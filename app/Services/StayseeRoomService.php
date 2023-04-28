<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * <Class Name> StayseeRoomService
 */
class StayseeRoomService
{
    /*************************************************************/
    /* Processing Heirarchy                                      */
    /*************************************************************/
    // fetchRoomData                (1.0) Fetch the latest room data from the Staysee API
    // deleteRemovedRooms           (2.0) Analyze and delete the removed rooms
    // storeNewRooms                (3.0) Analyze and store the newly added rooms
    // updateRooms                  (4.0) Update all rooms from the Staysee API
    // updateRoomStatus             (5.0) Update the status of the room based on today's reservations

    /**
     * The hostname for Staysee API.
     *
     * @var  string  $hostname
     */
    private string $hostname = 'https://api.staysee.jp';

    /**
     * The access token for Staysee API.
     *
     * @var  string  $token
     */
    private string $token;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->token = config('staysee.token');
    }

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> fetchRoomData <br>
     * <Function> Fetch the latest room data from the Staysee API <br>
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function fetchRoomData()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->get("{$this->hostname}/v1/rooms");
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> deleteRemovedRooms <br>
     * <Function> Analyze and delete the removed rooms <br>
     *
     * @param  array  $items
     * @return \Illuminate\Http\Response
     */
    public function deleteRemovedRooms(array $items)
    {
        // prepare data for comparison
        $newRoomIds = collect($items)->pluck('id')->toArray();

        // get rooms where their ROOM_ID is not in the $newRoomIds
        $removedRooms = Room::all()->except($newRoomIds);

        // delete excluded rooms
        $removedRooms->each->delete();

        // return 204 no content status code
        return response()->noContent();
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> storeNewRooms <br>
     * <Function> Analyze and store the newly added rooms <br>
     *
     * @param  array  $items
     * @return \Illuminate\Http\Response
     */
    public function storeNewRooms(array $items)
    {
        // prepare data for comparison
        $oldRoomIds = Room::all()->pluck('ROOM_ID')->toArray();

        // get rooms where their id is not in the $oldRoomIds
        $newRooms = collect($items)->whereNotIn('id', $oldRoomIds);

        // store new rooms
        $newRooms->each(function ($newRoom) {
            $room = new Room();
            $room->ROOM_ID = $newRoom['id'];
            $room->FLOOR_ID = 1;
            $room->ROOM_NAME = $newRoom['name'];
            $room->MAX_OCCUPANCY = 5;
            $room->STATUS_ID = 203;
            $room->EMERGENCY_FLAG = 0;
            $room->save();
        });

        // return 204 no content status code
        return response()->noContent();
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> updateRooms <br>
     * <Function> Update all rooms from the Staysee API
     *
     * @param  array  $items
     * @return \Illuminate\Http\Response
     */
    public function updateRooms(array $items)
    {
        // prepare data
        $updatedRoomData = collect($items);

        // update the room details from the latest room data
        foreach ($updatedRoomData as $updatedRoom) {
            try {
                // if the room does not exists, skip to the next loop
                $room = Room::findOrFail($updatedRoom['id']);

                // update the room details
                $room->ROOM_NAME = $updatedRoom['name'];
                $room->save();
            } catch (\Throwable $th) {
                continue;
            }
        }

        // return 204 no content status code
        return response()->noContent();
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> updateRoomStatus <br>
     * <Function> Update the status of the room based on today's reservations <br>
     *
     * @return \Illuminate\Http\Response
     */
    public function updateRoomStatus()
    {
        $rooms = Room::with('checkOutToday', 'checkInToday', 'bookingNow')->get();

        foreach ($rooms as $room) {
            $STATUS = 203;

            $checkOutToday = $room->checkOutToday;
            $checkInToday = $room->checkInToday;
            $currentlyCheckedIn = $room->bookingNow;

            if (!!$checkOutToday && !$checkInToday) {
                switch ($checkOutToday->RESERVATION_STATUS) {
                    case 201: // Checked in
                    case 203: // Available
                    case 205: // Reserved
                        // if booking status is 201, 203 or 205, room will inherit the status
                        $STATUS = $checkOutToday->RESERVATION_STATUS;
                        break;
                    case 202:
                        // for early checkout, room status will be 202 until the check out time
                        // otherwise, return 203
                        $STATUS = $checkOutToday->LATE_FOR_CHECKOUT ? 202 : 203;
                        break;
                }
                Log::info("1");
                Log::info($STATUS);
            } elseif (!$checkOutToday && !!$checkInToday) {
                switch ($checkInToday->RESERVATION_STATUS) {
                    case 201: // Checked in
                    case 202: // Checked out
                    case 203: // Available
                    case 205: // Reserved
                        // the room status will inherit the booking status
                        $STATUS = $checkInToday->RESERVATION_STATUS;
                        break;
                }
                Log::info("2");
                Log::info($STATUS);
            } elseif (!!$checkOutToday && !!$checkInToday) {

                switch ($checkOutToday->RESERVATION_STATUS) {
                    case 201:
                        // if the checkOutToday is still checked in, inherit status
                        $STATUS = 201;
                        break;
                    case 202:
                        // for early checkout, room status will be 202 until the check out time
                        if ($checkOutToday->LATE_FOR_CHECKOUT) {
                            $STATUS = 202;
                        } elseif ($checkInToday->RESERVATION_STATUS === 205) {
                            $STATUS = 205;
                        } elseif ($checkInToday->RESERVATION_STATUS === 202) {
                            $STATUS = 202;
                        } elseif ($checkInToday->RESERVATION_STATUS === 201) {
                            $STATUS = 201;
                        }
                        break;
                    case 205:
                        if ($checkInToday->RESERVATION_STATUS === 205) {
                            $STATUS = 205;
                        } elseif ($checkInToday->RESERVATION_STATUS === 202) {
                            $STATUS = 202;
                        } elseif ($checkInToday->RESERVATION_STATUS === 201) {
                            $STATUS = 201;
                        }
                        break;
                }
                Log::info("3");
                Log::info($STATUS);
            } else {
                // check if there is currently checked in reservation today
                // if yes, set status to 201, otherwise 203 or 204
                if (!!$currentlyCheckedIn) {
                    $STATUS = $currentlyCheckedIn->RESERVATION_STATUS;
                } elseif ($room->STATUS_ID === 204) {
                    $STATUS = 204;
                } else {
                    $STATUS = 203;
                }
                Log::info("4");
                Log::info($STATUS);
            }
            Log::info("データ挿入");
            Log::info($STATUS);
            Log::info("\n");
            $room->STATUS_ID = $STATUS;
            $room->save();
        }

        return response('success');
    }

    // /**
    //  * <Layer number> (5.0)
    //  *
    //  * <Processing name> updateRoomStatus <br>
    //  * <Function> Update the status of the room based on today's reservations <br>
    //  *
    //  * @param  array  $reservations
    //  * @return \Illuminate\Http\Response
    //  */
    // public function updateRoomStatus(array $reservations)
    // {
    //     $reservationService = new StayseeReservationService();

    //     // find and get the room
    //     $rooms = Room::all();

    //     foreach ($rooms as $room) {

    //         // get the date string today
    //         $dateToday = Carbon::today()->toDateString();

    //         // initialize variables
    //         $todayCO = $coDT = null;
    //         $todayCI = $ciDT = null;

    //         foreach ($reservations as $reservation) {
    //             // get the reserved rooms of the reservation
    //             $reservedRooms = collect($reservation['allocate_rooms'])
    //                 ->groupBy('room_id')
    //                 ->keys();

    //             // if the room is not included in the reserved rooms, break out of the loop
    //             if (!$reservedRooms->contains($room->ROOM_ID)) {
    //                 continue;
    //             }

    //             // if the check out date is today, assign it to the variables
    //             if ($dateToday == $reservation['end_date']) {
    //                 $todayCO = $reservation;
    //                 $coDT = Carbon::parse($reservation['check_out_date_time']);
    //             }

    //             // if the check in date is today, assign it to the variables
    //             if ($dateToday == $reservation['start_date']) {
    //                 $todayCI = $reservation;
    //                 $ciDT = Carbon::parse($reservation['check_in_date_time']);
    //             }
    //         }

    //         // get the current timestamp for comparison
    //         $now = Carbon::now();

    //         if (!$todayCO && !$todayCI && ($room->STATUS_ID !== 204)) {
    //             // status is available because there is no reservations today
    //             $room->STATUS_ID = 203;
    //         } elseif ($todayCO && !$todayCI && $now->isBefore($coDT)) {
    //             // has check out today; now is before check out time
    //             if (in_array($todayCO['status'], $reservationService::STATUS_RESERVED)) {
    //                 // check out today is in the reserved status
    //                 $room->STATUS_ID = 205;
    //             } elseif (in_array($todayCO['status'], $reservationService::STATUS_CHECK_IN)) {
    //                 // check out today is in the check in status
    //                 $room->STATUS_ID = 201;
    //             } elseif (in_array($todayCO['status'], $reservationService::STATUS_CHECK_OUT)) {
    //                 // check out today is in the check out status
    //                 $room->STATUS_ID = 202;
    //             }
    //         } elseif ($todayCO && !$todayCI && $now->isAfter($coDT) && ($room->STATUS_ID !== 204)) {
    //             // has check out today; now is after check out time
    //             // status is available, whether it is already check out or not
    //             $room->STATUS_ID = 203;
    //         } elseif (!$todayCO && $todayCI && $now->isBefore($ciDT)) {
    //             // has check in today; now is before check in time
    //             // status is reserved
    //             $room->STATUS_ID = 205;
    //         } elseif (!$todayCO && $todayCI && $now->isAfter($ciDT)) {
    //             // has check in today; now is after check in time
    //             if (in_array($todayCI['status'], $reservationService::STATUS_RESERVED)) {
    //                 // check in today is in reserved status
    //                 $room->STATUS_ID = 205;
    //             } elseif (in_array($todayCI['status'], $reservationService::STATUS_CHECK_IN)) {
    //                 // check in today is in check in status
    //                 $room->STATUS_ID = 201;
    //             } elseif (in_array($todayCI['status'], $reservationService::STATUS_CHECK_OUT)) {
    //                 // check in today is in check out status
    //                 $room->STATUS_ID = 202;
    //             }
    //         } elseif ($todayCO && $todayCI && $now->isBefore($coDT)) {
    //             // has check out and check in today; now is before the check out time
    //             if (in_array($todayCO['status'], $reservationService::STATUS_RESERVED)) {
    //                 // check out today is still in reserved status
    //                 $room->STATUS_ID = 205;
    //             } elseif (in_array($todayCO['status'], $reservationService::STATUS_CHECK_IN)) {
    //                 // check out today is in check in status
    //                 $room->STATUS_ID = 201;
    //             } elseif (in_array($todayCO['status'], $reservationService::STATUS_CHECK_OUT)) {
    //                 // check out today is in check out status
    //                 $room->STATUS_ID = 202;
    //             }
    //         } elseif ($todayCO && $todayCI && $now->isAfter($ciDT)) {
    //             // has check out and check in today; now is after the check in time
    //             if (in_array($todayCI['status'], $reservationService::STATUS_RESERVED)) {
    //                 // check in today is still in reserved status
    //                 $room->STATUS_ID = 205;
    //             } elseif (in_array($todayCI['status'], $reservationService::STATUS_CHECK_IN)) {
    //                 // check in today is in check in status
    //                 $room->STATUS_ID = 201;
    //             } elseif (in_array($todayCI['status'], $reservationService::STATUS_CHECK_OUT)) {
    //                 // check in today is in check out status
    //                 $room->STATUS_ID = 202;
    //             }
    //         } elseif ($todayCO && $todayCI && $now->isAfter($coDT) && $now->isBefore($ciDT)) {
    //             // has check out and check in today
    //             // now is after the check out time; now is before the check in time
    //             // status is reserved
    //             $room->STATUS_ID = 205;
    //         }
    //         $room->save();
    //     }

    //     return response()->noContent();
    // }
}
