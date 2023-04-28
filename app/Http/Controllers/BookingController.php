<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\Room;
use App\Models\User;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * <Class Name> BookingController
 *
 * <Function Name> Booking Management and Processing
 * Create : xxxx.xx.xx TP Harvey<br>
 * Update : 2021.07.21 TP Harvey         Added getAccountInfo method
 *          2021.07.22 TP Harvey         [Task 001]Transfer and modify updateGuestAccount from RemoteLockController to BookingController
 *          2021.07.22 TP Harvey         Added Delete Guest Account
 *          2021.08.02 TP Harvey         Added Delete Admin Account
 *          2021.09.10 TP Jermaine       SPRINT_06 TASK140
 *          2021.09.14 TDN Okada         SPRINT_06 TASK131
 *          2021.09.15 TDN Okada         SPRINT_07 TASK169
 *
 * <Overview> This controller contains methods that uses the RemoteLock API to
 *            control or manage the RemoteLock Devices<br>
 * @package Controller
 * @author TP Harvey <t-harvey@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */

class BookingController extends Controller
{
    //
    /**************************************************************************/
    /* Processing Hierachy                                                    */
    /**************************************************************************/
    // scanAllCameras                   (1.0) Get all cameras recognized by the ACS
    // registerCamera                   (2.0) Set camera's REG_FLAG to 1 and add floor and room designation
    // updateCamera                     (3.0) Update a camera's details
    // deleteCamera                     (4.0) Delete a camera
    // getUnregisteredCameras           (5.0) Get unregistered cameras
    // getRegisteredCameras             (6.0) Get registered cameras
    // storeCameraLogs                  (7.0) Store camera logs
    // PostPcData                       (8.0) Save current people counter data
    // vmdAlert                         (9.0) Update counter data at motion detected
    // resetCountData                   (10.0) Reset People Counter data
    // getCurrentLoginUserDetails       (10.0) Get Current Login User ROOM_ID
    // getCurrentCheckInDetails         (11.0) Get Current CheckIn Details
    // darkFeedAlert                    (11.0) Monitor camera for dark feed
    // sendPeopleCounterDataToArchibus  (12.0) Send People Counter Data To Archibus API every 30 minutes
    // getCamerasWithBindings            (13.0) Get cameras with Bindings.



    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> getAccountInfo<br>
     * <Function> Utilizes RemoteLock API to get all accounts<br>
     *            URL: http://localhost/getAccountInfo<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return object $getAccResponse
     */
    public function getBookingAccountInfo()
    {

        //Get All User from Remote Lock
        $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
        $accessToken = $this->getAccessToken($apiInfo->API_ID);
        $headers = [
            'Accept' => 'application/vnd.lockstate+json; version=1',
            'Content-Type' => 'application/json'
        ];
        $remoteLockAccounts = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->get('https://api.remotelock.jp/access_persons');

        //Compare Admin Account to Remote Lock Email
        $adminDBList = User::where('USER_TYPE', 1)->get();

        //Loop all admin account
        foreach ($adminDBList as $adminDB) {
            $adminDBEmail = $adminDB->EMAIL;
            $adminDB->REMOTE_LOCK_STATUS = false;

            foreach ($remoteLockAccounts['data'] as $remoteLockAccount) {
                $adminRLEmail = $remoteLockAccount['attributes']['email'];
                //Include add id of user to remotelock data
                $remoteLockAccount['attributes']['id'] = $remoteLockAccount['id'];
                if ($adminDBEmail === $adminRLEmail) {
                    $adminDB->REMOTE_LOCK_INFO = $remoteLockAccount['attributes'];
                    $adminDB->REMOTE_LOCK_STATUS = true;
                    break;
                }
            }
        }

        //Compare Guest Accounts with Bookings to RemoteLock Email
        $guestDBUnfiltered = BookPMS::whereHas('bookingsWithRoom', function ($query) {
            $query->where('ACTIVE', 1);
        })->with(['bookingsWithRoom' => function ($book_room) {
            return $book_room->where('ACTIVE', 1);
        }])->get();

        $guestDBList = [];
        foreach ($guestDBUnfiltered as $guest) {
            foreach ($guest['bookingsWithRoom'] as $guestWithBooking) {
                $arr = [
                    'BOOK_ID' => $guest->BOOK_ID,
                    'CONTACT_NUMBER' => $guest->CONTACT_NUMBER,
                    'EMAIL' => $guest->EMAIL,
                    'FIRST_NAME' => $guest->FIRST_NAME,
                    'LAST_NAME' => $guest->LAST_NAME,
                    'ADDRESS' => $guest->ADDRESS,
                    'THANK_YOU_MAIL_SENT_FLAG' => $guest->THANK_YOU_MAIL_FLAG,
                    'PMS_UPDATED_AT' => $guest->PMS_UPDATED_AT,
                    'CREATED_AT' => $guest->CREATED_AT,
                    'UPDATED_AT' => $guest->UPDATED_AT,
                    'bookingsWithRoom' => $guestWithBooking
                ];
                $guestDBList[] = $arr;
            }
        }
        foreach ($guestDBList as $guestDB) {
            $guestDBEmail = $guestDB['EMAIL'];
            $guestDB['REMOTE_LOCK_STATUS'] = false;

            foreach ($remoteLockAccounts['data'] as $remoteLockAccount) {
                $guestRLEmail = $remoteLockAccount['attributes']['email'];
                //Include ID
                $remoteLockAccount['attributes']['id'] = $remoteLockAccount['id'];
                if ($guestDBEmail === $guestRLEmail) {
                    $guestDB['REMOTE_LOCK_INFO'] = $remoteLockAccount['attributes'];
                    $guestDB['REMOTE_LOCK_STATUS'] = true;
                    break;
                }
            }
        }

        $accountListInfo = [
            'admin_list' => $adminDBList,
            'guest_list' => $guestDBList
        ];

        return $accountListInfo;
    }

