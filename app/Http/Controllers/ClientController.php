<?php

/**
 * <System Name> iBMS HoteRes
 */

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Events\RoomMessageEvent;
use App\Models\Book_Room;
use App\Models\Device;
use App\Models\Gateway;
use App\Models\Notification;
use App\Models\ProcessedData;
use App\Models\Room;
use App\Models\User;
use App\Models\UserNotification;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * <Class Name> ClientController
 *
 * <Function Name> Client/Guest Dashboard management and processing<br>
 * Create : 2020.12.xx TP Uddin<br>
 * Update : 2021.02.26 TP Uddin Update documentation<br>
 * Update : 2021.06.06 TP Ivin Create Check in and Check out Function for Guest<br>
 * Update : 2021.06.18 TP Ivin Create Notification for Late Check In<br>
 * Update : 2021.06.28 TP Chris Check Check-in Date<br>
 * Update : 2021.07.15 TP Shannie Modify functions according to new ERD
 * Update : 2021.07.16 TP Harvey update getUserNotifications for room name
 * Update : 2021.07.21 TP Uddin Import Nature Remo PG from 41.170
 * Update : 2021.07.21 TP Uddin Added getRoomAppliances method
 * Update : 2021.07.30 TP Uddin Update updateRoomMessage method
 * Update : 2021.08.18 TP Jermaine SPRINT_03 Task023
 * Update : 2021.08.26  TP Ivin  create Notification for late check out Guest <br>
 * Update : 2021.09.14 TDN Renji SPRINT07 Task141 refactoring related to login
 *
 * <Overview> This controller is responsible for managing the process in the guest dashboard screen
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (1.0)
    // getRoom                          (2.0) Get all rooms with their book properties
    // getRoomGateway                   (3.0) Get the gateway in the room
    // getRoomDevices                   (4.0) Get devices in the room
    // getRoomAppliances                (5.0) Get appliances connected to the IR remote (Nature device)
    // sendInstruction                  (6.0) Send instruction code to OPS to command devices
    // getDeviceData                    (7.0) Get the DATA of the devices
    // updateRoomMessage                (8.0) Change the message in the room
    // getUserNotifications             (9.0) Get the latest notifications of the user
    // getLatestData                    (10.0) Get the latest entry data of the device in the Processed Data Table
    // createCheckOutNotification       (12.0) Insert Notification In admin Notification when Guest has been Check out
    // updateNotification               (13.0) Update the Latest Notification
    // checkCheckinDate                 (15.0) Checking date for check-in
    // lateCheckoutNotifications        (16.0) [Task125][Sprint04] Notify the admin if the Guest didn't check out on time
    // isValidReservation               (17.0) Check if the resarvation is valid
    // isReservedStatus                 (18.0) Check if the room status is Reserved
    // isCheckinStatus                  (19.0) Check if the room status is Checked in
    // isCheckoutStatus                 (20.0) Check if the room status is Checked out
    // updateStatusToCheckin            (21.0) Update the room status to Checked in
    // createCheckinNotification        (22.0) create Checked in notification for admin
    // createRecheckinNotification      (23.0) create Rechecked in notification for admin

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Constructor<br>
     * <Function> Create constructor for Client<br>
     *            URL: <br>
     *            METHOD:
     *
     * @param
     * @return void
     */
    public function __construct(User $user, Book_Room $bookRoom)
    {
        $this->user = $user;
        $this->bookRoom = $bookRoom;
    }

    /**
     * <Layer Number> (1.0)
     *
     * <Processing Name> Get Room<br>
     * <Function> Get all rooms with their book properties<br>
     *            URL: http://localhost/client/room/:id
     *            METHOD: GET
     *
     * @param Request $request
     * @param integer $id ROOM_ID
     * @return object|string $room|$th->getMessage()
     */
    public function getRoom(Request $request, int $id)
    {
        try {
            // = [TASK022]
            $room = Room::with('bookingsWithBook', 'bookingToday')->withCount('futureBookings')->find($id);

            // Throw Error
            if (!$room) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("0:Room Not Found", 210030000);
            }
            return $room;
            // = [TASK022]
        } catch (\Throwable $th) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $th);
        }
    }

    /**
     * <Layer Number> (2.0)
     *
     * <Processing Name> Get Room Gateway<br>
     * <Function> Get the gateway in the room<br>
     *            URL: http://localhost/client/gateway
     *            METHOD: GET
     *
     * @param Request $request
     * @return object|string $gateway|$th->getMessage()
     */
    public function getRoomGateway(Request $request)
    {
        try {
            $gateway = Gateway::where('ROOM_ID', $request->ROOM_ID)
                ->where('REG_FLAG', 1)
                ->first();
            return $gateway;
        } catch (\Throwable $th) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $th);
        }
    }

    /**
     * <Layer Number> (3.0)
     *
     * <Processing Name> Get Room Devices<br>
     * <Function> Get devices in the room<br>
     *            URL: http://localhost/client/devices/:id
     *            METHOD: GET
     *
     * @param Request $request
     * @param integer $id
     * @return object|string $devices|$th->getMessage()
     */
    public function getRoomDevices(Request $request, int $id)
    {
        try {
            $device = Device::where('ROOM_ID', $id)
                ->where('REG_FLAG', 1)
                ->latest()
                ->get();

            // Throw Error
            if (!$device) {
                throw new \Exception("0:Device Not Found", 210050001);
            }

            return $device;
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer Number> (4.0)
     *
     * <Processing Name> Get Room Appliances<br>
     * <Function> Get appliances connected to the IR remote (Nature device)<br>
     *            URL: http://localhost/room/:id/appliances
     *            METHOD: GET
     *
     * @param int $id
     * @return object[]|string
     * @throws \Exception $e
     */
    public function getRoomAppliances(int $id)
    {
        try {
            return Room::findOrFail($id)->natureRemoAppliances()->get();
        } catch (\Throwable $th) {
            return response($th->getMessage(), 400);
        }
        // try {
        //     return NatureRemoAppliance::with('natureRemoSignals')
        //         ->whereHas('devices', function ($query) use ($id) {
        //             $query->where('DEVICE_TYPE', 'nature_remo')
        //                 ->where('ROOM_ID', $id)
        //                 ->where('REG_FLAG', 1);
        //         })
        //         ->get();
        // } catch (\Exception $e) {
        //     return response($e->getMessage(), 400);
        // }
    }

    // [TASK-024]
    /**
     * <Layer Number> (4.0)
     *
     * <Processing Name> Send Instruction<br>
     * <Function> Send instruction code to OPS to command devices<br>
     *            URL: http://localhost/client/instruct
     *            METHOD: POST
     *
     * @param Request $request
     * @return object|string $sRet|$e->getMessage()
     */
    // public function sendInstruction(Request $request)
    // {
    //     try {
    //         $gateway = Gateway::where('GATEWAY_ID', $request->GATEWAY_ID)
    //             ->select('GATEWAY_IP')
    //             ->firstOrFail();

    //         $device = Device::where('DEVICE_ID', $request->DEVICE_ID)
    //             ->select('DEVICE_ID','DEVICE_TYPE', 'DEVICE_SERIAL_NO',
    //                 'DEVICE_NAME', 'ROOM_ID', 'FLOOR_ID','DEVICE_CATEGORY')
    //             ->firstOrFail();

    //         $room = Room::where('ROOM_ID', $device->ROOM_ID)
    //             ->select('ROOM_NAME')
    //             ->firstOrFail();

    //         $floor = Floor::where('FLOOR_ID', $device->FLOOR_ID)
    //             ->select('FLOOR_NAME')
    //             ->firstOrFail();

    //     } catch (\Throwable $e) {
    //         // Insert System Logs
    //         $type = '3';
    //         $instructionType = 'System Error';
    //         $uri = 'Send Instruction';
    //         $content = $uri ." : ". $e->getMessage();
    //         $username = auth()->user()->USER_ID;
    //         $ip = $request->GATEWAY_IP ? $request->GATEWAY_IP : '';
    //         $this->storeLogs($type, $instructionType, $content, $ip, $username);
    //         return $e->getMessage();
    //     }

    //     // MC network details
    //     $remote_ip = $gateway->GATEWAY_IP;
    //     $remote_port = env('PORT_GATEWAY');

    //     // Device Data
    //     $deviceId = $device->DEVICE_SERIAL_NO;
    //     $deviceType = $device->DEVICE_TYPE;
    //     $addValue = $request->addlValue ? $request->addlValue : "";
    //     $deviceName = $device->DEVICE_NAME;
    //     $deviceInstruction = $this->convertInstruction(
    //         $deviceType,
    //         $request->COMMAND,
    //         $request->VALUE,
    //         $addValue
    //     );
    //     $data = json_encode([
    //         'mode' => 'sendInstruction',
    //         'device_id' => $deviceId,
    //         'command' => $deviceInstruction
    //     ]);
    //     $message = $this->encryptMessage($data);
    //     $sRet = $this->sendToSocket($remote_ip, $remote_port, $message);

    //     // Event
    //     $event = $request->event;
    //     // Automated or Manual
    //     $instructionType = $request->TYPE;
    //     // Log content
    //     $logMessage = "Device: $deviceName Room: $room->ROOM_NAME Floor: $floor->FLOOR_NAME Event: $event";
    //     // Fetch user data from session
    //     $username = auth()->user();
    //     // Fetch IP if has any
    //     $ip = $request->ip() ? $request->ip() : '-';
    //     // Store Type, Instruction Type, Content, IP and Username
    //     if ($request->TYPE == 'Manual') {
    //         $type = 4;
    //         $this->storeLogs($type, $instructionType, $logMessage, $ip, $username->USERNAME);
    //     }
    //     return $sRet;
    // }

    // [TASK-024]

    // /**
    //  * <Layer Number> (5.0)
    //  *
    //  * <Processing Name> Get Device Data<br>
    //  * <Function> Get the DATA of the device<br>
    //  *            URL: http://localhost/client/device/:id
    //  *            METHOD: GET
    //  *
    //  * @param Request $request
    //  * @param integer $id
    //  * @return object[]|object|null $deviceData
    //  */
    // public function getDeviceData(Request $request, int $id)
    // {
    //     $deviceData = Device::find($id)->DATA;
    //     return $deviceData;
    // }

    /**
     * <Layer Number> (6.0)
     *
     * <Processing Name> Update Room Message<br>
     * <Function> Change the message in the room<br>
     *            URL: http://localhost/client/room/message/update
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'|$th->getMessage()
     */
    public function updateRoomMessage(Request $request)
    {
        /* Updated by Uddin 2021.07.30 */

        // $room = Room::findOrFail($request->ROOM_ID);
        // $room->STATUS_ID = $request->STATUS_ID;
        // $room->save();
        // $bookId = $request->BOOK_ROOM_ID;
        // $bookRoom = Book_Room::findOrFail($bookId);
        // $bookRoom->MESSAGE_ID = $request->MESSAGE_ID;


        $bookRoom = Book_Room::find($request->BOOK_ROOM_ID);

        try {
            // Throw Error
            if (!$bookRoom) {
                throw new ModelNotFoundException("0:Room ID Doesn't Exist", 210030001);
            }
        } catch (\Throwable $th) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $th);
        }
        $bookRoom->MESSAGE_ID = $request->MESSAGE_ID;
        $bookRoom->save();
        event(new RoomMessageEvent($bookRoom));
        return response('success');
    }

    /**
     * <Layer Number> (7.0)
     *
     * <Processing Name> Get User Notifications<br>
     * <Function> Get the latest notifications of the user<br>
     *            URL: http://localhost/client/notifications/:id
     *            METHOD: GET
     *
     * @param Request $request
     * @param integer $id
     * @return object[]|string $retArr|$th->getMessage()
     */
    public function getUserNotifications(Request $request)
    {
        $id = auth()->user()->USER_ID;
        $date = date('Y-m-d H:i:s', strtotime('-1 month'));

        $notifs = Notification::whereHas('userNotification', function ($query) use ($id) {
            $query->where('USER_ID', $id);
        })
            ->with(['userNotification' => function ($userNotif) use ($id) {
                return $userNotif->where('USER_ID', $id);
            }])
            ->with('room')
            ->where('ERROR_FLAG', 5)
            ->where('CREATED_AT', '>', $date)
            ->latest()
            ->get();


        try {
            // Throw Error
            if (!$notifs) {
                throw new \Exception("0:No New Notification", 400030001);
            }
        } catch (\Throwable $th) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $th);
        }

        $retArr = [];
        foreach ($notifs as $notif) {
            $date = date("Y/m/d H:i", strtotime($notif->CREATED_AT));

            $roomName = isset($notif->room->ROOM_NAME) ? $notif->room->ROOM_NAME : "-";
            $tempArr = [
                'NOTIFICATION_ID' => $notif->NOTIFICATION_ID,
                'OBJECT_NAME' => $notif->OBJECT_NAME,
                'ROOM_ID' => $notif->ROOM_ID,
                'SUBJECT' => $notif->SUBJECT,
                'CREATED_AT' => $date,
                'ROOM_NAME' => $roomName,
                'SEEN_FLAG' => $notif->userNotification[0]->SEEN_FLAG,
                'USER_ID' => $notif->userNotification[0]->USER_ID,
            ];
            $retArr[] = $tempArr;
        }
        return $retArr;
    }

    /**
     * <Layer Number> (8.0)
     *
     * <Processing Name> Get Latest Data<br>
     * <Function> Get the latest entry data of the device in the Processed Data table<br>
     *            URL: http://localhost/client/data/latest
     *            METHOD: POST
     *
     * @param Request $request
     * @return object[] $lastData
     */
    public function getLatestData(Request $request)
    {
        $lastData = ProcessedData::where('DEVICE_ID', $request->DEVICE_ID)
            ->latest()
            ->first();
        return $lastData;
    }

    /**
     * <Layer Number> (10.0)
     *
     * <Processing Name> create Check Out Notification<br>
     * <Function> Insert Notification In admin Notification when Guest has
     * been Check out<br>
     *
     * @param object $user
     */
    public function createCheckOutNotification(object $user)
    {
        // user ID of the guest
        $id = $user->USER_ID;
        $adminUsers = User::select('USER_ID')
            ->where('USER_TYPE', 1)
            ->get();

        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        $book = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->book;
        $checkOutRoom = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;

        // $checkOutRoom is empty, return
        if (!$checkOutRoom) {
            return;
        }

        // not guest terminate
        if ($user->USER_TYPE == 1) {
            return;
        }
        if ($user->USER_TYPE == 2) {
            // insert the Check Out notification To the DB
            $notify =  new Notification();
            $notify->OBJECT_ID = 1;
            $notify->OBJECT_NAME = "check out";
            //Dummy Data for Restaurant Show 2021/06/09
            $notify->ROOM_ID = $checkOutRoom->ROOM_ID;
            $notify->SUBJECT = $book->FIRST_NAME . ' ' . $book->LAST_NAME;
            $notify->CONTENT = " ";
            $notify->ERROR_FLAG =  5;
            $notify->NOTIFICATION_LINK = " ";
            $notify->save();
            // Update the Status of the Room
            // Temporary comment out for development by Task429_Renji
            // $checkOutRoom->STATUS_ID = 202;
            // $checkOutRoom->save();
        }
        //Dummy Data for Restaurant Show 2021/06/09
        $roomNotif = $checkOutRoom->ROOM_NAME;
        $room = '{"ROOM_NAME" : "' . $roomNotif . '"}';
        $seen = '{"SEEN_FLAG": 0}';
        //Push the needed Data
        $push_notif = [
            "NOTIFICATION" => array_merge(
                json_decode($notify, true),
                json_decode($room, true),
                json_decode($seen, true),
                json_decode($room, true)
            )
        ];

        event(new NotificationEvent($push_notif));

        // Show the Notification to Admin Account
        $adminQuery = [];
        foreach ($adminUsers as $adminUser) {
            array_push($adminQuery, [
                'USER_ID' => $adminUser->USER_ID,
                'NOTIFICATION_ID' => $notify->NOTIFICATION_ID,
                'SEEN_FLAG' => 0
            ]);
        }
        UserNotification::insert($adminQuery);

        return true;
    }
    /**
     * <Layer number> (11.0)
     *
     * <Processing name> Update notification<br>
     * <Function> Mark notification as seen<br>
     *            URL: http://localhost/client/updateNotification<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function updateNotification(Request $request)
    {
        $module = 'Notification Controller';
        $sender = 'MC';
        $id = auth()->user()->USER_ID;
        try {
            $updateNotif = UserNotification::where(
                'NOTIFICATION_ID',
                $request->NOTIFICATION_ID
            )
                ->where('USER_ID', $id)
                ->first();
            $updateNotif->SEEN_FLAG = 1;

            $updateNotif->save();
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer Number> (13.0)
     *
     * <Processing Name> Check check-in Date<br>
     * <Function> Check the current date and check-in date for login of the guest<br>
     *
     * @return string 'success'
     * @return string 'failed'
     */
    public function checkCheckinDate($user)
    {
        // This function is for Guest only.
        // If the user is not a Guest terminate.
        if ($user->USER_TYPE == 1) {
            return;
        }
        // User ID of the Guest
        $userid = $user->USER_ID;
        // Get the information of the guest using $userid
        $checkInRoom = Room::whereHas('bookingsWithBook', function ($query) use ($userid) {
            $query->where('USER_ID', $userid);
        })
            ->with(['bookingsWithBook' => function ($bookingData) use ($userid) {
                return $bookingData->where('USER_ID', $userid);
            }])
            ->first();

        // + SPRINT_03 [TASK023]
        //If $checckInRoom is false return no booking
        if (!$checkInRoom) {
            return 'no booking';
        }
        // + SPRINT_03 [TASK023]
        // Initialize the check-in data
        $checkinDateTime = strtotime($checkInRoom->bookingsWithBook[0]->CHECK_IN_TIME);
        // Convert the check-in data into 'Y-m-d' format
        $checkinDateTimeConvert = date('Y-m-d', $checkinDateTime);
        // Compare the check-in date to current date
        // If converted check-in date and current date is equal, return 'success'
        // Or return 'failed'
        if ($checkinDateTimeConvert == date('Y-m-d')) {
            return 'success';
        } else {
            return 'failed';
        }
    }

    /**TASK021-4
     * <Layer Number> (7.0)
     *
     * <Processing Name> Update Room Status<br>
     * <Function> Change the status of the room to checkout<br>
     *            URL: http://localhost/client/room/message/update
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'|$th->getMessage()
     */
    public function updateRoomStatusToCheckout(int $room_id, int $status_id)
    {
        $room = Room::find($room_id);
        try {
            // Throw Error
            if (!$room) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("0:Room ID Doesn't Exist", 210030001);
            }
        } catch (\Throwable $th) {
            info($th);
        }

        $room->STATUS_ID = $status_id;

        $room->update();
        event(new RoomMessageEvent($room));
        return 'success';
    }
    // +  Sprint04 Task125

    /**
     * <Layer Number> (16.0)
     *
     * <Processing Name> create Notifications for late checkout guest<br>
     * <Function> Insert notification to admin when the guest didn't
     *            check out on time<br>
     *
     *
     */
    public function lateCheckoutNotifications()
    {
        $guestUsers = User::select('USER_ID')
            ->where('USER_TYPE', 2)
            ->get();
        foreach ($guestUsers as $guestUser) {
            $lateCheckOutUser = $guestUser->USER_ID;
            $bookRoom = Room::whereHas('bookingsWithBook', function ($query) use ($lateCheckOutUser) {
                $query->where('USER_ID', $lateCheckOutUser);
            })
                ->with(['bookingsWithBook' => function ($bookData) use ($lateCheckOutUser) {
                    return $bookData->where('USER_ID', $lateCheckOutUser);
                }])
                ->first();
            $adminUsers = User::select('USER_ID')
                ->where('USER_TYPE', 1)
                ->get();
            if ($bookRoom == null) {
                continue;
            }
            if ($bookRoom->STATUS_ID == 201) {
                if ($bookRoom->bookingsWithBook[0]->CHECK_OUT_TIME < date('Y-m-d H:i:s') ? true : false) {
                    // insert the Check In notification To the DB
                    $notify =  new Notification();
                    $notify->OBJECT_ID = 1;
                    $notify->OBJECT_NAME = "late check out";
                    //Dummy Data for Restaurant Show 2021/06/09
                    $notify->ROOM_ID = $bookRoom->ROOM_ID;
                    $notify->SUBJECT = $bookRoom->book->FIRST_NAME . ' ' . $bookRoom->book->LAST_NAME;
                    $notify->CONTENT = " ";
                    $notify->ERROR_FLAG =  5;
                    $notify->NOTIFICATION_LINK = " ";
                    $notify->save();
                }
            } else {
                continue;
            }
            //Dummy Data for Restaurant Show 2021/06/09
            $roomNotif = $bookRoom->ROOM_NAME;
            $room = '{"ROOM_NAME" : "' . $roomNotif . '"}';
            $seen = '{"SEEN_FLAG": 0}';
            //Push the needed Data
            $push_notif = [
                "NOTIFICATION" => array_merge(
                    json_decode($notify, true),
                    json_decode($bookRoom, true),
                    json_decode($seen, true),
                    json_decode($room, true)
                )
            ];

            event(new NotificationEvent($push_notif));

            // Show the Notification to Admin Account
            $adminQuery = [];
            foreach ($adminUsers as $adminUser) {
                array_push($adminQuery, [
                    'USER_ID' => $adminUser->USER_ID,
                    'NOTIFICATION_ID' => $notify->NOTIFICATION_ID,
                    'SEEN_FLAG' => 0
                ]);
            }
            UserNotification::insert($adminQuery);
        }
    }
    // +  Sprint04 Task125

    // + Sprint07 Task141
    /**
     * <Layer Number> (17.0)
     *
     * <Processing Name> Check if the reservation is valid<br>
     * <Function> Check if the check in time is matched with the reservation checkin and checkout time<br>
     *
     * @param object
     * @return bool
     */
    public function isValidReservation(object $reservation)
    {
        if (is_null($reservation)) return false;

        $CIDT = Carbon::parse($reservation->ACCESS_STARTS_AT);
        $CODT = Carbon::parse($reservation->ACCESS_ENDS_AT);

        return now()->isBetween($CIDT, $CODT);
    }

    /**
     * <Layer Number> (18.0)
     *
     * <Processing Name> Check if the room status is Reserved<br>
     * <Function> Check if the room status is Reserved<br>
     *
     * @param object
     * @return bool
     */
    public function isReservedStatus(object $user)
    {
        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        $room = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;

        // if the room status is reserved
        if (!empty($room) && $room->STATUS_ID == 205) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * <Layer Number> (19.0)
     *
     * <Processing Name> Check if the room status is Checked in<br>
     * <Function> Check if the room status is Checked in<br>
     *
     * @param object
     * @return bool
     */
    public function isCheckinStatus(object $user)
    {
        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        $room = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;

        // if the room status is checkin
        if (!empty($room) && $room->STATUS_ID == 201) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * <Layer Number> (20.0)
     *
     * <Processing Name> Check if the room status is Checked out<br>
     * <Function> Check if the room status is Checked out<br>
     *
     * @param object
     * @return bool
     */
    public function isCheckoutStatus(object $reservation)
    {
        Log::info('START @ ' . __FILE__ . '_isCheckoutStatus(object $reservation)');
        Log::info("\t" . 'INPUT @ ');
        log::info($reservation);

        // when the user is admin, $room will be null
        // if the room status is check-out
        if (($reservation->STATUS == 7)) {
            Log::info('END @ ' . __FILE__ . '_isCheckoutStatus(object $reservation)');
            return true;
        } else {
            Log::info('END @ ' . __FILE__ . '_isCheckoutStatus(object $reservation)');
            return false;
        }
    }

    /**
     * <Layer Number> (21.0)
     *
     * <Processing Name> Update room status to Checked in<br>
     * <Function> Update room status to 201(Checked in)<br>
     *
     * @param object
     * @return bool
     */
    public function updateStatusToCheckin(object $user)
    {
        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        $room = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;

        if (is_null($room)) {
            return false;
        }

        // Update the Status of the Room to checkin
        $room->STATUS_ID = 201;
        $room->save();

        return true;
    }

    /**
     * <Layer Number> (22.0)
     *
     * <Processing Name> Create checked in notification for admin<br>
     * <Function> Create checked in notification for admin<br>
     *
     * @param object
     * @return bool
     */
    public function createCheckinNotification(object $user)
    {
        $adminUsers = User::select('USER_ID')
            ->where('USER_TYPE', 1)
            ->get();

        // get bookRoom/book/room data related to the guest
        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        $book = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->book;
        $checkinRoom = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;

        if (is_null($checkinRoom)) {
            return false;
        }

        // insert the Check In notification To the DB
        $notify =  new Notification();
        $notify->OBJECT_ID = 1;
        $notify->OBJECT_NAME = "check in";
        $notify->ROOM_ID = $checkinRoom->ROOM_ID;
        $notify->SUBJECT = $book->FIRST_NAME . ' ' . $book->LAST_NAME;;
        $notify->CONTENT = " ";
        $notify->NOTIFICATION_LINK = " ";
        $notify->ERROR_FLAG =  5;
        $notify->save();

        // Room Name
        $roomNotif = $checkinRoom->ROOM_NAME;
        $room = '{"ROOM_NAME" : "' . $roomNotif . '"}';
        $seen = '{"SEEN_FLAG": 0}';

        //Push the needed Data
        $push_notif = [
            "NOTIFICATION" => array_merge(
                json_decode($notify, true),
                json_decode($room, true),
                json_decode($seen, true),
                json_decode($room, true)
            )
        ];
        event(new NotificationEvent($push_notif));

        // Show the Notification to Admin Account
        $adminQuery = [];
        foreach ($adminUsers as $adminUser) {
            array_push($adminQuery, [
                'USER_ID' => $adminUser->USER_ID,
                'NOTIFICATION_ID' => $notify->NOTIFICATION_ID,
                'SEEN_FLAG' => 0
            ]);
        }
        UserNotification::insert($adminQuery);

        return true;
    }

    /**
     * <Layer Number> (23.0)
     *
     * <Processing Name> Create rechecked in notification for admin<br>
     * <Function> Create rechecked in notification for admin<br>
     *
     * @param object
     * @return bool
     */
    public function createRecheckinNotification(object $user)
    {
        $adminUsers = User::select('USER_ID')
            ->where('USER_TYPE', 1)
            ->get();

        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        $book = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->book;
        $checkinRoom = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;

        if (is_null($checkinRoom)) {
            return false;
        }

        // insert the Check In notification To the DB
        $notify =  new Notification();
        $notify->OBJECT_ID = 1;
        $notify->OBJECT_NAME = "recheck in";
        $notify->ROOM_ID = $checkinRoom->ROOM_ID;
        $notify->SUBJECT = $book->FIRST_NAME . ' ' . $book->LAST_NAME;
        $notify->CONTENT = " ";
        $notify->NOTIFICATION_LINK = " ";
        $notify->ERROR_FLAG =  5;
        $notify->save();

        // Room Name
        $roomNotif = $checkinRoom->ROOM_NAME;
        $room = '{"ROOM_NAME" : "' . $roomNotif . '"}';
        $seen = '{"SEEN_FLAG": 0}';

        //Push the needed Data
        $push_notif = [
            "NOTIFICATION" => array_merge(
                json_decode($notify, true),
                json_decode($room, true),
                json_decode($seen, true),
                json_decode($room, true)
            )
        ];
        event(new NotificationEvent($push_notif));

        // Show the Notification to Admin Account
        $adminQuery = [];
        foreach ($adminUsers as $adminUser) {
            array_push($adminQuery, [
                'USER_ID' => $adminUser->USER_ID,
                'NOTIFICATION_ID' => $notify->NOTIFICATION_ID,
                'SEEN_FLAG' => 0
            ]);
        }
        UserNotification::insert($adminQuery);

        return true;
    }


    public function errorCheckOutNotification(object $user, object $book)
    {



        $bookRoom = $this->user->find($user->USER_ID)->bookRoom;
        // get the first name and last name
        $book = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->book;
        // get the room associated to the booking
        $room = $this->bookRoom->find($bookRoom->BOOK_ROOM_ID)->room;
        // $room = Room::find($booking->ROOM_ID);



        // create error notification
        $notification = new Notification();
        $notification->OBJECT_ID = 1;
        $notification->OBJECT_NAME = 'failed checkout';
        $notification->ROOM_ID = $room->ROOM_ID;
        $notification->SUBJECT = $book->FIRST_NAME . ' ' . $book->LAST_NAME;
        $notification->CONTENT = '';
        $notification->NOTIFICATION_LINK = '';
        $notification->ERROR_FLAG = '5';
        $notification->save();


        // prepare data for push notification
        $pushNotifications = [
            'NOTIFICATION' => $notification,
            'ROOM_NAME' => $room->ROOM_NAME,
            'SEEN_FLAG' => 0
        ];

        // trigger notification event with the pushed data
        event(new NotificationEvent($pushNotifications));

        // get all admin users
        $adminUserCollection = User::where('USER_TYPE', 1)->pluck('USER_ID');

        // prepare and map the data for UserNotification
        $userNotifications = $adminUserCollection->map(function ($adminUser) use ($notification) {
            return [
                'USER_ID' => $adminUser,
                'NOTIFICATION_ID' => $notification->NOTIFICATION_ID,
                'SEEN_FLAG' => 0
            ];
        })->toArray();


        // save user notifications to DB
        UserNotification::insert($userNotifications);


        return true;
    }
}
