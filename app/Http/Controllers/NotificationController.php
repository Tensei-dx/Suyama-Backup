<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Events\RoomEmergency;
use App\Models\Device;
use App\Models\Gateway;
use App\Models\LogsNotification;
use App\Models\Notification;
use App\Models\Room;
use App\Models\User;
use App\Models\UserNotification;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * <Class Name> NotificationController
 *
 * <Function Name> Notification Management and Processing<br>
 * Create : 2018.08.29 TP Robert<br>
 * Update : 2019.06.28 TP Jethro Edited insert query for USER NOTIFICATION to optimize execution time
 *          2019.07.10 TP Ivin   insert try catch functions and remove logsNotification
 *          2020.01.15 TP Harvey Modified Parameters in insertNotification Function
 *          2020.01.15 TP Harvey Added condition in before save in insertNotification Function
 *          2020.05.13 TP Uddin  Implement coding standard for PHP7
 *          2020.05.21 TP Uddin  Modify URL and Methodname according to the URL list<br>
 *
 * <Overview> This controller is responsible for managing notifications.
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class NotificationController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // createNotification               (1.0)  Send instruction to MC to create new system notification
    // getNotification                  (2.0)  Retrieve user notifications
    // updateNotification               (3.0)  Mark notification as seen
    // getNotificationTest              (4.0)  Get User Notifications
    // deviceNotification               (5.0)  Device notifications
    // lateCheckInChecker               (6.0)  Late check in notifications
    // lateCheckOutChecker              (7.0)  Late check out notifications
    // natureRemoDeviceNotification     (8.0)  Create notifications for nature remo device
    // checkNetvoxConnectivity          (9.0)  Check netvox connectivity every 2 hours
    // createOrUpdateLogsNotif          (10.0) Create or update logs notification depending on the conditions
    // PMSnotification                  (11.0) Create logs notification depending on the conditions for PMS

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Create new notification<br>
     * <Function> Send instruction to MC to create new system notification<br>
     *            URL: http://localhost/createNotification<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @param bool $isBroadcast
     * @return array|string $push_notif
     * @throws Throwable When an exception occurs in this process
     */
    public function createNotification(Request $request, bool $isBroadcast = false)
    {
        $module = 'Notification Controller';
        $username = 'MC';
        //For Audit Logs
        try {
            $gateway = Gateway::where('GATEWAY_ID', $request->GATEWAY_ID)
                ->select('GATEWAY_IP')
                ->first();
            $ip = $gateway->GATEWAY_IP;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        try {

            $notif = new Notification();
            $notif->OBJECT_ID = 1;
            $notif->ROOM_ID = $request->ROOM_ID;
            $notif->OBJECT_NAME = $request->OBJECT_NAME;
            $notif->SUBJECT = $request->SUBJECT;
            $notif->CONTENT = $request->CONTENT;
            $notif->ERROR_FLAG = $request->ERROR_FLAG;
            $notif->NOTIFICATION_LINK = $request->NOTIFICATION_LINK;
            //Don't save if notification came from another server

            if (!$isBroadcast) {
                $notif->save();
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        //Notify for Notification Alert
        $seen = '{"SEEN_FLAG": 0}';
        $notif->ROOM_MAP_DATA = $request->ROOM_MAP_DATA;
        $push_notif = [
            "NOTIFICATION" => array_merge(
                json_decode($notif, true),
                json_decode($seen, true)
            )
        ];
        event(new NotificationEvent($push_notif));
        try {
            $users = User::select('USER_ID')->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        //array for insert queries
        $userquery = [];
        //insert each user into array for mass insert query
        foreach ($users as $user) {
            array_push($userquery, [
                'USER_ID' => $user->USER_ID,
                'NOTIFICATION_ID' => $notif->NOTIFICATION_ID,
                'SEEN_FLAG' => 0
            ]);
        }
        try {
            UserNotification::insert($userquery);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        return $push_notif;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Acquire notification<br>
     * <Function> Retrieve user notifications<br>
     *            URL: http://localhost/getNotification/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $notif
     * @throws Throwable When an exception occurs in this process
     */
    public function getNotification(Request $request, int $id)
    {
        $module = 'Notification Controller';
        $sender = 'MC';
        try {
            $date = date('Y-m-d H:i:s', strtotime("-1 month"));
            $notif = Notification::whereHas('userNotification', function ($query) use ($id) {
                $query->where('USER_ID', $id);
            })
                ->with('room:ROOM_ID')
                ->where('CREATED_AT', '>', $date)
                ->orderBy('NOTIFICATION_ID', 'DESC')
                ->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        return $notif;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Update notification<br>
     * <Function> Mark notification as seen<br>
     *            URL: http://localhost/updateNotification<br>
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
        try {
            $updateNotif = UserNotification::where(
                'NOTIFICATION_ID',
                $request->NOTIFICATION_ID
            )
                ->where('USER_ID', $request->USER_ID)
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
     * <Layer Number> (4.0)
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
    public function getNotificationTest(Request $request, int $id)
    {
        try {
            // $id = auth()->id();
            $userType = 2;
            $date = date('Y-m-d H:i:s', strtotime("-1 month"));
            $notifs = UserNotification::where('USER_ID', auth()->id())
                ->whereHas('userNotification', function (Builder $query) use ($userType) {
                    $query->where('OBJECT_NAME', 'CHECKED IN')
                        ->orWhere('OBJECT_NAME', 'CHECKED OUT');
                })

                // $notifs = Notification::whereHas('userNotification', function (Builder $query) use($id){
                //     $query->whereHas('user', function(Builder $query2) use ($id){
                //         $query2->where('USER_ID', $id);
                //     });
                // })
                ->with('userNotification')
                //->where('CREATED_AT', '>', $date)
                // ->latest()
                ->limit(10)
                ->orderBy('NOTIFICATION_ID', 'DESC')
                ->get();

            $retArr = [];

            //
            foreach ($notifs as $notif) {
                $tempArr = [
                    'NOTIFICATION_ID' => $notif->userNotification->NOTIFICATION_ID
                ];
            }
            return $notifs;
            foreach ($notifs as $notif) {

                $date = date("Y/m/d H:i", strtotime($notif->userNotification->CREATED_AT));
                $tempArr = [
                    'NOTIFICATION_ID' => $notif->userNotification->NOTIFICATION_ID,
                    'OBJECT_NAME' => $notif->userNotification->OBJECT_NAME,
                    'ROOM_ID' => $notif->userNotification->ROOM_ID,
                    'SUBJECT' => $notif->userNotification->SUBJECT,
                    'CREATED_AT' => $date,
                    'ROOM_NAME' => 'ROOM 101',
                    'SEEN_FLAG' => $notif->SEEN_FLAG,
                ];
                $retArr[] = $tempArr;
            }
            return $retArr;
        } catch (\Throwable $th) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $th);
        }
    }

    /**
     * <Layer Number> (5.0)
     *
     * <Processing Name>Device notifications<br>
     * <Function>Create notifications for Netvox devices<br>
     *            URL:
     *            METHOD:
     *
     * @param object $device
     * @return null
     */
    public function netvoxDeviceNotification(object $device)
    {
        switch ($device->DEVICE_TYPE) {
            case 'occupancy_temp_light': {
                    //Battery low level
                    if ($device->DATA['battery'] < $device->LOW_VOLTAGE) {
                        $this->logsNotification('W002', $device->ROOM_ID, 0);
                    }
                }
                break;
            case 'window_door_sensor': {
                    //Battery low level
                    if ($device->DATA['battery'] < $device->LOW_VOLTAGE) {
                        $this->logsNotification('W003', $device->ROOM_ID, 0);
                    }
                }
                break;
            case 'emergency_button': {
                    if ($device->DATA['status'] == 1) {
                        $this->logsNotification('E017', $device->ROOM_ID, 0);
                    }
                    //Battery low level
                    if ($device->DATA['battery'] < $device->LOW_VOLTAGE) {
                        $this->logsNotification('W004', $device->ROOM_ID, 0);
                    }
                }
                break;
        }
    }

    /**
     * <Layer Number> (6.0)
     *
     * <Processing Name>Late check in notifications<br>
     * <Function>Create notifications for late check-in<br>
     *            URL:
     *            METHOD:
     * @param int $room_id
     */
    public function lateCheckInChecker()
    {
        $rooms = Room::with('checkInToday')->get();
        foreach ($rooms as $room) {
            if ($room->checkInToday != null && $room->checkInToday->ENTERED_FLAG == 1 && $room->STATUS_ID == 205) {
                $timeCheckInSec = strtotime($room->checkInToday->CHECK_IN_TIME);
                $timeEnteredInSec = strtotime($room->checkInToday->ENTERED_TIME);
                $timeTodayInSec = strtotime(Carbon::now());
                $timeDiffTodayEntered = ($timeTodayInSec - $timeEnteredInSec) / 3600;
                if ($timeDiffTodayEntered >= 1) {
                    $this->logsNotification('E009', $room->ROOM_ID, 0);
                }
            }
        }
    }

    /**
     * <Layer Number> (7.0)
     *
     * <Processing Name>Late check out notifications<br>
     * <Function>Create notifications for late check-out<br>
     *            URL:
     *            METHOD:
     */
    public function lateCheckOutChecker()
    {
        $rooms = Room::with('checkOutToday')->get();
        foreach ($rooms as $room) {
            if ($room->STATUS_ID == 201 && $room->checkOutToday != null) {
                if ($room->checkOutToday->CHECK_OUT_TIME < Carbon::now()) {
                    $this->logsNotification('E010', $room->ROOM_ID, 0);
                }
            }
        }
    }

    /**
     * <Layer Number> (8.0)
     *
     * <Processing Name>Nature Remo Device Notification<br>
     * <Function>Create notifications for nature remo device<br>
     *            URL:
     *            METHOD:
     * @param string $signal_label
     * @param int $roomID
     */
    public function natureRemoDeviceNotification(string $signal_label, int $roomID)
    {
        switch ($signal_label) {
            case 'power-off button': {
                    $this->logsNotification('I002', $roomID, 5);
                }
                break;
            case 'cool mode': {
                    $this->logsNotification('I003', $roomID, 5);
                }
                break;
            case 'warm mode': {
                    $this->logsNotification('I004', $roomID, 5);
                }
                break;
            case 'dry mode': {
                    $this->logsNotification('I005', $roomID, 5);
                }
                break;
            case 'auto mode': {
                    $this->logsNotification('I006', $roomID, 5);
                }
                break;
        }
    }

    /**
     * <Layer Number> (9.0)
     *
     * <Processing Name>Netvox Connectivity<br>
     * <Function>Check netvox connectivity every 2 hours<br>
     *            URL:
     *            METHOD:
     */
    public function checkNetvoxConnectivity()
    {
        $netvox_devices = Device::where('DEVICE_TYPE', 'occupancy_temp_light')
            ->orWhere('DEVICE_TYPE', 'window_door_sensor')
            ->orWhere('DEVICE_TYPE', 'co2_temp_humid')
            ->orWhere('DEVICE_TYPE', 'emergency_button')
            ->get();
        foreach ($netvox_devices as $netvox_device) {
            $timeCheckInSec = strtotime($netvox_device->UPDATED_AT);
            $timeTodaySec = strtotime(Carbon::now());
            $timeDiffSec = $timeTodaySec - $timeCheckInSec;
            $time = $timeDiffSec / 3600;
            if ($time >= 2) {
                switch ($netvox_device->DEVICE_TYPE) {
                    case 'occupancy_temp_light': {
                            $this->createOrUpdateLogsNotif('E013', $netvox_device->ROOM_ID);
                            break;
                        }
                    case 'window_door_sensor': {
                            $this->createOrUpdateLogsNotif('E014', $netvox_device->ROOM_ID);
                            break;
                        }
                    case 'co2_temp_humid': {
                            $this->createOrUpdateLogsNotif('E015', $netvox_device->ROOM_ID);
                            break;
                        }
                    case 'emergency_button': {
                            $this->createOrUpdateLogsNotif('E018', $netvox_device->ROOM_ID);
                            break;
                        }
                }
            }
        }
    }

    /**
     * <Layer Number> (10.0)
     *
     * <Processing Name>Create or Update Logs Notification<br>
     * <Function>Create or update logs notification depending on the conditions<br>
     *            URL:
     *            METHOD:
     * @param string $message_id
     * @param int $room_id
     */
    public function createOrUpdateLogsNotif(string $message_id, int $room_id)
    {
        $log = LogsNotification::with('correspondence')->where('MESSAGE_ID', $message_id)
            ->orderBy('UPDATED_AT', 'DESC')->first();

        //Check if LogsNotification exist. If exist update , else create new
        //Update
        if (isset($log)) {
            //Check if LogsNotification has responded. If responded create new, else update it
            //Create
            if (isset($log->correspondence)) {
                $this->logsNotification($message_id, $room_id, 0);
            }
            //Update
            else {
                $log_notif_update = LogsNotification::where('LOGS_NOTIF_ID', $log->LOGS_NOTIF_ID)->first();
                $log_notif_update->UPDATED_AT = Carbon::now();
                $log_notif_update->save();
            }
        }
        //Create
        else {
            $this->logsNotification($message_id, $room_id, 0);
        }
    }

    /**
     * <Layer Number> (11.0)
     *
     * <Processing Name>Create PMS Notification<br>
     * <Function>Create logs notification depending on the conditions for PMS<br>
     *            URL:
     *            METHOD:
     * @param object $response
     */
    public function PMSnotification(Request $response)
    {
        //Name has not been entered
        if (!isset($response->name_kanji)) {
            $message_id = 'E002 ' . $response->start_date;
            info($message_id);
            foreach ($response->allocate_rooms as $room) {
                $room_id = $room['room_id'];
                $this->logsNotification($message_id, $room_id, 0);
            }
        }
        //Email has not been entered
        if (!isset($response->email)) {
            $message_id = 'E003 ' . $response->start_date;
            foreach ($response->allocate_rooms as $room) {
                $room_id = $room['room_id'];
                $this->logsNotification($message_id, $room_id, 0);
            }
        }
        //Check-in time has not been entered
        if ($response->check_in_time == "00:00") {
            $message_id = 'E004 ' . $response->start_date;
            foreach ($response->allocate_rooms as $room) {
                $room_id = $room['room_id'];
                $this->logsNotification($message_id, $room_id, 0);
            }
        }
        //Check-out time has not been entered
        if ($response->check_out_time == "23:59") {
            $message_id = 'E005 ' . $response->start_date;
            foreach ($response->allocate_rooms as $room) {
                $room_id = $room['room_id'];
                $this->logsNotification($message_id, $room_id, 0);
            }
        }
        //Number of person has not been entered
        foreach ($response->allocate_rooms as $room) {
            if (($room['adult_number'] + $room['child_number']) == 0) {
                $message_id = 'E006 ' . $response->start_date;
                $room_id = $room['room_id'];
                $this->logsNotification($message_id, $room_id, 0);
            }
        }
    }

    /**
     * <Layer number> (12.0)
     * 
     * <Processing name> broadcastRoomEmergency <br>
     * <Function> Broadcast room emergency to every admin user <br>
     *          URL: /notifications/room-emergency
     *          METHOD: POST
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function broadcastRoomEmergency(Request $request)
    {
        $request->validate([
            'ROOM_ID' => 'required|integer|exists:M004_ROOM,ROOM_ID'
        ]);

        $room = Room::findOrFail($request->ROOM_ID);
        $room->EMERGENCY_FLAG = true;
        $room->save();

        broadcast(new RoomEmergency($room));

        return response()->noContent();
    }
}