    /**[Task 001]
     * <Layer number> (2.0)
     *
     * <Processing name> updateGuestAccount<br>
     * <Function> Utilizes RemoteLock API to update a user account and also update the user account's User and BookPMS table<br>
     *            URL: http://localhost/updateGuestAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|object 'success'|$errors
     */
    public function updateGuestAccount(Request $request)
    {
        if ($request->REMOTE_LOCK_STATUS) {
            $guest_id = $request->GUEST_ID;
            $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
            $accessToken = $this->getAccessToken($apiInfo->API_ID);

            $headers = [
                'Accept' => 'application/vnd.lockstate+json; version=1',
                'Content-Type' => 'application/json'
            ];

            $name = "$request->FIRST_NAME $request->LAST_NAME";
            $updateAccResponse = Http::withToken($accessToken)
                ->withHeaders($headers)
                ->put("https://api.remotelock.jp/access_persons/$guest_id", [
                    'type' => 'access_guest',
                    'attributes' => [
                        'email' => $request->EMAIL,
                        'name' => $name,
                        'starts_at' => $request->CHECK_IN,
                        'ends_at' => $request->CHECK_OUT,
                        'pin' => $request->PIN,
                    ]
                ])->json();

            if (isset($updateAccResponse['errors'])) {
                $errors = [];
                foreach ($updateAccResponse['errors'] as $error) {
                    $errors[] = $error['full_messages'][0];
                }
                return $errors;
            }
        }

        try {
            //Modify User Table
            $password           = Hash::make($request->PIN);
            // - SPRINT_07 [Task169]
            // $user               = User::where("USERNAME",$request->USERNAME)->first();
            // $user->USERNAME     = $request->EMAIL;
            $user               = User::where("USER_ID", $request->USER_ID)->first();

            $firstName = $request->FIRST_NAME;
            $lastName = $request->LAST_NAME;
            $email = $request->EMAIL;

            // - SPRINT_07 [Task169]
            $user->FIRST_NAME   = $firstName;
            $user->LAST_NAME    = $lastName;
            $user->EMAIL        = $email;
            $user->PASSWORD     = $password;

            // Throw error
            throw_if(
                is_null($firstName || $lastName || $email),
                new \Exception('0', 220060000)
            );

            $user->save();
            //Modify Book Room Table Based on User Registered
            $bookRoom = Book_Room::where("USER_ID", $user->USER_ID)->first();
            $bookRoom->CHECK_IN_TIME = $request->CHECK_IN;
            $bookRoom->CHECK_OUT_TIME =  $request->CHECK_OUT;
            $bookRoom->PIN = $request->PIN;
            $bookRoom->save();

            //Modify Book Table Based on User and Book Table
            $book = BookPMS::where("BOOK_ID", $bookRoom->BOOK_ID)->first();
            $book->EMAIL = $request->EMAIL;
            $book->FIRST_NAME = $request->FIRST_NAME;
            $book->LAST_NAME = $request->LAST_NAME;
            $book->save();

            $room_name = Room::where('ROOM_ID', $bookRoom->ROOM_ID)->select('ROOM_NAME')->get();
            $user = auth()->user();
            $user_id = $user->USER_ID;
            // $this->appLog($user_id, 'Guest Account (' . $request->EMAIL . ' - ' . $room_name . ')', 'User Updated');

            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    //[Task 001] Transfer from RemoteLockController
    /**
     * <Layer number> (3.0)
     *
     * <Processing name> duplicationPinCheck<br>
     * <Function> Check whether the PIN is already taken<br>
     *            URL: http://localhost/duplicationPinCheck<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string 'duplicate'
     */
    public function duplicationPinCheck(Request $request)
    {

        if ($request->TYPE == 'new') {
            $books = [];
            $books = Book_Room::where('PIN', $request->PIN)
                ->where('ROOM_ID', $request->ROOM_ID)
                ->get();
            if (!$books->isEmpty()) {
                return 'duplicate';
            }
        } elseif ($request->TYPE == 'update') {
            $books = [];
            $books = Book_Room::where('PIN', $request->PIN)
                ->where('ROOM_ID', '!=', $request->ROOM_ID)
                ->get();
            if (!$books->isEmpty()) {
                return 'duplicate';
            }
        }
    }
    /**
     * <Layer number> (4.0)
     *
     * <Processing name> deleteGuestAccount<br>
     * <Function> Utilizes RemoteLock API to delete an account and also delete the account from User and BookPMS table<br>
     *            URL: http://localhost/deleteGuestAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|object 'success'|$errors
     */
    public function deleteGuestAccount(Request $request)
    {
        if ($request->REMOTE_LOCK_STATUS) {

            $user_id = $request->USER_ID;
            $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
            $accessToken = $this->getAccessToken($apiInfo->API_ID);

            $headers = [
                'Accept' => 'application/vnd.lockstate+json; version=1',
                'Content-Type' => 'application/json'
            ];
            $deleteAccResponse = Http::withToken($accessToken)
                ->withHeaders($headers)
                ->delete("https://api.remotelock.jp/access_persons/$user_id");

            if (isset($deleteAccResponse['errors'])) {
                $errors = [];
                foreach ($deleteAccResponse['errors'] as $error) {
                    $errors[] = $error['full_messages'][0];
                }
                return $errors;
            }
        }


        //Delete User credentials based on booked customer
        User::where('USERNAME', $request->USERNAME)->delete();
        $room_id = Book_Room::where('USER_ID', $user_id)->select('ROOM_ID')->get();
        $room_name = Room::where('ROOM_ID', $room_id)->select('ROOM_NAME')->get();
        $user = auth()->user();
        $user_id = $user->USER_ID;
        // $this->appLog($user_id, 'Guest Account (' . $request->EMAIL . ' - ' . $room_name . ')', 'User Updated');

        //Get selected Book
        $bookInfo = BookPMS::where('EMAIL', $request->USERNAME)
            ->whereHas('bookingsWithRoom', function ($query) {
                $query->where('ACTIVE', 1);
            })->first();

        //Modify Booking status and Room status connected to it
        foreach ($bookInfo->bookingsWithRoom as $key => $booking) {
            $booking->ACTIVE = 0;
            $booking->save();

            $room = $booking->room;
            $room->STATUS_ID = 203;    //Update room status to available after guest cancels the reservation
            $room->save();
        }
        return 'success';
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> updateUserAccount<br>
     * <Function> Utilizes RemoteLock API to update a user account and also update the user account's User and BookPMS table<br>
     *            URL: http://localhost/updateUserAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|object 'success'|$errors
     */
    public function updateUserAccount(Request $request)
    {
        if ($request->REMOTE_LOCK_STATUS) {

            $user_id = $request->REMOTE_LOCK_ID;
            $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
            $accessToken = $this->getAccessToken($apiInfo->API_ID);

            $headers = [
                'Accept' => 'application/vnd.lockstate+json; version=1',
                'Content-Type' => 'application/json'
            ];

            $name = "$request->FIRST_NAME $request->LAST_NAME";
            $updateAccResponse = Http::withToken($accessToken)
                ->withHeaders($headers)
                ->put("https://api.remotelock.jp/access_persons/$user_id", [
                    'type' => 'access_user',
                    'attributes' => [
                        'email' => $request->EMAIL,
                        'name' => $name,
                        'pin' => $request->PIN,
                    ]
                ])->json();

            if (isset($updateAccResponse['errors'])) {
                $errors = [];
                foreach ($updateAccResponse['errors'] as $error) {
                    $errors[] = $error['full_messages'][0];
                }
                return $errors;
            }
        }

        try {
            $firstName = $request->FIRST_NAME;
            $lastName = $request->LAST_NAME;
            $email = $request->EMAIL;

            //[Task 024]
            //Modify User Table
            $password           = Hash::make($request->PIN);
            $user               = User::findOrFail($request->USER_ID);
            $user->USERNAME     = $request->EMAIL;
            $user->FIRST_NAME   = $firstName;
            $user->LAST_NAME    = $lastName;
            $user->EMAIL        = $email;
            $user->PASSWORD     = $password;

            throw_if(
                is_null($firstName || $lastName || $email),
                new \Exception('0', 220060000)
            );

            $user->save();

            $user = auth()->user();
            $user_id = $user->USER_ID;
            // $this->appLog($user_id, 'Admin Account (' . $request->EMAIL . ')', 'User Updated');

            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> deleteAccount<br>
     * <Function> Utilizes RemoteLock API to delete an account and also delete the account from User and BookPMS table<br>
     *            URL: http://localhost/deleteAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|object 'success'|$errors
     */
    public function deleteUserAccount(Request $request)
    {

        //Check if this account is connected to Remote Lock Devices
        if ($request->REMOTE_LOCK_STATUS) {

            $user_id = $request->USER_ID;
            $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
            $accessToken = $this->getAccessToken($apiInfo->API_ID);

            $headers = [
                'Accept' => 'application/vnd.lockstate+json; version=1',
                'Content-Type' => 'application/json'
            ];
            $deleteAccResponse = Http::withToken($accessToken)
                ->withHeaders($headers)
                ->delete("https://api.remotelock.jp/access_persons/$user_id");

            if (isset($deleteAccResponse['errors'])) {
                $errors = [];
                foreach ($deleteAccResponse['errors'] as $error) {
                    $errors[] = $error['full_messages'][0];
                }
                return $errors;
            }
        }
        User::where('USERNAME', $request->USERNAME)->delete();
        $user = auth()->user();
        $user_id = $user->USER_ID;
        // $this->appLog($user_id, 'Admin Account (' . $request->USERNAME . ')', 'User Deleted');
        return 'success';
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> getAvailableRooms<br>
     * <Function> Get availble rooms with a remote lock device in the database<br>
     *            URL: http://localhost/getAvailableRooms<br>
     *            METHOD: GET
     *
     * @param void
     * @return object $books
     */
    public function getAvailableRooms()
    {
        $rooms = Room::where('STATUS_ID', [203, 205])->with('statusName')->get();
        return $rooms;
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> createBookAccount<br>
     * <Function> Create an account in the BookPMS table<br>
     *            URL: http://localhost/createBookAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string 'success'
     */
    public function createBookAccount(Request $request)
    {
        $user = User::where('EMAIL', $request->EMAIL)
            ->where('FIRST_NAME', $request->FIRST_NAME)
            ->where('LAST_NAME', $request->LAST_NAME)->first();
        if (!$user) {
            return 'user_not_found';
        }

        //Create Book Information if user
        $book = new BookPMS();
        $book->CONTACT_NUMBER = $request->CONTACT_NUMBER;
        $book->FIRST_NAME = $request->FIRST_NAME;
        $book->LAST_NAME = $request->LAST_NAME;
        $book->EMAIL = $request->EMAIL;
        $book->save();

        //Create Booking logs of user
        $booking = new Book_Room();
        $booking->BOOK_ID = $book->BOOK_ID;
        $booking->ROOM_ID = $request->ROOM_ID;
        $booking->USER_ID = $user->USER_ID;
        $booking->CHECK_IN_TIME = $request->CHECK_IN_TIME;
        $booking->CHECK_OUT_TIME = $request->CHECK_OUT_TIME;
        $booking->PIN = $request->PIN;
        $booking->ACTIVE = 1;
        $booking->save();

        //Change status if selected room
        $checkInRoom = Room::select('ROOM_ID')
            ->where('ROOM_ID', $request->ROOM_ID)
            ->first();
        $checkInRoom->STATUS_ID = 205;
        $checkInRoom->save();

        $room_name = $checkInRoom->ROOM_NAME;
        $user = auth()->user();
        $user_id = $user->USER_ID;
        // $this->appLog($user_id, 'Guest Account (' . $request->USERNAME . ' - ' . $room_name . ')', 'User Updated');
        return 'success';
        //[Task024] 20210804
        // $checkInRoom = Room::select('ROOM_ID')
        // ->where('ROOM_ID', $request->ROOM_ID)
        // ->first();

        // $user = User::where('EMAIL', $request->EMAIL)->get();
        // $userId = $user[0]['USER_ID'];

        // $book = new BookPMS();
        // $book->FIRST_NAME = $request->FIRST_NAME;
        // $book->LAST_NAME = $request->LAST_NAME;
        // $book->EMAIL = $request->EMAIL;
        // $book->CHECK_IN_TIME = $request->CHECK_IN_TIME;
        // $book->CHECK_OUT_TIME = $request->CHECK_OUT_TIME;
        // $book->CONTACT_NUMBER = $request->CONTACT_NUMBER;
        // $book->PIN = $request->PIN;
        // $book->ROOM_ID = $checkInRoom->ROOM_ID;
        // $book->USER_ID = $userId;
        // $book->save();

        // $checkInRoom->STATUS_ID = 205;
        // $checkInRoom->save();

        // return 'success';
    }

    // + SPRINT_06 [Task131]
    /**
     * <Layer number> (9.0)
     *
     * <Processing name> getUpdateInfo<br>
     * <Function> Utilizes RemoteLock API to get all accounts<br>
     *            URL: http://localhost/getUpdateInfo<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return object $getAccResponse
     */
    public function getUpdateInfo($user_id)
    {
        $updateData = Book_Room::with('book')->where('USER_ID', $user_id)->first();

        return view('remotelock.updateAccount.index')->with('updateData', $updateData)
            ->with('modules', app('App\Http\Controllers\DashboardController')->getModules());
    }
    // + SPRINT_06 [Task131]

    // + SPRINT_06 [TASK140]
    /**
     * <Layer number> (10.0)
     *
     * <Processing name> getCurrentLoginUserDetails>
     * <Function> Get Current Login User ROOM_ID
     *            URL: http://localhost/getCurrentLoginUserDetails<br>
     *            METHOD: GET
     *
     * @return int
     */
    public function getCurrentLoginUserDetails()
    {
        $roomId = Book_Room::firstWhere('USER_ID', auth()->id())->ROOM_ID ?? null;

        return $roomId;
    }


    // + SPRINT_06 [TASK140]

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> getCurrentCheckInDetails>
     * <Function> Get Current Checkin Details
     *            URL: http://localhost/getCurrentLoginUserDetails<br>
     *            METHOD: GET
     *
     * @return int
     */
    public function getCurrentCheckInDetails()
    {
        return Book_Room::with('room', 'user')->firstWhere('USER_ID', auth()->id());
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> getCurrentCheckInDetails>
     * <Function> Get Current Checkin Details
     *            URL: http://localhost/getCurrentLoginUserDetails<br>
     *            METHOD: GET
     *
     * @return int
     */
    public function getBookRoomDetails()
    {
        return Book_Room::with('room', 'user')->where('USER_ID', auth()->id())->get();
    }
}
