<?php

namespace App\Services;

use App\Models\Book_Room;
use App\Models\Device;
use App\Models\User;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * <Class Name> StayseeAllocateRoomService
 *
 * <Function Name> Handles processing of allocated rooms of a reservation <br>
 * Create : 2021.12.15 TP Uddin <br>
 *
 */
class StayseeAllocateRoomService
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // generateUsername             (1.0) Generates a random alphanumeric string
    // determineCheckInTime         (2.0) Evaluate which check in time will be use
    // determineCheckOutTime        (3.0) Evaluate which check out time will be use
    // getNewAllocatedRooms         (4.0) Compare, filter and retrieve only the allocated rooms that are not yet synced in the DB
    // getExistingAllocatedRooms    (5.0) Compare, filter and retrieve only the allocated rooms that already exists in the DB
    // getRemovedAllocatedRooms     (6.0) Compare, filter and retrieve only the allocated rooms were removed according to the latest data
    // storeBookingRoom             (7.0) Perform complete process of storing a Book_Room entity with Remote Lock guest account
    // updateBookingRoom            (8.0) Update the Book_Room entity and the Remote Lock guest account
    // destroyBookingRoom           (9.0) Delete the Book_Room entity and disable the Remote Lock guest account

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> generateUsername
     * <Function> Generates a random alphanumeric string
     *
     * @return string
     */
    public function generateUsername(): string
    {
        // get the max length and prefix for the generated username
        $length = config('staysee.username.generated_max_length');
        $prefix = config('staysee.username.prefix');

        do {
            /**
             * generate a random integer from 0 to the maximum available
             * integer based on the maximum allowed length then cast it
             * as a string.
             */
            $number = (string) rand(0, 10 ** $length - 1);

            // add '0' left padding
            $username = $prefix . Str::padLeft($number, $length, '0');

            /**
             * if the generated username was already used,
             * generate a new one.
             */
        } while (User::all()->pluck('USERNAME')->contains($username));

        return $username;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> determineCheckInTime <br>
     * <Function> Evaluate which check in time will be use <br>
     *
     * @param  string  $checkInDT
     * @return \Carbon\CarbonImmutable
     */
    public function determineCheckInTime(string $checkInDT)
    {
        $plannedCIDT = CarbonImmutable::parse($checkInDT);

        $hotelCIHourMinuteSecond = explode(":", config('staysee.hotel_check_in_time'));

        $hotelCIDT = $plannedCIDT->setTime(...$hotelCIHourMinuteSecond);

        // return $plannedCIDT->isBefore($hotelCIDT)
        //     ? $plannedCIDT
        //     : $hotelCIDT;

        return $plannedCIDT->isBefore($hotelCIDT) && $plannedCIDT->toTimeString() != '00:00:00'
            ? $plannedCIDT
            : $hotelCIDT;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> determineCheckOutTime <br>
     * <Function> Evaluate which check out time will be use <br>
     *
     * @param  string  $checkOutDT
     * @return \Carbon\CarbonImmutable
     */
    public function determineCheckOutTime(string $checkOutDT)
    {
        $plannedCODT = CarbonImmutable::parse($checkOutDT);

        $hotelCOHourMinuteSecond = explode(":", config('staysee.hotel_check_out_time'));

        $hotelCODT = $plannedCODT->setTime(...$hotelCOHourMinuteSecond);

        // return $plannedCODT->isAfter($hotelCODT)
        //     ? $plannedCODT
        //     : $hotelCODT;

        return $plannedCODT->isAfter($hotelCODT) && $plannedCODT->toTimeString() != '23:59:00'
            ? $plannedCODT
            : $hotelCODT;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> getNewAllocatedRooms <br>
     * <Function> Compare, filter and retrieve only the allocated rooms
     *            that are not yet synced in the DB <br>
     *
     * @param  array  $reservation
     * @return \Illuminate\Support\Collection
     */
    public function getNewAllocatedRooms(array $reservation)
    {
        $updatedAllocatedRoomIDs = collect($reservation['allocate_rooms'])
            ->groupBy('room_id')
            ->keys();

        $localAllocatedRoomIDs = Book_Room::where('BOOK_ID', $reservation['id'])
            ->get()
            ->pluck('ROOM_ID');

        $newAllocatedRoomIDs = $updatedAllocatedRoomIDs->diff($localAllocatedRoomIDs);

        return $newAllocatedRoomIDs;
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> getExistingAllocatedRooms <br>
     * <Function> Compare, filter and retrieve only the allocated rooms
     *            that already exists in the DB <br>
     *
     * @param  array  $reservation
     * @return \Illuminate\Support\Collection
     */
    public function getExistingAllocatedRooms(array $reservation)
    {
        $updatedAllocatedRoomIDs = collect($reservation['allocate_rooms'])
            ->groupBy('room_id')
            ->keys();

        $localAllocatedRoomIDs = Book_Room::where('BOOK_ID', $reservation['id'])
            ->get()
            ->pluck('ROOM_ID');

        $existingAllocatedRoomIDs = $updatedAllocatedRoomIDs->intersect($localAllocatedRoomIDs);

        return $existingAllocatedRoomIDs;
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> getRemovedAllocatedRooms <br>
     * <Function> Compare, filter and retrieve only the allocated rooms
     *            were removed according to the latest data <br>
     *
     * @param  array  $reservation
     * @return \Illuminate\Support\Collection
     */
    public function getRemovedAllocatedRooms(array $reservation)
    {
        $updatedAllocatedRoomIDs = collect($reservation['allocate_rooms'])
            ->groupBy('room_id')
            ->keys();

        $localAllocatedRoomIDs = Book_Room::where('BOOK_ID', $reservation['id'])
            ->get()
            ->pluck('ROOM_ID');

        $removedAllocatedRoomIDs = $localAllocatedRoomIDs->diff($updatedAllocatedRoomIDs);

        return $removedAllocatedRoomIDs;
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> storeBookingRoom <br>
     * <Function> Perform complete process of storing a Book_Room entity
     *            with Remote Lock guest account <br>
     *
     * @param  array  $reservation
     * @param  int  $roomID
     * @return \Illuminate\Http\Response
     */
    public function storeBookingRoom(array $reservation, int $roomID)
    {
        $evaluatedCIDT = $this->determineCheckInTime($reservation['check_in_date_time']);
        $evaluatedCODT = $this->determineCheckOutTime($reservation['check_out_date_time']);

        // look for the Remote Lock device associated to the room
        $remoteLockDevice = Device::ofType('remote_lock')
            ->where('ROOM_ID', $roomID)
            ->firstOrFail();

        $remoteLockService = new RemoteLockService();

        // generate a username for the guest
        $username = $this->generateUsername();

        // generate pin
        $pin = $remoteLockService->generatePin();

        // create new iBMS guest user
        $user = new User();
        $user->USER_TYPE = 2;
        $user->FIRST_NAME = $reservation['name_kanji'];
        $user->LAST_NAME = null;
        $user->USERNAME = $username;
        $user->EMAIL = $reservation['email'];
        $user->PASSWORD = Hash::make($pin);
        $user->CONTACT_NUMBER = $reservation['tel'];
        $user->REMEMBER_TOKEN = null;
        $user->REG_FLAG = 1;
        $user->USER_LOGO = null;
        $user->ALLOW_ALERT_NOTIFICATION = [
            'sms' => false,
            'email' => false,
            'voice' => false
        ];
        $user->save();

        // create book room entity
        $bookRoom = new Book_Room();
        $bookRoom->BOOK_ID = $reservation['id'];
        $bookRoom->ROOM_ID = $roomID;
        $bookRoom->USER_ID = $user->USER_ID;
        $bookRoom->STATUS = $reservation['status'];
        $bookRoom->CHECK_IN_TIME = $evaluatedCIDT->toDateTimeLocalString();
        $bookRoom->CHECK_OUT_TIME = $evaluatedCODT->toDateTimeLocalString();

        if (Carbon::parse($reservation['check_in_date_time'])->toTimeString() != '00:00:00') {
            $bookRoom->ARRIVAL_TIME = Carbon::parse($reservation['check_in_date_time']);
        } else {
            $bookRoom->ARRIVAL_TIME = $evaluatedCIDT->toDateTimeLocalString();
        }

        if (Carbon::parse($reservation['check_out_date_time'])->toTimeString() != '23:59:00') {
            $bookRoom->DEPARTURE_TIME = Carbon::parse($reservation['check_out_date_time']);
        } else {
            $bookRoom->DEPARTURE_TIME = $evaluatedCODT->toDateTimeLocalString();
        }

        $bookRoom->ACTIVE = true;
        $bookRoom->save();

        // get the Remote Lock device UUID
        $remoteLockDeviceUUID = $remoteLockDevice->DATA['remote_lock_id'];

        // initialize the RemoteLockService class
        $remoteLockService = new RemoteLockService();

        // create new Remote Lock "Guest Access"
        $createGuestResponse = $remoteLockService->createAccessGuest(
            $username,
            $reservation['email'],
            $bookRoom->accessStartsAt->toDateTimeLocalString(),
            $bookRoom->accessEndsAt->toDateTimeLocalString(),
            $pin
        );

        if ($createGuestResponse->failed()) {
            $this->logsNotification('E007', $roomID, 0, $reservation['id']);
            throw new \Exception('failed creating new access guest', 400);
        }

        // get the contents of the API response
        $guestData = $createGuestResponse->json();

        // Get the Guest Access UUID
        $guestUUID = $guestData['data']['id'];

        // grant the "Guest Access" access to the Remote Lock device
        $grantAccessResponse = $remoteLockService->grantLockAccess(
            $guestUUID,
            $remoteLockDeviceUUID
        );

        // if the grantLockAccess fails, deactivate the account
        if ($grantAccessResponse->failed()) {

            $deactivateGuestResponse = $remoteLockService->deactivateAccessPerson($guestUUID);

            $this->logsNotification('E007', $roomID, 0, $reservation['id']);

            if ($deactivateGuestResponse->failed()) {
                throw new \Exception('failed deactivating access guest', 400);
            }

            throw new \Exception('failed creating guest account', 400);
        }

        // get the PIN and the guest UUID
        $guestPIN = $guestData['data']['attributes']['pin'];
        $guestUUID = $guestData['data']['id'];

        // update user id, pin, and remote lock guest id
        $bookRoom->PIN = $guestPIN;
        $bookRoom->REMOTE_LOCK_GUEST_UUID = $guestUUID;
        $bookRoom->save();

        // return 201 for success
        return response("Successfully created new booking room", 201);
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> updateBookingRoom <br>
     * <Function> Update the Book_Room entity and the Remote Lock guest account <br>
     *
     * @param  array  $reservation
     * @param  int  $roomID
     * @return \Illuminate\Http\Response
     */
    public function updateBookingRoom(array $reservation, int $roomID)
    {
        $evaluatedCIDT = $this->determineCheckInTime($reservation['check_in_date_time']);
        $evaluatedCODT = $this->determineCheckOutTime($reservation['check_out_date_time']);

        // get the Book_Room entity
        $bookRoom = Book_Room::where('BOOK_ID', $reservation['id'])
            ->where('ROOM_ID', $roomID)
            ->firstOrFail();

        // update the book room entity
        $bookRoom->STATUS = $reservation['status'];
        $bookRoom->CHECK_IN_TIME = $evaluatedCIDT;
        $bookRoom->CHECK_OUT_TIME = $evaluatedCODT;

        if (Carbon::parse($reservation['check_in_date_time'])->toTimeString() != '00:00:00') {
            $bookRoom->ARRIVAL_TIME = $reservation['check_in_date_time'];
        } else {
            $bookRoom->ARRIVAL_TIME = $evaluatedCIDT;
        }

        if (Carbon::parse($reservation['check_out_date_time'])->toTimeString() != '23:59:00') {
            $bookRoom->DEPARTURE_TIME = $reservation['check_out_date_time'];
        } else {
            $bookRoom->DEPARTURE_TIME = $evaluatedCODT;
        }

        $bookRoom->save();

        // initialize RemoteLockService class
        $remoteLockService = new RemoteLockService();

        // get the Guest Access UUID from the Book_Room
        $guestUUID = $bookRoom->REMOTE_LOCK_GUEST_UUID;

        // update the Guest Access associated to the Book_Room
        $updateAccessGuestResponse = $remoteLockService->updateAccessGuest(
            $guestUUID,
            $reservation['email'],
            $bookRoom->ACCESS_STARTS_AT->toDateTimeLocalString(),
            $bookRoom->ACCESS_ENDS_AT->toDateTimeLocalString()
        );

        if ($updateAccessGuestResponse->status() === 404) {
            // generate a username for the guest
            $username = $this->generateUsername();

            // generate pin
            $pin = $remoteLockService->generatePin();

            // look for the Remote Lock device associated to the room
            $remoteLockDevice = Device::ofType('remote_lock')
                ->where('ROOM_ID', $roomID)
                ->firstOrFail();

            // get the Remote Lock device UUID
            $remoteLockDeviceUUID = $remoteLockDevice->DATA['remote_lock_id'];

            // create new Remote Lock "Guest Access"
            $createGuestResponse = $remoteLockService->createAccessGuest(
                $username,
                $reservation['email'],
                $bookRoom->accessStartsAt->toDateTimeLocalString(),
                $bookRoom->accessEndsAt->toDateTimeLocalString(),
                $pin
            );

            if ($createGuestResponse->failed()) {
                $this->logsNotification('E007', $roomID, 0, $reservation['id']);
                throw new \Exception('failed creating new access guest', 400);
            }

            // get the contents of the API response
            $guestData = $createGuestResponse->json();

            // Get the Guest Access UUID
            $guestUUID = $guestData['data']['id'];

            // grant the "Guest Access" access to the Remote Lock device
            $grantAccessResponse = $remoteLockService->grantLockAccess(
                $guestUUID,
                $remoteLockDeviceUUID
            );

            // if the grantLockAccess fails, deactivate the account
            if ($grantAccessResponse->failed()) {

                $deactivateGuestResponse = $remoteLockService->deactivateAccessPerson($guestUUID);

                $this->logsNotification('E007', $roomID, 0, $reservation['id']);

                if ($deactivateGuestResponse->failed()) {
                    throw new \Exception('failed deactivating access guest', 400);
                }

                throw new \Exception('failed creating guest account', 400);
            }
        } else if ($updateAccessGuestResponse->failed()) {
            $this->logsNotification('E007', $roomID, 0, $reservation['id']);
            throw new \Exception('failed updating guest account', 400);
        }

        // return 204 for success
        return response("successfully updated the booking room", 200);
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> destroyBookingRoom <br>
     * <Function> Deletes the Book_Room entity then deactivates the RemoteLock guest account<br>
     *
     * @param  array  $reservation
     * @param  int  $roomID
     * @return \Illuminate\Http\Response
     * @throws \ModelNotFoundException
     */
    public function destroyBookingRoom(array $reservation, int $roomID)
    {
        // get the Book_Room entity
        $bookRoom = Book_Room::where('BOOK_ID', $reservation['id'])
            ->where('ROOM_ID', $roomID)
            ->firstOrFail();

        // initialize RemoteLockService class
        $remoteLockService = new RemoteLockService();

        // get the Guest Access UUID from the Book_Room
        $guestUUID = $bookRoom->REMOTE_LOCK_GUEST_UUID;

        // deactivate the Guest Access associated to the Book_Room
        $deactivateAccessPersonResponse = $remoteLockService->deactivateAccessPerson($guestUUID);

        if (
            $deactivateAccessPersonResponse->failed()
            && $deactivateAccessPersonResponse->status() !== 404
            && $deactivateAccessPersonResponse->status() !== 422    // Error when trying to change inactive Access User
        ) {
            // $this->logsNotification('E007', $roomID, 0, $reservation['id']);
            throw new \Exception('failed deactivating access person', 400);
        }

        // delete the iBMS user
        User::destroy($bookRoom->USER_ID);

        // delete booking room
        $bookRoom->delete();

        // return 204 for success
        return response("successfully destroyed a booking room", 200);
    }
}
