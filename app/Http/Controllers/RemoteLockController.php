<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\LowBatteryDeviceEvent;
use App\Events\NotificationEvent;
use App\Mail\CancellationMail;
use App\Mail\Email;
use App\Models\Api;
use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\Device;
use App\Models\Notification;
use App\Models\Room;
use App\Models\User;
use App\Models\UserNotification;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


/**
 * <Class Name> RemoteLockController
 *
 * <Function Name> Remote Lock Management and Processing
 * Create : xxxx.xx.xx TP Russell<br>
 * Update : 2021.04.16 TP Uddin         Disable remoteLockApi method
 *                                      Break down remoteLockApi method to submethods
 *                                      Added createRemoteLockGuestAccount method
 *                                      Added sendRemoteLockAlertEmail method
 *                                      Added getRemoteLockPinSettings method
 *        : 2021.06.17 TP Russell       Added createRemoteLockUserAccount method
 *                                      Added getAccountInfo method
 *                                      Added updateUserAccount method
 *                                      Added updateGuestAccount method
 *                                      Added deleteAccount method
 *                                      Added duplicationPinCheck method
 *                                      Added createBookAccount method
 *                                      Added sendBookCancellationEmail method
 *                                      Added getBookDetails method
 *          2021.07.12 TP Harvey        [Task 003] Added getRemoteLockBatteryLevel function
 *          2021.08.04 TP Harvey        [Task 024] Added Transfer and modify other functions to Booking Controller
 *          2021.09.14 TDN Okada        [Task 131] Added updateGuestAccount function
 *          2021.10.04 TP Ivin          [Task 207] Change the notification message
 *          2022.01.31 TP Russell       [Task 568] Added entered flag and entered time update
 *
 * <Overview> This controller contains methods that uses the RemoteLock API to
 *            control or manage the RemoteLock Devices<br>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class RemoteLockController extends Controller
{
    /**************************************************************************/
    /* Processing Hierachy                                                    */
    /**************************************************************************/
    // getApiInfo                       (1.0) Get the API information for Remote Lock in the database
    // createRemoteLockGuestAccount     (2.0) Utilizes RemoteLock API to create a guest account for a RemoteLock Device
    // sendRemoteLockAlertEmail         (3.0) Send an email with the reservation data and iBMS guest account
    // unlockRemoteLockState            (4.0) Utilizes RemoteLock API to unlock Remote Lock device
    // getRemoteLockPinSettings         (5.0) Check the settings for generating PIN Code
    // createRemoteLockUserAccount      (6.0) Utilizes RemoteLock API to create a user account for a RemoteLock Device
    // getAccountInfo                   (7.0) Utilizes RemoteLock API to get all accounts
    // updateUserAccount                (8.0) Utilizes RemoteLock API to update a user account and also update the user account's User and BookPMS table
    // updateGuestAccount               (9.0) Utilizes RemoteLock API to update a guest account and also update the guest account's User and BookPMS table
    // deleteAccount                    (10.0) Utilizes RemoteLock API to delete an account and also delete the account from User and BookPMS table
    // duplicationPinCheck              (11.0) Check whether the PIN is already taken
    // createBookAccount                (12.0) Create an account in the BookPMS table
    // sendBookCancellationEmail        (13.0) Send a cancellation of reservation email with reservation email data
    // getBookDetails                   (14.0) Get BookPMS details in the database
    // getAvailableRooms                (15.0) Get availble rooms with a remote lock device in the databas
    // getRemoteLockBatteryLevel        (16.0) Get current Battery Level of each Remote Lock Device, then push to Notification

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing Name> getApiInfo<br>
     * <Function> Get the API information for Remote Lock in the database<br>
     *            URL: http://localhost/getApiInfo<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return App\Api|string $api|$e->getMessage()
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function getApiInfo(Request $request)
    {
        try {
            return Api::where("API_NAME", $request->API_NAME)->firstOrFail();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing Name> createRemoteLockGuestAccount<br>
     * <Function> Utilizes RemoteLock API to create a guest account for a RemoteLock Device<br>
     *            URL: http://localhost/createRemoteLockGuestAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|string[] 'success'
     */
    public function createRemoteLockGuestAccount(Request $request)
    {

        $errors = [];

        //Get required Guest ID
        $guest_id = $request->GUEST_ID;

        //Get RemoteLock Device based on selected Room
        $remoteLock = Device::where('ROOM_ID', $request->ROOM_ID)
            ->where('DEVICE_TYPE', 'remote_lock')->first();

        //If no Remote Lock, return error
        if (!$remoteLock) {
            return 'no_remote_lock';
        }

        //Request new Remote Lock Token
        $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
        $accessToken = $this->getAccessToken($apiInfo->API_ID);

        $name = "$request->FIRST_NAME $request->LAST_NAME";

        $intervalTime = new \DateTime($request->CHECK_OUT_TIME);
        $intervalTime->add(new \DateInterval('PT30M'));
        $testTime = $intervalTime->format('Y-m-d H:i');

        //Create new account on Remote Lock
        $headers = [
            'Accept' => 'application/vnd.lockstate+json; version=1',
            'Content-Type' => 'application/json'
        ];
        $createAccResponse = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->post('https://api.remotelock.jp/access_persons?&', [
                'type' => 'access_guest',
                'attributes' => [
                    'starts_at' => $request->CHECK_IN_TIME,
                    'ends_at' => $testTime,
                    'name' => $name,
                    'pin' => $request->PIN,
                    'email' => $request->EMAIL
                ]
            ])->json();

        if (isset($createAccResponse['errors'])) {
            foreach ($createAccResponse['errors'] as $error) {
                $errors[] = $error['full_messages'][0];
            }
            return $errors;
        }

        //Get current id of newly created guest
        $guestId = $createAccResponse['data']['id'];

        //Grant user to specific Remote Lock Device
        $grantAccResponse = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->post("https://api.remotelock.jp/access_persons/$guestId/accesses", [
                'attributes' => [
                    'accessible_id' => $remoteLock->DATA['remote_lock_id'],
                    'accessible_type' => 'lock'
                ]
            ])->json();
        if (isset($grantAccResponse['errors'])) {
            $errors = [];
            $errors[] = 'No Device Selected';
            return $errors;
        }
        return 'success';


        //[Task024] 20210804
        ///********************************************************* */
        // $code = $request->AUTH_CODE;
        // $id = $request->API_ID;
        // $access_token = $this->getAccessToken($id, $code);

        // $errorToken = [];
        // if (isset($access_token['error'])) {
        //     $errorToken[] = $access_token['error_description'];
        //     return $errorToken;
        // }

        // $token = $access_token['access_token'];
        // $name = "$request->FIRST_NAME $request->LAST_NAME";
        // $headers = [
        //     'Accept' => 'application/vnd.lockstate+json; version=1',
        //     'Content-Type' => 'application/json'
        // ];
        // Log::info($request->CHECK_OUT_TIME);
        // $intervalTime = new \DateTime($request->CHECK_OUT_TIME);
        // $intervalTime->add(new \DateInterval ('PT30M'));
        // $testTime = $intervalTime->format('Y-m-d H:i');

        // $createAccResponse = Http::withToken($token)
        //     ->withHeaders($headers)
        //     ->post('https://api.remotelock.jp/access_persons?&', [
        //         'type' => 'access_guest',
        //         'attributes' => [
        //             'starts_at' => $request->CHECK_IN_TIME,
        //             'ends_at' => $testTime,
        //             'name' => $name,
        //             'pin' => $request->PIN,
        //             'email' => $request->EMAIL
        //         ]
        //     ])->json();

        // if (isset($createAccResponse['errors'])) {
        //     $errors = [];
        //     foreach ($createAccResponse['errors'] as $error) {
        //         $errors[] = $error['full_messages'][0];
        //     }
        //     return $errors;
        // }

        // $guestId = $createAccResponse['data']['id'];

        // $grantAccResponse = Http::withToken($token)
        //     ->withHeaders($headers)
        //     ->post("https://api.remotelock.jp/access_persons/$guestId/accesses", [
        //         'attributes' => [
        //             'accessible_id' => $request->DEVICE_ID,
        //             'accessible_type' => 'lock'
        //         ]
        //     ])->json();

        // if (isset($grantAccResponse['errors'])) {
        //     $errors = [];
        //     $errors[] = 'No Device Selected';
        //     return $errors;
        // }
        // return 'success';
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing Name> sendRemoteLockAlertEmail<br>
     * <Function> Send an email with the reservation data and iBMS guest account<br>
     *            URL: http://localhost/sendRemoteLockAlertEmail<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|string[] 'success'|$emailError
     */
    public function sendRemoteLockAlertEmail(Request $request)
    {
        $emailFrom = $request->EMAIL_FROM;
        $emailTo = $request->EMAIL;
        $checkInTime = str_replace('T', ' ', $request->CHECK_IN_TIME);
        $checkOutTime = str_replace('T', ' ', $request->CHECK_OUT_TIME);

        $emailContent = [
            'emailAdd' => $emailFrom,
            'subject' => "【ようこそCARRY CUBEへ！】宿泊のご予約を受け付けました",
            'pin' => $request->PIN,
            'checkin' => $checkInTime,
            'checkout' => $checkOutTime,
            'name' => "$request->LAST_NAME $request->FIRST_NAME",
            'room' => $request->ROOM_NAME,
            'userEmailAdd' => $emailTo,
        ];

        Mail::to($emailTo)->send(new Email($emailContent));
        if (count(Mail::failures()) > 0) {
            Log::channel('slack')->warning(Mail::failures());
            $emailError[0] = 'Email Error';
            return $emailError;
        }
        return 'success';
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> unlockRemoteLockState<br>
     * <Function> Utilizes RemoteLock API to unlock Remote Lock device<br>
     *            URL: http://localhost/unlockRemoteLockState<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return object json_decode($response, true)
     */
    public function unlockRemoteLockState(Request $request)
    {
        $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
        $accessToken = $this->getAccessToken($apiInfo->API_ID);
        $contentType = 'application/json';
        $header = [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: $contentType"
        ];
        $url = "https://api.remotelock.jp/devices/$request->REMOTE_LOCK_ID/unlock";
        $body = [];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        if ($response['data']['attributes']['state'] == 'unlocked') {
            $this->logsNotification('I003', $request->ROOM_ID, 4);
        }
        return $response;
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> getRemoteLockPinSettings<br>
     * <Function> Check the settings for generating PIN Code<br>
     *            URL: http://localhost/getRemoteLockPinSettings<br>
     *            METHOD: GET
     *
     * @param void
     * @return Illuminate\Http\Client\Response response()
     */
    public function getRemoteLockPinSettings()
    {
        $numOfPin = intval(env('REMOTELOCK_NUM_OF_PIN'));
        $pinRule = intval(env('REMOTELOCK_PIN_RULE'));
        $pin = "";

        // 0 means generate random PIN
        if ($pinRule == 0) {
            for ($i = 0; $i < $numOfPin; $i++) {
                $no = rand(0, 9);
                $pin = $pin . $no;
            }
        }

        return response([
            'numOfPin' => $numOfPin,
            'pinRule' => $pinRule,
            'pin' => $pin
        ], 200);
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> createRemoteLockUserAccount<br>
     * <Function> Utilizes RemoteLock API to create a user account grant for all RemoteLock Device<br>
     *            URL: http://localhost/createRemoteLockUserAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|string[] 'success'|$errors
     */
    public function createRemoteLockUserAccount(Request $request)
    {
        //Request new Remote Lock Token
        $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
        $accessToken = $this->getAccessToken($apiInfo->API_ID);
        $headers = [
            'Accept' => 'application/vnd.lockstate+json; version=1',
            'Content-Type' => 'application/json'
        ];

        //Get all door group to grant admin account
        $createAccResponse = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->get('https://api.remotelock.jp/groups', [])->json();

        $doorGroupId = "";
        foreach ($createAccResponse['data'] as $doorGroup) {
            $doorGroupName = $doorGroup['attributes']['name'];
            if ($doorGroupName == 'SUYAMA Rooms') {
                $doorGroupId = $doorGroup['id'];
            }
        }
        //Error catcher
        if (isset($createAccResponse['errors'])) {
            $errors = [];
            foreach ($createAccResponse['errors'] as $error) {
                $errors[] = $error['full_messages'][0];
            }
            return $errors;
        }


        //Create new User(Admin) Account
        $name = "$request->FIRST_NAME $request->LAST_NAME";
        $createAccResponse = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->post('https://api.remotelock.jp/access_persons?&', [
                'type' => 'access_user',
                'attributes' => [
                    'email' => $request->EMAIL,
                    'name' => $name,
                    'pin' => $request->PIN
                ]
            ])->json();
        //Error catcher
        if (isset($createAccResponse['errors'])) {
            $errors = [];
            foreach ($createAccResponse['errors'] as $error) {
                $errors[] = $error['full_messages'][0];
            }
            return $errors;
        }


        //Get current id of newly created guest
        $guestId = $createAccResponse['data']['id'];

        //Grant user to specific Door Group
        $grantAccResponse = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->post("https://api.remotelock.jp/access_persons/$guestId/accesses", [
                'attributes' => [
                    'accessible_id' => $doorGroupId,
                    'accessible_type' => 'door_group'
                ]
            ])->json();
        //Error catcher
        if (isset($grantAccResponse['errors'])) {
            $errors = [];
            foreach ($grantAccResponse['errors'] as $error) {
                $errors[] = $error['full_messages'][0];
            }
            return $errors;
        }

        return 'success';
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> getAccountInfo<br>
     * <Function> Utilizes RemoteLock API to get all accounts<br>
     *            URL: http://localhost/getAccountInfo<br>
     *            METHOD: GET
     *
     * @param Illuminate\Http\Request $request
     * @return object $getAccResponse
     */
    // public function getBookinAccountInfo()
    // {

    //     //Get All User from Remote Lock
    //     $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
    //     $accessToken = $this->getAccessToken($apiInfo->API_ID);
    //     $headers = [
    //         'Accept' => 'application/vnd.lockstate+json; version=1',
    //         'Content-Type' => 'application/json'
    //     ];
    //     $remoteLockAccounts = Http::withToken($accessToken)
    //                                 ->withHeaders($headers)
    //                                 ->get('https://api.remotelock.jp/access_persons');

    //     //Compare Admin Account to Remote Lock Email
    //     $adminDBList = User::where('USER_TYPE',1)->get();

    //     foreach($adminDBList as $adminDB){
    //         $adminDBEmail = $adminDB->EMAIL;
    //         $adminDB->REMOTE_LOCK_STATUS = false;

    //         foreach($remoteLockAccounts['data'] as $remoteLockAccount){
    //             $adminRLEmail = $remoteLockAccount['attributes']['email'];

    //             if($adminDBEmail === $adminRLEmail){
    //                 $adminDB->REMOTE_LOCK_INFO = $remoteLockAccount['attributes'];
    //                 $adminDB->REMOTE_LOCK_STATUS = true;
    //                 break;
    //             }
    //         }
    //     }

    //     //Compare Guest Accounts with Bookings to RemoteLock Email
    //     $guestDBList = BookPMS::whereHas('bookingsWithRoom')
    //                             ->with('bookingsWithRoom')->get();

    //     foreach($guestDBList as $guestDB){
    //         $guestDBEmail = $guestDB->EMAIL;
    //         $guestDB->REMOTE_LOCK_STATUS = false;

    //         foreach($remoteLockAccounts['data'] as $remoteLockAccount){
    //             $guestRLEmail = $remoteLockAccount['attributes']['email'];

    //             if($guestDBEmail === $guestRLEmail){
    //                 $guestDB->REMOTE_LOCK_INFO = $remoteLockAccount['attributes'];
    //                 $guestDB->REMOTE_LOCK_STATUS = true;
    //                 break;
    //             }
    //         }
    //     }

    //     $accountListInfo = ['admin_list' => $adminDBList,
    //                         'guest_list' => $guestDBList];

    //     return $accountListInfo;

    // }

    /**
     * <Layer number> (8.0)
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

        //[Task 024]
        //Modify User Table
        $password           = Hash::make($request->PIN);
        $user               = User::findOrFail($request->USER_ID);
        $user->USERNAME     = $request->EMAIL;
        $user->FIRST_NAME   = $request->FIRST_NAME;
        $user->LAST_NAME    = $request->LAST_NAME;
        $user->EMAIL        = $request->EMAIL;
        $user->PASSWORD     = $password;
        $user->save();


        //[Task 024]
        //Update Booking Table
        // $books = BookPMS::where("EMAIL" ,$request->USERNAME)->get();
        // foreach ($books as $book) {
        //     $bookId = $book['BOOK_ID'];
        //     $books = BookPMS::findOrFail($bookId);
        //     $books->EMAIL = $request->EMAIL;
        //     $books->FIRST_NAME = $request->FIRST_NAME;
        //     $books->LAST_NAME = $request->LAST_NAME;
        //     $books->PIN = $request->PIN;
        //     $books->save();
        // }

        return 'success';
    }
    /**
     * <Layer number> (9.0)
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

        try {
            //Modify User Table
            $password           = Hash::make($request->PIN);
            $user               = User::where("USERNAME", $request->USERNAME)->first();
            $user->USERNAME     = $request->EMAIL;
            $user->FIRST_NAME   = $request->FIRST_NAME;
            $user->LAST_NAME    = $request->LAST_NAME;
            $user->EMAIL        = $request->EMAIL;
            $user->PASSWORD     = $password;
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

            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> deleteAccount<br>
     * <Function> Utilizes RemoteLock API to delete an account and also delete the account from User and BookPMS table<br>
     *            URL: http://localhost/deleteAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|object 'success'|$errors
     */
    //[Task 024]
    // public function deleteAccount(Request $request)
    // {

    //     //Check if this account is connected to Remote Lock Devices
    //     if($request->REMOTE_LOCK_STATUS){

    //         $user_id = $request->USER_ID;
    //         $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
    //         $accessToken = $this->getAccessToken($apiInfo->API_ID);

    //         $headers = [
    //             'Accept' => 'application/vnd.lockstate+json; version=1',
    //             'Content-Type' => 'application/json'
    //         ];
    //         $deleteAccResponse = Http::withToken($accessToken)
    //         ->withHeaders($headers)
    //         ->delete("https://api.remotelock.jp/access_persons/$user_id");

    //         if (isset($deleteAccResponse['errors'])) {
    //             $errors = [];
    //             foreach ($deleteAccResponse['errors'] as $error) {
    //                 $errors[] = $error['full_messages'][0];
    //             }
    //             return $errors;
    //         }
    //     }
    //     User::where('USERNAME' , $request->USERNAME)->delete();
    //     return 'success';
    // }

    // /**
    //  * <Layer number> (11.0)
    //  *
    //  * <Processing name> duplicationPinCheck<br>
    //  * <Function> Check whether the PIN is already taken<br>
    //  *            URL: http://localhost/duplicationPinCheck<br>
    //  *            METHOD: POST
    //  *
    //  * @param Illuminate\Http\Request $request
    //  * @return string 'duplicate'
    //  */
    // public function duplicationPinCheck(Request $request)
    // {

    //     if($request->TYPE == 'new'){
    //         $books = [];
    //         $books = Book_Room::where('PIN',$request->PIN)
    //                             ->where('ROOM_ID',$request->ROOM_ID)
    //                             ->get();
    //         if($books){
    //             return 'duplicate';
    //         }
    //         // PIN: 2031, ROOM=ID: 1, TYPE: new

    //     }elseif ($request->TYPE == 'update') {
    //         $books = [];
    //         $books = Book_Room::where('PIN',$request->PIN)
    //         ->where('ROOM_ID','!=',$request->ROOM_ID)
    //         ->get();
    //         if(!$books->isEmpty()){
    //             return 'duplicate';
    //         }
    //         // PIN: 2031, ROOM=ID: 2, TYPE: update
    //     }
    // }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> createBookAccount<br>
     * <Function> Create an account in the BookPMS table<br>
     *            URL: http://localhost/createBookAccount<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string 'success'
     */
    // public function createBookAccount(Request $request)
    // {


    //     $checkInRoom = Room::select('ROOM_ID')
    //     ->where('ROOM_ID', $request->ROOM_ID)
    //     ->first();

    //     $user = User::where('EMAIL', $request->EMAIL)->get();
    //     $userId = $user[0]['USER_ID'];

    //     $book = new BookPMS();
    //     $book->FIRST_NAME = $request->FIRST_NAME;
    //     $book->LAST_NAME = $request->LAST_NAME;
    //     $book->EMAIL = $request->EMAIL;
    //     $book->CHECK_IN_TIME = $request->CHECK_IN_TIME;
    //     $book->CHECK_OUT_TIME = $request->CHECK_OUT_TIME;
    //     $book->CONTACT_NUMBER = $request->CONTACT_NUMBER;
    //     $book->PIN = $request->PIN;
    //     $book->ROOM_ID = $checkInRoom->ROOM_ID;
    //     $book->USER_ID = $userId;
    //     $book->save();

    //     $checkInRoom->STATUS_ID = 205;
    //     $checkInRoom->save();

    //     return 'success';
    // }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> sendBookCancellationEmail<br>
     * <Function> Send a cancellation of reservation email with reservation email data<br>
     *            URL: http://localhost/sendBookCancellationEmail<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return string|object 'success'|$emailError
     */
    public function sendBookCancellationEmail(Request $request)
    {
        $emailFrom = 'r-russell@tenseiph.com';
        $emailTo = $request->EMAIL;
        $name = $request->NAME;
        $room = $request->ROOM_NAME;
        $checkInTime = str_replace('T', ' ', $request->CHECK_IN_TIME);
        $checkOutTime = str_replace('T', ' ', $request->CHECK_OUT_TIME);

        $emailContent = [
            'emailAdd' => $emailFrom,
            'subject' => "CARRY CUBE予約取消",
            'checkin' => $checkInTime,
            'checkout' => $checkOutTime,
            'guest_name' => $name,
            'room' => $room
        ];

        Mail::to($emailTo)->send(new CancellationMail($emailContent));
        if (count(Mail::failures()) > 0) {
            Log::channel('slack')->warning(Mail::failures());
            $emailError[0] = 'Email Error';
            return $emailError;
        }
        return 'success';
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> getBookDetails<br>
     * <Function> Get BookPMS details in the database<br>
     *            URL: http://localhost/getBookDetails<br>
     *            METHOD: GET
     *
     * @param void
     * @return object $books
     */
    public function getBookDetails()
    {
        return BookPMS::with('room', 'user')->get();
    }

    /**
     * <Layer number> (15.0)
     *
     * <Processing name> getAvailableRooms<br>
     * <Function> Get availble rooms with a remote lock device in the database<br>
     *            URL: http://localhost/getAvailableRooms<br>
     *            METHOD: GET
     *
     * @param void
     * @return object $books
     */
    // public function getAvailableRooms()
    // {
    //     $response = Device::where("DEVICE_TYPE", 'remote_lock')
    //             ->whereHas('room', function($query) {
    //                 $query->where('STATUS_ID',203);
    //             })
    //             ->where("REG_FLAG", 1)
    //             ->with("room:ROOM_ID,ROOM_NAME,STATUS_ID")
    //             ->get();
    //     return $response;
    // }

    //Task[024]
    // public function getRemoteLockDevicesRoom()
    // {

    //     //Task[024]
    //     // $rooms = Room::whereHas('devices', function (Builder $query) {
    //     //     $query->where('DEVICE_TYPE', 'remote_lock')
    //     //         ->where('REG_FLAG', 1);
    //     // })
    //     // /* 201: Checked in; 202: Checked out; 203: Available; 204: Unavailable; 205: Reserved */
    //     // ->whereIn('STATUS_ID', [201, 202, 203, 205])
    //     // ->get();
    //     // return $rooms;

    //     // $rlDevices = Device::where('DEVICE_TYPE', 'remote_lock')
    //     // ->whereHas('room', function (Builder $query) {
    //     //         /* 201: Checked in; 202: Checked out; 203: Available; 204: Unavailable; 205: Reserved */
    //     //         $query->whereIn('STATUS_ID', [201, 202, 203, 205]);
    //     //     })->where('REG_FLAG', 1)
    //     //     ->with('room:ROOM_ID,ROOM_NAME,STATUS_ID')
    //     //     ->get();

    //     $rooms = Room::where('STATUS_ID',[203,205])->with('statusName')->get();
    //     return $rooms;
    // }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> getRemoteLockBatteryLevel<br>
     * <Function> Get current Battery Level of each Remote Lock Device, then push to Notification<br>
     *            URL: -
     *            METHOD: -
     *
     * @param void
     * @return object
     */

    public function getRemoteLockBatteryLevel(Request $request)
    {

        //Collect API info for Remote Lock
        $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
        $accessToken = $this->getAccessToken($apiInfo->API_ID);

        $headers = [
            'Accept' => 'application/vnd.lockstate+json; version=1',
            'Content-Type' => 'application/json'

        ];

        //Get all device information in Remote Lock API
        $getBatteryResponse = Http::withToken($accessToken)
            ->withHeaders($headers)
            ->get("https://api.remotelock.jp/devices");

        //Collect all admin account for notification
        $adminUsers = User::select('USER_ID')
            ->where('USER_TYPE', 1)
            ->get();

        //Process each Remote Lock Devices
        foreach ($getBatteryResponse['data'] as $data) {
            $battery    = $data['attributes']['power_level'];
            info($battery);
            $id         = $data['id'];
            $device     = Device::where('DATA->remote_lock_id', $id)
                ->where('REG_FLAG', 1)->first();
            if ($device) {
                //Check if the battery level is lower than the battery threshold of device
                if ($battery < 26 && $battery > 10) {
                    //Save room name if the room of device exist.
                    $roomName = $device->room->ROOM_NAME ? $device->room->ROOM_NAME : "-";

                    //Insert to Notification DB
                    $notify = new Notification();
                    $notify->OBJECT_ID = 1;
                    $notify->ROOM_ID = $device->ROOM_ID;
                    $notify->SUBJECT = '';
                    $notify->CONTENT = '';
                    $notify->ERROR_FLAG = 5;
                    $notify->NOTIFICATION_LINK = '';
                    $notify->OBJECT_NAME = "Remote Lock Low Battery at " . $roomName;
                    $notify->save();

                    //Push notification to front end
                    $room = '{"ROOM_NAME" : "' . $roomName . '"}';
                    $seen = '{"SEEN_FLAG": 0}';
                    //Push the needed Data
                    $push_notif = [
                        "NOTIFICATION" => array_merge(
                            json_decode($notify, true),
                            json_decode($seen, true),
                            json_decode($room, true)
                        )
                    ];

                    $this->logsNotification('W001', $device->ROOM_ID, 2);

                    //Notify Admin Dashboard
                    event(new NotificationEvent($push_notif));

                    //Save notification to each admin account
                    $adminQuery = [];
                    foreach ($adminUsers as $adminUser) {
                        array_push($adminQuery, [
                            'USER_ID' => $adminUser->USER_ID,
                            'NOTIFICATION_ID' => $notify->NOTIFICATION_ID,
                            'SEEN_FLAG' => 0
                        ]);
                    }
                    UserNotification::insert($adminQuery);


                    //Notification for realtime event (Optional)
                    event(new LowBatteryDeviceEvent($device));
                } elseif ($battery < 10) {
                    $this->logsNotification('E011', $device->ROOM_ID, 0);
                }
            }
        }
    }

    // + [SPRINT_04][TASK 142]
    /**
     * <Layer number> (17.0)
     *
     * <Processing name> accessDeniedNotification<br>
     * <Function> Count the Access Deny in remote lock<br>
     *            URL: http://localhost/api/accessDeniedNotificationt<br>
     *            METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     */
    public function accessDeniedNotification(Request $request)
    {

        // Get Data of the Remote Lock that is access manually
        $remoteLock = Device::where('DATA->remote_lock_id', $request->data['attributes']['publisher_id'])
            ->first();

        //Check If Succeded or Failed
        if ($request->data["attributes"]["status"] == "succeeded") {

            $book_room = Book_Room::where('PIN', $request->data['attributes']['pin'])->first();
            if ($book_room != null) {
                if ($book_room->ENTERED_FLAG == 0) {
                    $room = Room::where('ROOM_ID', $book_room->ROOM_ID)->with('checkInToday')->first();
                    if ($room != null) {
                        $RemoteLockController = app()->make('App\Http\Controllers\RemoteLockController');
                        $RemoteLockController->enteredFlagTimeUpdate($book_room);
                    }
                }
                $this->logsNotification('I007', $remoteLock->ROOM_ID, 4);
            }

            //Remotelock Will Reset to 0 if Succeded
            $remoteLockCounter = 0;
            $updateRemoteLock = Device::find($remoteLock->DEVICE_ID);


            $updateRemoteLock->where('DATA->remote_lock_id', $request->data['attributes']['publisher_id'])
                ->update([
                    'DATA->access_error_counter' => $remoteLockCounter
                ]);
            return "success";
        } else {
            //Access Denied Counter in Manual Access of Remote Lock
            $remoteLockCounter = $remoteLock->DATA['access_error_counter'];
            $remoteLockCounter = $remoteLockCounter +  1;
            $updateRemoteLock = Device::find($remoteLock->DEVICE_ID);

            $updateRemoteLock->where('DATA->remote_lock_id', $request->data['attributes']['publisher_id'])
                ->update([
                    'DATA->access_error_counter' => $remoteLockCounter
                ]);
            //Notication For Access Denied If >= 5
            if ($remoteLockCounter >= 5) {
                $this->logsNotification('E008', $remoteLock->ROOM_ID, 0);
            }
        }
    }

    /**
     * <Layer number> (18.0)
     *
     * <Processing name>checkRemoteLockConnection<br>
     * <Function> Check the connection of remote lock<br>
     *            URL:
     *            METHOD:
     */
    public function checkRemoteLockConnection()
    {
        //Collect API info for Remote Lock
        $apiInfo = Api::where('API_NAME', 'remote_lock_cc')->first();
        $accessToken = $this->getAccessToken($apiInfo->API_ID);

        $headers = [
            'Accept' => 'application/vnd.lockstate+json; version=1',
            'Content-Type' => 'application/json'

        ];

        $devices = Device::where('DEVICE_TYPE', 'remote_lock')->get();
        foreach ($devices as $device) {
            $device_id = $device->DATA['remote_lock_id'];
            $getDeviceDetail = Http::withToken($accessToken)
                ->withHeaders($headers)
                ->get("https://api.remotelock.jp/devices/$device_id");
            if ($getDeviceDetail['data']['attributes']['alive'] == false) {
                $this->logsNotification('E012', $device->ROOM_ID, 0);
            }
        }
    }

    /**
     * <Layer number> (19.0)
     *
     * <Processing name>enteredFlagTimeUpdate<br>
     * <Function> Update ENTERED_FLAG and ENTERED_TIME of BOOK_ROOM table<br>
     *            URL:
     *            METHOD:
     */
    public function enteredFlagTimeUpdate(Book_Room $book_room)
    {
        $book_room->ENTERED_FLAG = 1;
        $book_room->ENTERED_TIME = Carbon::now();
        $book_room->save();
    }
}
