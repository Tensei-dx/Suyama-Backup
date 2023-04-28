<?php

/**
 * <System Name> iBMS
 * <File Name> StayseeReservationController.php
 */

namespace App\Http\Controllers\Staysee;

use App\Events\ReservationSyncedEvent;
use App\Http\Controllers\Controller;
use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Services\StayseeReservationService;
use App\Services\StayseeRoomService;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


/**
 * <Class Name> StayseeReservationController
 *
 * Create : 2021.08.09 TP Uddin
 * Update : 2021.08.17 TP Uddin     SPRINT_03 Task035
 *          2021.08.17 TP Uddin     SPRINT_03 Task036
 *          2021.08.18 TND Okada    SPRINT_03 Task008
 *          2021.08.18 TND Okada    SPRINT_03 Task009
 *
 * <Overview>
 * @package
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version 1.0
 * @copyright
 */
class StayseeReservationController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // sync                     (1.0) Synchronize the latest reservation data from Staysee
    // patchStayseeCheckIn      (2.0) Update the status of Staysee to check-in
    // patchStayseeCheckOut     (3.0) Update the status of Staysee to check-out

    use CommonFunctions;

    // /**
    //  * <Layer number> (1.0)
    //  *
    //  * <Processing name> sync <br>
    //  * <Function> Synchronize the latest reservation data from Staysee <br>
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function sync()
    // {
    //     // initialize service classes
    //     $reservationService = new StayseeReservationService();
    //     $roomService = new StayseeRoomService();

    //     // retrieve the latest reservation data, return status code 400 if fails
    //     $response = $reservationService->fetchReservationData();

    //     // report an error when the API request fails
    //     if ($response->failed()) {
    //         $this->logsNotification('E001', null, 0);

    //         return response('API failed', 400);
    //     }

    //     // contains the latest reservation data via Staysee API
    //     $latestReservationData = $response->json();

    //     foreach ($latestReservationData as $reservationData) {

    //         // look for the booking
    //         $booking = BookPMS::find($reservationData['id']);

    //         // cancel the booking if the canceled attribute is true
    //         if ($reservationData['canceled'] || in_array($reservationData['status'], $reservationService::STATUS_CANCEL)) {
    //             if (!!$booking) {
    //                 $reservationService->cancelBooking($reservationData);
    //             }
    //             continue;
    //         }

    //         // skip if the reservation has no allocate room
    //         if (empty($reservationData['allocate_rooms'])) {
    //             continue;
    //         }

    //         $checkouttime = Carbon::parse($reservationData['check_out_date_time']);

    //         // if the check out time of the reservation already passed, skip it.
    //         if ($checkouttime->isBefore(Carbon::now())) {
    //             continue;
    //         }

    //         // if the reservation content  is incomplete or invalid, continue to the next loop.
    //         $errorCodes = $reservationService->validateReservation($reservationData);

    //         if (in_array($reservationData['status'], $reservationService::STATUS_RESERVED)) {
    //             // process for reservation that is in the status group reserved
    //             if (!!$booking) {
    //                 $newUpdatedAt = Carbon::parse($reservationData['updated_at']);
    //                 $oldUpdatedAt = Carbon::parse($booking->PMS_UPDATED_AT);
    //                 if ($oldUpdatedAt->equalTo($newUpdatedAt)) {
    //                     continue;
    //                 }
    //                 // if the booking exists, update the booking data
    //                 $reservationService->updateBooking($reservationData, $errorCodes);
    //             } else {
    //                 // otherwise, create a new booking data
    //                 $reservationService->createBooking($reservationData, $errorCodes);
    //             }
    //         } elseif (in_array($reservationData['status'], $reservationService::STATUS_CHECK_IN) && !$booking) {
    //             // process for reservation that is in the status group check in but no booking data yet
    //             $reservationService->createBooking($reservationData, $errorCodes);
    //         }
    //     }

    //     // after the reservations have been synced, update the status of all rooms
    //     $roomService->updateRoomStatus($latestReservationData);

    //     $this->logsNotification('I014', null, 4);
    //     event(new ReservationSyncedEvent());

    //     return response()->noContent();
    // }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> check-in API <br>
     * <Function> When Guest is Login it will update on Staysee by staysee API <br>
     *            URL: http://localhost/patchStayseeCheckIn/00<br>
     *            METHOD: PATCH
     *
     * @param object $user
     * @return $responce
     */
    public function patchStayseeCheckIn(object $user)
    {
        $userid = $user->USER_ID;
        $bookid = BOOK_ROOM::where('USER_ID', $userid)->value('BOOK_ID');

        if (is_null($bookid)) {
            return false;
        }

        $response = Http::withToken(env('STAYSEE_TOKEN'))
            ->patch('https://api.staysee.jp/v1/reservations/' . $bookid . '/checkin');

        if ($response == 'failed') {
            return false;
        }
        return true;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> check-out API <br>
     * <Function> When Guest is Logout it will update on Staysee by staysee API <br>
     *            URL: http://localhost/patchStayseeCheckOut/00<br>
     *            METHOD: PATCH
     *
     * @param int $bookid
     * @return $responce
     */
    public function patchStayseeCheckOut(int $bookid)
    {
        $responce = Http::withToken(env('STAYSEE_TOKEN'))
            ->patch('https://api.staysee.jp/v1/reservations/' . $bookid . '/checkout');

        return $responce;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> sync <br>
     * <Function> Synchronize the latest reservation data from Staysee <br>
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        // initialize service classes
        $rsvService = new StayseeReservationService();

        /*
        |--------------------------------------------------------------------------
        | Fetch the latest reservation data
        |--------------------------------------------------------------------------
        | Retrieve the latest reservation data from the Staysee.
        | If an error occur, abort to status code 400.
        */
        $response1 = $rsvService->fetchReservationData();
        if ($response1->failed()) {
            $this->logsNotification('E001', null, 0);
            abort('API failed', 400);
        }
        $reservations = $response1->json();

        foreach ($reservations as $reservation) {

            // get the booking if exists
            $booking = BookPMS::find($reservation['id']);

            /*
            |--------------------------------------------------------------------------
            | Handle Canceled Reservations
            |--------------------------------------------------------------------------
            | If the booking exists and the status of the reservation is considered
            | canceled, cancel the booking
            */
            if (!!$booking && ($reservation['canceled'] || in_array($reservation['status'], $rsvService::STATUS_CANCEL))) {
                try {
                    DB::beginTransaction();
                    $rsvService->cancelBooking($reservation);
                    DB::commit();
                    continue;
                } catch (\Throwable $th) {
                    DB::rollBack();
                    report($th);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Handle Non-canceled Reservations with no Allocated Rooms
            |--------------------------------------------------------------------------
            | If the reservation has an empty allocate_rooms attribute, skip it.
            */
            if (empty($reservation['allocate_rooms'])) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Check the validation of the Reservations
            |--------------------------------------------------------------------------
            | Collect all the error codes for the invalid attributes of the reservation.
            | These error codes will be reported later if the booking was stored successfully.
            */
            $errorCodes = $rsvService->validateReservation($reservation);

            /*
            |--------------------------------------------------------------------------
            | Handle Reservations in Reserved Status
            |--------------------------------------------------------------------------
            | If the reservation was already stored, check if the updated_at attribute
            | was changed from the last synced. If true, update the booking.
            | If the reservation haven't stored yet in the first place, store the booking.
            */
            if (in_array($reservation['status'], $rsvService::STATUS_RESERVED)) {
                if (!$booking) {
                    try {
                        DB::beginTransaction();
                        $rsvService->createBooking($reservation, $errorCodes);
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        report($th);
                    }
                } else {
                    try {
                        $NEW_UPDATED_AT = Carbon::parse($reservation['updated_at']);
                        $OLD_UPDATED_AT = Carbon::parse($booking->PMS_UPDATED_AT);
                        if ($OLD_UPDATED_AT->equalTo($NEW_UPDATED_AT)) continue;

                        DB::beginTransaction();
                        $rsvService->updateBooking($reservation, $errorCodes);
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        report($th);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Handle Reservations in Checked-In Status
            |--------------------------------------------------------------------------
            | If the reservation was already stored, check if the updated_at attribute
            | was changed from the last synced. If true, update the booking.
            | If the reservation haven't stored yet in the first place, store the booking.
            |
            */
            if (in_array($reservation['status'], $rsvService::STATUS_CHECK_IN)) {
                if (!$booking) {
                    try {
                        DB::beginTransaction();
                        $rsvService->createBooking($reservation, $errorCodes);
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        report($th);
                    }
                } else {
                    $NEW_UPDATED_AT = Carbon::parse($reservation['updated_at']);
                    $OLD_UPDATED_AT = Carbon::parse($booking->PMS_UPDATED_AT);
                    if ($OLD_UPDATED_AT->equalTo($NEW_UPDATED_AT)) continue;

                    try {
                        DB::beginTransaction();
                        $rsvService->updateCheckedInBooking($reservation, $errorCodes);
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        report($th);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Handle Reservations in CheckedOut Status
            |--------------------------------------------------------------------------
            | If the reservation was already stored, check if the updated_at attribute
            | was changed from the last synced. If true, update the booking.
            | If the reservation haven't stored yet in the first place, don't store
            | the booking.
            */
            if (in_array($reservation['status'], $rsvService::STATUS_CHECK_OUT)) {
                if (!!$booking) {
                    $NEW_UPDATED_AT = Carbon::parse($reservation['updated_at']);
                    $OLD_UPDATED_AT = Carbon::parse($booking->PMS_UPDATED_AT);
                    if ($OLD_UPDATED_AT->equalTo($NEW_UPDATED_AT)) continue;

                    try {
                        DB::beginTransaction();
                        $rsvService->updateCheckedOutBooking($reservation);
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        report($th);
                    }
                }
            }
        }

        $roomService = new StayseeRoomService();
        // after the reservations have been synced, update the status of all rooms
        $roomService->updateRoomStatus($reservations);

        $this->logsNotification('I014', null, 4);
        event(new ReservationSyncedEvent());

        return response()->noContent();
    }
}
