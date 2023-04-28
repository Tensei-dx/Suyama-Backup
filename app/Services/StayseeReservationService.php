<?php

namespace App\Services;

use App\Mail\BookingCanceled;
use App\Mail\BookingCreated;
use App\Mail\BookingUpdated;
use App\Mail\RemindBookingMail;
use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\ParamSettings;
use App\Models\Room;
use App\Models\User;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class StayseeReservationService
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // fetchReservationData             (1.0) Get the latest reservation from the Staysee API
    // getExistingReservations          (2.0) Compare, filter and retrieve only the reservations that already exists in the DB
    // getNewReservations               (3.0) Compare, filter and retrieve only the reservations that are not yet synced in the DB
    // createBooking                    (4.0) Save a new reservation to a BookPMS entity
    // updateBooking                    (5.0) Update a reservation, then sync its allocated rooms
    // cancelBooking                    (6.0) Cancel and delete all data related to the booking
    // deleteExpiredBookings            (7.0) Delete reservations that were already check out
    // isBookingScheduleModified        (8.0) Determines if the booking schedule has been changed since the last synced
    // updateOldBookings                (9.0) Update room status for old bookings
    // validateReservationContent       (10.0) Validate the reservation if it contains complete and valid information

    use CommonFunctions;

    /**
     * The hostname for the Staysee API
     *
     * @var string
     */
    private string $hostname = "https://api.staysee.jp";

    /**
     * @var int[] STATUS_CANCEL
     */
    const STATUS_CANCEL = [1, 14, 15, 9, 10, 2, 3];

    /**
     * @var int[] STATUS_RESERVED
     */
    const STATUS_RESERVED = [4, 11, 13, 12];

    /**
     * @var int[] STATUS_CHECK_IN
     */
    const STATUS_CHECK_IN = [5];

    /**
     * @var int[] STATUS_CHECK_OUT
     */
    const STATUS_CHECK_OUT = [7];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> fetchReservationData
     * <Function> Get the latest reservation from the Staysee API
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function fetchReservationData()
    {
        $dateOffset = (int) config('staysee.fetch_offset');
        $token = config('staysee.token');

        // calculate the lower and upper range of the date
        $startDate = CarbonImmutable::today()->addDays($dateOffset);
        $endDate = $startDate->addMonth();

        // fetch the reservation list
        return Http::withToken($token)
            ->get("{$this->hostname}/v1/reservations", [
                'date' => $startDate->toDateString(),
                'until_date' => $endDate->toDateString()
            ]);
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> getExistingReservations
     * <Function> Compare, filter and retrieve only the reservations
     *            that already exists in the DB
     *
     * @param  array  $reservations
     * @return \Illuminate\Support\Collection
     */
    public function getExistingReservations(array $reservations)
    {
        $updatedReservationIDs = collect($reservations)->pluck('id');
        $localReservationIDs = BookPMS::all()->pluck('BOOK_ID');

        $existingReservationIDs = $updatedReservationIDs->intersect($localReservationIDs);

        return collect($reservations)->filter(
            function ($reservation) use ($existingReservationIDs) {
                return $existingReservationIDs->contains($reservation['id']);
            }
        );
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> getNewReservations
     * <Function> Compare, filter and retrieve only the reservations
     *            that are not yet synced in the DB
     *
     * @param  array  $reservations
     * @return \Illuminate\Support\Collection
     */
    public function getNewReservations(array $reservations)
    {
        $updatedReservationIDs = collect($reservations)->pluck('id');
        $localReservationIDs = BookPMS::all()->pluck('BOOK_ID');

        $newReservationIDs = $updatedReservationIDs->diff($localReservationIDs);

        return collect($reservations)->filter(
            function ($reservation) use ($newReservationIDs) {
                return $newReservationIDs->contains($reservation['id']);
            }
        );
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> createBooking
     * <Function> Save a new reservation to a BookPMS entity
     *
     * @param  array  $reservation
     * @param  array  $errorCodes
     * @return \Illuminate\Http\Response
     */
    public function createBooking(array $reservation, array $errorCodes = [])
    {
        // get the rooms reserved by the reservation
        $reservedRoomIDs = collect($reservation['allocate_rooms'])
            ->groupBy('room_id')
            ->keys();

        // check if the reservation has at least one room reserved
        if ($reservedRoomIDs->isEmpty()) {
            return response("Reservation does not have an allocated room", 400);
        }

        // set the $validFlag to true if the there are no error codes
        $validFlag = empty($errorCodes);

        /*
        |--------------------------------------------------------------------------
        | PAID_FLAG
        |--------------------------------------------------------------------------
        | This flag will distinguish if the reservation is considered paid
        */
        $PAID_FLAG = $reservation['payment_amount'] > 0;

        // create a booking
        $booking = BookPMS::create([
            'BOOK_ID' => $reservation['id'],
            'CONTACT_NUMBER' => $reservation['tel'],
            'EMAIL' => $reservation['email'],
            'FIRST_NAME' => $reservation['name_kanji'],
            'ADDRESS' => $reservation['address'],
            'READY_TO_SEND_FLAG' => $validFlag,
            'PAID_FLAG' => $PAID_FLAG,
            'PMS_UPDATED_AT' => $reservation['updated_at']
        ]);

        // initialize StayseeAllocateRoomService class
        $stayseeAllocateRoomService = new StayseeAllocateRoomService();

        // create Book_Room record for all the allocated rooms
        foreach ($reservedRoomIDs as $roomID) {
            $stayseeAllocateRoomService->storeBookingRoom($reservation, $roomID);
        }

        // Count the bookingRoom associated to the booking
        $booking->loadCount('bookingRooms');

        if ($booking->booking_rooms_count === 0) {
            $booking->delete();
            return response("The booking does not have a valid room reservation", 400);
        }

        $this->reportInvalidReservation($reservation, $errorCodes);

        // load all the data associated to the booking
        $booking->load('bookingRoomAndUser');

        // send BookingCreated mailable class to the guest's email address
        if ($booking->PAID_FLAG && $booking->READY_TO_SEND_FLAG && !$booking->BOOKING_CREATED_MAIL_SENT_FLAG) {
            Mail::to($booking->EMAIL)->send(new BookingCreated($booking, $reservation));
            $booking->update(['BOOKING_CREATED_MAIL_SENT_FLAG' => true]);
        }

        // return a 201 status code
        return response("success", 201);
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> updateBooking
     * <Function> Update a reservation, then sync its allocated rooms
     *
     * @param  array  $reservation
     * @param  array  $errorCodes
     * @return \Illuminate\Http\Response
     */
    public function updateBooking(array $reservation, array $errorCodes = [])
    {
        // check booking if exists, otherwise, return 400
        $booking = BookPMS::find($reservation['id']);
        if (!$booking) {
            return response('booking not found', 400);
        }

        // check if the booking is still in synced, otherwise, return 400
        $UPDATED_AT_LOCAL = new Carbon($booking->PMS_UPDATED_AT);
        $UPDATED_AT_CLOUD = new Carbon($reservation['updated_at']);
        if ($UPDATED_AT_LOCAL->equalTo($UPDATED_AT_CLOUD)) {
            return response('no change', 200);
        }

        /*
        |--------------------------------------------------------------------------
        | $BOOKING_SCHEDULE_MODIFIED
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's check_in_date_time and
        | check_out_date_time has been modified
        */
        $BOOKING_SCHEDULE_MODIFIED = $this->isBookingScheduleModified(
            $reservation['id'],
            $reservation['check_in_date_time'],
            $reservation['check_out_date_time']
        );

        // initialize StayseeAllocateRoomService class
        $allocateRoomService = new StayseeAllocateRoomService();

        // if there are removed allocated room, destroy them
        $removedRoomIDs = $allocateRoomService->getRemovedAllocatedRooms($reservation);
        foreach ($removedRoomIDs as $roomID) {
            $allocateRoomService->destroyBookingRoom($reservation, $roomID);
        }

        // if there are new allocated room, store them
        $addedRoomIDs = $allocateRoomService->getNewAllocatedRooms($reservation);
        foreach ($addedRoomIDs as $roomID) {
            $allocateRoomService->storeBookingRoom($reservation, $roomID);
        }

        // if there are changes in the details of the allocated rooms, update them
        $existingRoomIDs = $allocateRoomService->getExistingAllocatedRooms($reservation);
        foreach ($existingRoomIDs as $roomID) {
            $allocateRoomService->updateBookingRoom($reservation, $roomID);
        }

        /*
        |--------------------------------------------------------------------------
        | PAID_FLAG
        |--------------------------------------------------------------------------
        | This flag will distinguish if the reservation is considered paid
        */
        $PAID_FLAG = $reservation['payment_amount'] > 0;

        /*
        |--------------------------------------------------------------------------
        | $RECENTLY_PAID
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's PAID_FLAG was recently
        | changed from false to true
        */
        $RECENTLY_PAID = (bool) !$booking->PAID_FLAG && (bool) $PAID_FLAG;

        /*
        |--------------------------------------------------------------------------
        | $EMAIL_MODIFIED
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's email address has been
        | modified since the last synced
        */
        $EMAIL_MODIFIED = $booking->EMAIL !== $reservation['email'];

        /*
        |--------------------------------------------------------------------------
        | $ALLOCATED_ROOMS_MODIFIED
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's allocate_rooms attribute
        | has been modified since the last synced
        */
        $ALLOCATED_ROOMS_MODIFIED = $removedRoomIDs->isNotEmpty() || !!$addedRoomIDs->isNotEmpty();

        /*
        |--------------------------------------------------------------------------
        | $IS_VALID
        |--------------------------------------------------------------------------
        | This flag will distinguish if the attributes of the booking is valid
        | by checking the error codes
        */
        $IS_VALID = empty($errorCodes);

        // update the booking info
        $booking->update([
            'CONTACT_NUMBER' => $reservation['tel'],
            'EMAIL' => $reservation['email'],
            'FIRST_NAME' => $reservation['name_kanji'],
            'ADDRESS' => $reservation['address'],
            'PAID_FLAG' => $PAID_FLAG,
            'READY_TO_SEND_FLAG' => $IS_VALID,
            'PMS_UPDATED_AT' => $reservation['updated_at'],
        ]);

        // count the Book_Room entities associated to the booking
        // if no Book_Room entities were saved, delete the BookPMS entity
        $booking->loadCount('bookingRooms');
        if ($booking->booking_rooms_count === 0) {
            $booking->delete();
            return response('no saved booking rooms', 400);
        }

        // load all the data associated to the booking
        $booking->load('bookingRoomAndUser');

        // report error codes
        $this->reportInvalidReservation($reservation, $errorCodes);

        // if the booking has not been paid yet, don't send an email
        if (!$booking->READY_TO_SEND_FLAG || !$booking->PAID_FLAG) {
            return response('success');
        }

        /*
        |--------------------------------------------------------------------------
        | $NOTIFY_CHANGES
        |--------------------------------------------------------------------------
        | This flag will distinguish if the changes made to the booking
        | information needs to be notified to the guest.
        */
        $NOTIFY_CHANGES = $EMAIL_MODIFIED || $BOOKING_SCHEDULE_MODIFIED || $ALLOCATED_ROOMS_MODIFIED;

        if ($booking->BOOKING_CREATED_MAIL_SENT_FLAG) {
            if ($NOTIFY_CHANGES) {
                if ($EMAIL_MODIFIED) {
                    Mail::to($booking->EMAIL)->send(new BookingCreated($booking, $reservation));
                    $booking->update([
                        'BOOKING_CREATED_MAIL_SENT_FLAG' => true
                    ]);
                } else {
                    Mail::to($booking->EMAIL)->send(new BookingUpdated($booking, $reservation));
                }
            } else {
                // Don't send email
            }
        } else {
            Mail::to($booking->EMAIL)->send(new BookingCreated($booking, $reservation));
            $booking->update([
                'BOOKING_CREATED_MAIL_SENT_FLAG' => true
            ]);
        }
        // if ($NOTIFY_CHANGES && $booking->PAID_FLAG && $booking->READY_TO_SEND_FLAG) {
        //     if ($RECENTLY_PAID || $EMAIL_MODIFIED || $booking->BOOKING_CREATED_MAIL_SENT_FLAG) {
        //         Mail::to($booking->EMAIL)->send(new BookingCreated($booking, $reservation));
        //         $booking->update([
        //             'BOOKING_CREATED_MAIL_SENT_FLAG' => true
        //         ]);
        //     } else {
        //         Mail::to($booking->EMAIL)->send(new BookingUpdated($booking, $reservation));
        //     }
        // }

        return response('success');
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> cancelBooking
     * <Function> Cancel and delete all data related to the booking
     *
     * @param  array  $reservation
     * @return \Illuminate\Http\Response
     */
    public function cancelBooking(array $reservation)
    {
        // initialize StayseeAllocateRoomService class
        $stayseeAllocateRoomService = new StayseeAllocateRoomService();

        // check if the booking exists, otherwise, return 400
        $booking = BookPMS::find($reservation['id']);
        if (!$booking) {
            return response("Booking not found", 404);
        }

        // delete all the data associated to the booking
        $bookingRooms = Book_Room::where('BOOK_ID', $reservation['id'])->get();
        if (!!$bookingRooms) {
            foreach ($bookingRooms as $bookingRoom) {
                $stayseeAllocateRoomService->destroyBookingRoom($reservation, $bookingRoom->ROOM_ID);
            }

            // send a BookingCanceled email to the guest if email exists
            if ($booking->EMAIL) {
                Mail::to($booking->EMAIL)->send(new BookingCanceled($booking, $reservation));
            }
        }
        // delete the booking itself
        $booking->delete();

        // return status code 204 for success
        return response()->noContent();
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> deleteExpiredBookings
     * <Function> Delete reservations that were already check out
     *            and already pass the expiration date
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteExpiredBookings()
    {
        $bookingExpiration = (int) config('staysee.days_before_expire');
        // get the expiration date
        $evaluateExpirationDate = Carbon::today()->subDays($bookingExpiration);

        // get the booking rooms that the check out time
        // already passed the evaluated expiration date
        $expiredBookingRooms = Book_Room::whereDate('CHECK_OUT_TIME', '<', $evaluateExpirationDate)
            ->get();

        // delete the user and the booking room itself
        foreach ($expiredBookingRooms as $bookingRoom) {
            // Delete the user of the bookingRoom
            User::destroy($bookingRoom->USER_ID);
            $bookingRoom->delete();
        }

        // delete all bookings
        BookPMS::whereDoesntHave('bookingRooms')->delete();

        return response()->noContent();
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> isBookingScheduleModified <br>
     * <Function> Determines if the booking schedule has been changed since the last synced <br>
     *
     * @param  int  $bookID
     * @param  string  $CIDT Check-in datetime string
     * @param  string  $CODT Check-out datetime string
     * @return bool
     */
    public function isBookingScheduleModified(int $bookID, string $CIDT, string $CODT): bool
    {
        $bookRoom = Book_Room::firstWhere('BOOK_ID', $bookID);

        $CHECK_IN_MODIFIED = Carbon::parse($bookRoom->ARRIVAL_TIME)->notEqualTo(Carbon::parse($CIDT));

        $CHECK_OUT_MODIFIED = Carbon::parse($bookRoom->DEPARTURE_TIME)->notEqualTo(Carbon::parse($CODT));

        return $CHECK_IN_MODIFIED || $CHECK_OUT_MODIFIED;
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> updateOldBookings <br>
     * <Function> Update room status for old bookings <br>
     *
     * @param  array  $reservations
     * @return \Illuminate\Http\Response
     */
    public function updateOldBookings(array $reservations)
    {
        // get the old bookings that are not yet checked out
        $latestReservationIDs = collect($reservations)->pluck('id');
        $oldBookings = BookPMS::with('bookingRooms')->whereNotIn('BOOK_ID', $latestReservationIDs)->get();

        foreach ($oldBookings as $booking) {
            // check all the check out time of the Book_Room entities
            foreach ($booking->bookingRooms as $bookingRoom) {

                // compare the checkout time to current time
                $coDT = Carbon::parse($bookingRoom->CHECK_OUT_TIME);
                $now = Carbon::now();
                if ($now->isAfter($coDT)) {
                    // if the check out time has passed, make the room available
                    $room = Room::find($bookingRoom->ROOM_ID);
                    $room->STATUS_ID = 203;
                    $room->save();
                }
            }
        }

        return response('success', 200);
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> validateReservation <br>
     * <Function> Get the error codes appropriate to the invalid attributes of the reservation.
     *
     * @param  array  $reservation
     * @return array  $codes
     */
    public function validateReservation(array $reservation)
    {
        $codes = [];

        // Add error code E002 if the 'name_kanji' is empty
        if (empty($reservation['name_kanji'])) $codes[] = 'E002';

        // Add error code E003 if the 'email' is empty
        if (empty($reservation['email'])) $codes[] = 'E003';

        // Add error code E004 if the 'check_in_time' uses the default value
        if ($reservation['check_in_time'] === '00:00') $codes[] = 'E004';

        // Add error code E005 if the 'check_out_time' uses the default value
        if ($reservation['check_out_time'] === '23:59') $codes[] = 'E005';

        return $codes;
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> reportInvalidReservation <br>
     * <Function> Report the error codes
     *
     * @param  array  $reservation
     * @param  array  $codes
     * @return bool
     */
    public function reportInvalidReservation(array $reservation, array $codes)
    {
        $roomID = collect($reservation['allocate_rooms'])
            ->groupBy('room_id')
            ->keys()
            ->first();

        foreach ($codes as $code) {
            $this->logsNotification($code, $roomID, 0, $reservation['id']);
        }

        return true;
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> sendRemindMail <br>
     * <Function> Send a email to remind guest about there reservation <br>
     *
     * @return bool
     */
    public function sendRemindMail()
    {
        // get the offset value
        $offset = ParamSettings::value('MAIL_REMIND_OFFSET');

        // this will be the date that will be used to retrieve the reservations
        // that needs to be reminded for check-in.
        $checkInDate = today()->addDays($offset);

        // include only paid reservations
        $bookings = BookPMS::where('PAID_FLAG', true)
            // exclude reservations created today
            ->whereDate('CREATED_AT', '!=', today())
            // include only reservations scheduled to check-in to the given date
            ->whereHas('bookingRooms', function (Builder $query) use ($checkInDate) {
                return $query->whereDate('CHECK_IN_TIME', $checkInDate);
            })
            // append bookingRooms relation
            ->with('bookingRooms')
            ->get();

        // fetch latest reservation data
        $response = $this->fetchReservationData();

        // handle failed response
        if ($response->failed()) return false;

        // parse the data from the response
        $reservations = collect($response->json());

        // send remind booking mail to each item of the collection
        foreach ($bookings as $booking) {
            $reservation = $reservations->firstWhere('id', $booking->BOOK_ID);
            Mail::to($booking->EMAIL)->send(new RemindBookingMail($booking, $reservation));
        }
        return true;
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> updateCheckedInBooking <br>
     * <Function> Update booking for reservation with checked-in status <br>
     *
     * @param  array  $reservation
     * @param  array  $errorCodes
     * @return \Illuminate\Http\Response
     */
    public function updateCheckedInBooking(array $reservation, array $errorCodes = [])
    {
        $booking = BookPMS::find($reservation['id']);

        if (!$booking) {
            return response('booking not found', 400);
        }
        /*
        |--------------------------------------------------------------------------
        | $BOOKING_SCHEDULE_MODIFIED
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's check_in_date_time and
        | check_out_date_time has been modified
        */
        $BOOKING_SCHEDULE_MODIFIED = $this->isBookingScheduleModified(
            $reservation['id'],
            $reservation['check_in_date_time'],
            $reservation['check_out_date_time']
        );

        // initialize StayseeAllocateRoomService class
        $allocateRoomService = new StayseeAllocateRoomService();

        // if there are removed allocated room, destroy them
        $removedRoomIDs = $allocateRoomService->getRemovedAllocatedRooms($reservation);
        foreach ($removedRoomIDs as $roomID) {
            $allocateRoomService->destroyBookingRoom($reservation, $roomID);
        }

        // if there are new allocated room, store them
        $addedRoomIDs = $allocateRoomService->getNewAllocatedRooms($reservation);
        foreach ($addedRoomIDs as $roomID) {
            $allocateRoomService->storeBookingRoom($reservation, $roomID);
        }

        // if there are changes in the details of the allocated rooms, update them
        $existingRoomIDs = $allocateRoomService->getExistingAllocatedRooms($reservation);
        foreach ($existingRoomIDs as $roomID) {
            $allocateRoomService->updateBookingRoom($reservation, $roomID);
        }

        /*
        |--------------------------------------------------------------------------
        | PAID_FLAG
        |--------------------------------------------------------------------------
        | This flag will distinguish if the reservation is considered paid
        */
        $PAID_FLAG = $reservation['payment_amount'] > 0;

        /*
        |--------------------------------------------------------------------------
        | $EMAIL_MODIFIED
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's email address has been
        | modified since the last synced
        */
        $EMAIL_MODIFIED = $booking->EMAIL !== $reservation['email'];

        /*
        |--------------------------------------------------------------------------
        | $ALLOCATED_ROOMS_MODIFIED
        |--------------------------------------------------------------------------
        | This flag will distinguish if the booking's allocate_rooms attribute
        | has been modified since the last synced
        */
        $ALLOCATED_ROOMS_MODIFIED = $removedRoomIDs->isNotEmpty() || !!$addedRoomIDs->isNotEmpty();

        /*
        |--------------------------------------------------------------------------
        | $IS_VALID
        |--------------------------------------------------------------------------
        | This flag will distinguish if the attributes of the booking is valid
        | by checking the error codes
        */
        $IS_VALID = empty($errorCodes);

        // update the booking info
        $booking->update([
            'CONTACT_NUMBER' => $reservation['tel'],
            'EMAIL' => $reservation['email'],
            'FIRST_NAME' => $reservation['name_kanji'],
            'ADDRESS' => $reservation['address'],
            'PAID_FLAG' => $PAID_FLAG,
            'READY_TO_SEND_FLAG' => $IS_VALID,
            'PMS_UPDATED_AT' => $reservation['updated_at'],
        ]);

        // load all the data associated to the booking
        $booking->load('bookingRoomAndUser');

        // report error codes
        $this->reportInvalidReservation($reservation, $errorCodes);

        /*
        |--------------------------------------------------------------------------
        | $NOTIFY_CHANGES
        |--------------------------------------------------------------------------
        | This flag will distinguish if the changes made to the booking
        | information needs to be notified to the guest.
        */
        $NOTIFY_CHANGES = $EMAIL_MODIFIED || $ALLOCATED_ROOMS_MODIFIED;

        // add guard clause to skip sending email if READY_TO_SEND_FLAG is false
        if (!$booking->READY_TO_SEND_FLAG) {
            return response('success');
        }

        if ($booking->BOOKING_CREATED_MAIL_SENT_FLAG) {
            if ($NOTIFY_CHANGES) {
                Mail::to($booking->EMAIL)->send(new BookingUpdated($booking, $reservation));
            }
        } else {
            Mail::to($booking->EMAIL)->send(new BookingCreated($booking, $reservation));
            $booking->update([
                'BOOKING_CREATED_MAIL_SENT_FLAG' => true
            ]);
        }

        return response('success');
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> updateCheckedOutBooking <br>
     * <Function> Update booking for reservation with checked-out status <br>
     *
     * @param  array  $reservation
     * @return \Illuminate\Http\Response
     */
    public function updateCheckedOutBooking(array $reservation)
    {
        $booking = BookPMS::find($reservation['id']);

        if (!$booking) {
            return response('booking not found', 400);
        }

        // initialize StayseeAllocateRoomService class
        $allocateRoomService = new StayseeAllocateRoomService();

        // if there are removed allocated room, destroy them
        $removedRoomIDs = $allocateRoomService->getRemovedAllocatedRooms($reservation);
        foreach ($removedRoomIDs as $roomID) {
            $allocateRoomService->destroyBookingRoom($reservation, $roomID);
        }

        // if there are new allocated room, store them
        $addedRoomIDs = $allocateRoomService->getNewAllocatedRooms($reservation);
        foreach ($addedRoomIDs as $roomID) {
            $allocateRoomService->storeBookingRoom($reservation, $roomID);
        }

        // if there are changes in the details of the allocated rooms, update them
        $existingRoomIDs = $allocateRoomService->getExistingAllocatedRooms($reservation);
        foreach ($existingRoomIDs as $roomID) {
            $allocateRoomService->updateBookingRoom($reservation, $roomID);
        }

        // update the booking info
        $booking->update([
            'PMS_UPDATED_AT' => $reservation['updated_at'],
        ]);

        // load all the data associated to the booking
        $booking->load('bookingRoomAndUser');

        return response('success');
    }
}
