<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\NewDeviceDataEvent;
use App\Models\AuthLocation;
use App\Models\BindingAlert;
use App\Models\Device;
use App\Models\ProcessedData;
use App\Models\User;
use App\Traits\CommonFunctions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * <Class Name> ProcessedDataController
 *
 * <Function Name> Processed Data Processing<br>
 * Create : 2018.06.26 TP Bryan<br>
 * Update : 2018.07.12 TP Bryan    Added sample event
 *          2018.07.12 TP Bryan    Added binding
 *          2018.09.26 TP Bryan    Added pull function
 *          2019.07.09 TP Ivin     Checking of Hierarchy and Adding of return comments
 *          2019.07.15 TP Jethro   Checked and fixed return comment and deleted all unecessary line breaks
 *          2019.12.12 TP Ivin     Create code for mode flag
 *          2020.01.15 TP Harvey   Added receiveBroadcastNotification Function
 *          2020.05.13 TP Uddin    Implement coding standard for PHP7
 *          2020.05.22 TP Uddin    Modify URL and Method Name according to the URL list
 *          2020.06.22 TP Harvey   Modify URL in API request to createBroadcastNotif<br>
 *          2021.06.03 TP Harvey   Add function Receiver from Netvox
 *          2021.07.21 TP Jermaine Comment out Wulian function on createProcessedData and createMcFunctionNotif
 *          2021.09.14 TP Chris    Add getNetvoxProcessedData and getStartAndEndTime method
 *
 * <Overview> This controller is responsible for inserting new processed data and
 *            notifications from other server
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ProcessedDataController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // createProcessedData         (1.0) Insert new processed data to database
    // createMcFunctionNotif       (2.0) Receive Uncommon MC Functions
    // createBroadcastNotif        (3.0) Receive Notification from other server.
    // createAlertInstruction      (4.0) Instruct to Alert the User
    // checkAlert                  (5.0) Instruct to Alert the User
    // newTXData                   (6.0) Api that will access by Netvox GW
    // getNetvoxProcessedData      (7.0) Get Netvox Processed Data from Database
    // getStartAndEndTime          (8.0) Get Start and End Time from Database

    use CommonFunctions;

    public function getProcessedDataDevice()
    {
        return ProcessedData::where('DEVICE_ID', 1)->limit(5)->get();
    }

    // - [Task023]
    // /**
    //  * <Layer number> (1.0)
    //  *
    //  * <Processing name> Create new processed data<br>
    //  * <Function> Insert a new processed data and save it to the database<br>
    //  *            URL: http://localhost/createProcessedData<br>
    //  *            METHOD: POST
    //  *
    //  * @param Request $request
    //  * @return Response 204
    //  * @throws Throwable When an exception occurs in this process
    //  */
    // public function createProcessedData(Request $request)
    // {
    //     $Test_Flag= env('TEST_FLAG');
    //     if($Test_Flag == 1){
    //         $dtStr1 = date("Y-m-d H:i:s") . "." .
    //             substr(explode(".",
    //                 (microtime(true) . ""))[1], 0, 3);
    //     }
    //     $message = [
    //         'content' => $request->MESSAGE,
    //         'iv' => $request->IV
    //     ];
    //     $decrypted = $this->decryptMessage($message);
    //     $decryptedArray = json_decode($decrypted,true);
    //     $retArr = [
    //         "DEVICE_SERIAL_NO" => $decryptedArray['device_id'],
    //         "DEVICE_DATA" => $decryptedArray['device_data']
    //     ];
    //     $device = Device::where('DEVICE_SERIAL_NO', $retArr['DEVICE_SERIAL_NO'])
    //         ->firstOrFail();
    //     //Check if Device is Registered
    //     if ($device->REG_FLAG != 1) {
    //         return response(204);
    //     }
    //     //Convert to more readable data
    //     $newData = $this->convertDeviceData($device->DEVICE_TYPE,
    //         $retArr['DEVICE_DATA'],'responseDeviceData');
    //     try{
    //         //Insert to Processed Data
    //         $processedData = new ProcessedData();
    //         $processedData->DEVICE_ID = $device->DEVICE_ID;
    //         $processedData->DATA = $newData;
    //         $processedData->SEND_FLAG = 0;
    //         if($Test_Flag == 1){
    //             // $dtStr2 = "Before save" . date("Y-m-d H:i:s") . "."
    //             //. substr(explode(".", (microtime(true) . ""))[1], 0, 3);
    //             $dtStr2 = date("Y-m-d H:i:s") . "."
    //                 . substr(explode(".", (microtime(true) .
    //                     ""))[1], 0, 3);
    //         }
    //         $processedData->save();
    //         if($Test_Flag == 1){
    //             // $dtStr3 = "After save" . date("Y-m-d H:i:s") . "."
    //             //. substr(explode(".", (microtime(true) . ""))[1], 0, 3);
    //             $dtStr3 = date("Y-m-d H:i:s") . "."
    //                 . substr(explode(".", (microtime(true) .
    //                     ""))[1], 0, 3);
    //         }
    //     }catch(\Throwable $e){
    //          //Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $uri = $request->URL;
    //         $content = $uri . " : " . $e->getMessage();
    //         $ip = $request->GATEWAY_IP;
    //         $username = "MC";
    //         $this->storeLogs($type,$instructionType,$content, $ip,$username);
    //         return $e->getMessage();
    //     }
    //     $device->DATA = $this->storeDeviceData(
    //         $device->DEVICE_ID, $device->DEVICE_TYPE, $device->DATA,$newData);
    //     try{
    //         $device->ONLINE_FLAG = 1; //Set status of device to online
    //         $device->save();
    //     }catch(\Throwable $e){
    //         // Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $uri = $request->URL;
    //         $content = $uri . " : " . $e->getMessage();
    //         $ip = $request->GATEWAY_IP;
    //         $username = "Device Saving";
    //         $this->storeLogs($type,$instructionType,$content, $ip,$username);
    //         return $e->getMessage();
    //     }

    //     //Broadcast for current Device Status
    //     $this->broadcastNewData($device);


    //     //Trigger Device Notification
    //     $this->insertDevicetoNotification($device);

    //     //Push notification to other server
    //     $local_IP   = env('IP_LOCAL');                  // IP of the current Server
    //     $serverList = env('SERVER_LIST');               // List of servers that has iBMS installed
    //     $serverList = explode(',',$serverList); // Array of servers that has iBMS installed
    //     foreach ($serverList as $key => $ip) {
    //         if($local_IP != $ip){
    //             try{
    //                 $client = new Client();
    //                 $result = $client->post('https://' . $ip .
    //                     "/api/createBroadcastNotif", [
    //                     'form_params'=> [
    //                         'DEVICE_DATA' => json_encode($device)
    //                     ]
    //                 ]);
    //             }catch(\Exception $e){
    //                 //Insert System Logs
    //                 // $type='3';
    //                 // $instructionType = 'System Error';
    //                 // $content = "Error in broadcasting notification in ".$ip;
    //                 // $ip = env('IP_LOCAL');
    //                 // $username = "-";
    //                 // $this->storeLogs($type, $instructionType, $content, $ip,
    //                 //     $username);
    //             }
    //         }
    //     }
    //     try {
    //         app('App\Http\Controllers\BindingController')
    //             ->checkBindings($processedData,$device);
    //     }
    //     catch(\Throwable $e) {
    //         echo "-________";
    //         echo $e;
    //         //Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $uri = $request->URL;
    //         $content = $uri . " : " . $e->getMessage();
    //         $ip = $request->GATEWAY_IP;
    //         $username = "MC";
    //         $this->storeLogs($type,$instructionType,$content, $ip,$username);
    //         return $e->getMessage();
    //     }
    //     return response(204);
    // }
    // - [Task023]

    // - [Task023]
    // /**
    //  * <Layer number> (2.0)
    //  *
    //  * <Processing name> Create MC function notification<br>
    //  * <Function> Receive Uncommon MC Functions<br>
    //  *            URL: http://localhost/createMcFunctionNotif<br>
    //  *            METHOD: POST
    //  *
    //  * @param Request $request
    //  * @return Response 204
    //  * @throws Throwable When an exception occurs in this process
    //  */
    // public function createMcFunctionNotif(Request $request)
    // {
    //     $message = [
    //         'content' => $request->MESSAGE,
    //         'iv' => $request->IV
    //     ];
    //     $decrypted = $this->decryptMessage($message);
    //     $decryptedArray = json_decode($decrypted,true);

    //     //Insert System Logs
    //     $type='5';
    //     $instructionType = 'Notice';
    //     $uri = $request->URL;
    //     if ($uri == 'alarmDevice') {
    //         $deviceSerial = $decryptedArray['device_id'];
    //         $device = Device::where('DEVICE_SERIAL_NO',$deviceSerial)->first();
    //         $content = "Other Function Triggered : ".$uri.": "
    //             .$device->DEVICE_NAME;
    //         $this->processNotification($device,'lowBattery','');
    //     }else{
    //         $content = "Other Function Triggered : ".$uri;
    //     }
    //     $ip = $request->GATEWAY_IP;
    //     $username = 'MC';
    //     $this->storeLogs($type,$instructionType,$content, $ip,$username);
    //     return response(204);
    // }
    // - [Task023]

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Create broadcast notification<br>
     * <Function> Receive Notification from other server<br>
     *            URL: http://localhost/createBroadcastNotif<br>
     *            METHOD: POST
     *
     * @param Request $request
     */
    public function createBroadcastNotif(Request $request)
    {
        $device = json_decode($request->DEVICE_DATA);
        $device->DATA = (array)$device->DATA;
        //Trigger Device Notification
        $this->insertDevicetoNotification($device, true);
        return response(204);
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Create alert instruction<br>
     * <Function> Instruct to Alert the User<br>
     *            URL: http://localhost/createAlertInstruction<br>
     *            METHOD: POST
     *
     * @param Request $request
     */
    public function createAlertInstruction($device)
    {
        //Emergency device has 1/0 device status
        if ($device->EMERGENCY_DEVICE == 1) {
            $authLocation = AuthLocation::where('FLOOR_ID', $device->FLOOR_ID)
                ->select('USER_ID')
                ->get();
            foreach ($authLocation as $user) {
                // retrieve users from authLoc
                $users = User::where('USER_ID', $user->USER_ID)
                    ->select('USERNAME', 'EMAIL', 'CONTACT_NUMBER', 'ALLOW_ALERT_NOTIFICATION')
                    ->get();
                foreach ($users as $key => $user) {
                    // retrieve user's alert notification
                    $this->checkAlert($user, $device);
                }
            }
        } else {
            $bindingAlert = BindingAlert::where('SOURCE_DEVICE_ID', $device->DEVICE_ID)
                ->where('BINDING_STATUS', 1)
                ->firstOrFail();
            //source device condition
            $sourceDeviceCondition = json_decode($bindingAlert['SOURCE_DEVICE_CONDITION'], true);
            $alertUsers = $bindingAlert['TARGET_USER_ALERT'];
            $operator = $sourceDeviceCondition['operator'];
            $sourceData = $sourceDeviceCondition['data'];

            if ($operator == 'MAX') {
                //check max source condition
                if ($device->DEVICE_TYPE == 'temp_hum') {
                    if ($device->DATA['temp'] >= $sourceData) {
                        $this->checkAlert($alertUsers, $device);
                    }
                } else {
                    if ($device->DATA['status'] >= $sourceData) {
                        $this->checkAlert($alertUsers, $device);
                    }
                }
            } elseif ($operator == 'MIN') {
                //check min source condition for temp device only
                if ($device->DATA['temp'] <= $sourceData) {
                    $this->checkAlert($alertUsers, $device);
                }
            }
        }
        return 'success';
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Check Alert<br>
     * <Function> Check alert method to use(SMS/Email).<br>
     *            URL: http://localhost/checkAlert<br>
     *            METHOD: POST
     *
     * @param Request $request
     */
    public function checkAlert($alertUsers, $device)
    {
        // $sFloorName = Floor::where('FLOOR_ID', $device->FLOOR_ID)
        //             ->select('FLOOR_NAME')
        //             ->get();
        // $sRoomName = Room::where('ROOM_ID', $device->ROOM_ID)
        //             ->select('ROOM_NAME')
        //             ->get();
        if (gettype($alertUsers) != 'array') {
            $alerts = json_decode($alertUsers['ALLOW_ALERT_NOTIFICATION'], true);
            $alertUsers['sms'] = $alerts['sms'];
            $alertUsers['email'] = $alerts['email'];
            $usersInfo = $alertUsers;
        } else {
            foreach ($alertUsers as $key => $alertUser) {
                $users = User::where('USER_ID', $alertUser['user_id'])
                    ->select('USERNAME', 'EMAIL', 'CONTACT_NUMBER')
                    ->get();
                foreach ($users as $key => $user) {
                    //     # retrieve user's alert notification
                    $alertUser['USERNAME'] = $user['USERNAME'];
                    $alertUser['CONTACT_NUMBER'] = $user['CONTACT_NUMBER'];
                    $alertUser['EMAIL'] = $user['EMAIL'];
                    $usersInfo = $alertUser;
                }
            }
        }
        if ($usersInfo['sms'] == true) {
            $sUsername = $usersInfo['USERNAME'];
            $iContactNo = $usersInfo['CONTACT_NUMBER'];
            $sMessage = $device->DEVICE_TYPE . ' alert';
            //send sms instruction
            app('App\Http\Controllers\InstructionController')
                ->sendAlertSMS($sUsername, $iContactNo, $sMessage);
        }
        if ($usersInfo['email'] == true) {
            $sUsername = $usersInfo['USERNAME'];
            $sEmailAdd = $usersInfo['EMAIL'];
            $sEmailSubj = $device->DEVICE_TYPE;
            $sMessage = $device->DEVICE_TYPE . ' alert';
            //send email instruction
            app('App\Http\Controllers\InstructionController')
                ->sendAlertEmail($sUsername, $sEmailAdd, $sEmailSubj, $sMessage);
        }
    }
    /**
     * <Layer number> (6.0)
     *
     * <Processing name>newTXData<br>
     * <Function> function that will accessed by Kerlink GW<br>
     *            URL: http://localhost/newTXData<br>
     *            METHOD: POST
     *
     * @param Request $request
     */
    public function newTXData(Request $request)
    {
        try {
            if ($request['DevEUI_uplink']) {
                // ThingPark
                $payload = $request['DevEUI_uplink']['payload_hex'];
                $device_serial = $request['DevEUI_uplink']['DevEUI'];
            } else {
                //Kerlink Only
                $payload = bin2hex(base64_decode($request->payload));
                $device_serial = $request->end_device_id;
            }

            $device = Device::where('DEVICE_SERIAL_NO', $device_serial)->firstOrFail();

            //Check if Device is Registered
            throw_if(!$device->REG_FLAG, new \Exception('Device not registered'));

            $newData = [];
            //Convert Hex Data to human readable data
            $newData = $this->convertDeviceData($device->DEVICE_TYPE, $payload, "");

            //If not Device Type is not supported, exit
            if (!is_array($newData)) {
                return "Device data not supported";
            }

            //Insert to Processed Data
            $processedData = new ProcessedData();
            $processedData->DEVICE_ID = $device->DEVICE_ID;
            $processedData->DATA = $newData;
            $processedData->SEND_FLAG = 0;
            $processedData->save();

            //Store Device data to database
            $device->DATA = $this->storeDeviceData($device->DEVICE_ID, $device->DEVICE_TYPE, $device->DATA, $newData);
            $device->ONLINE_FLAG = 1; //Set status of device to online
            $device->save();

            if ($device->DEVICE_TYPE == 'window_door_sensor' || $device->DEVICE_TYPE == 'occupancy_temp_light' || $device->DEVICE_TYPE == 'co2_temp_humid' || $device->DEVICE_TYPE == 'emergency_button') {
                event(new NewDeviceDataEvent($device));
            }

            $notif = app()->make('App\Http\Controllers\NotificationController');
            $notif->netvoxDeviceNotification($device);

            //Broadcast for current Device Status
            $this->broadcastNewData($device);

            //Trigger Device Notification
            $this->insertDevicetoNotification($device);

            //Check If device is binded to other device.
            app('App\Http\Controllers\BindingController')->checkBindings($processedData, $device);
        } catch (ModelNotFoundException $e) {
            return response('Device not found', 400);
        } catch (\Exception $e) {
            logger($e->getMessage());
            // Insert to new logs
            $uri = $request->getUri();
            // $this->processError($uri, $e);

            return response($e->getMessage(), 400);
        }

        return response("OK", 200);
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> getNetvoxProcessedData<br>
     * <Function> Get Processed Data based on Device in for Hotel<br>
     *            URL: http://localhost/getNetvoxProcessedData/<br>
     *            METHOD: GET
     *            DATA:
     * @param Request $request
     * @param int $id DEVICE_ID
     * @return array|object $processedData
     * @throws Throwable When an exception occurs in this process
     */
    public function getNetvoxProcessedDataHotel(Request $request, $id)
    {
        $processedData = ProcessedData::where('DEVICE_ID', $id)
            ->where('CREATED_AT', '>=', Carbon::yesterday())
            ->orderBy('CREATED_AT', 'ASC')
            ->get();
        return $processedData;
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> getStartAndEndTime<br>
     * <Function> Get Starts and Ends Time in the .env <br>
     *            URL: http://localhost/getStartAndEndTime/<br>
     *            METHOD: GET
     *            DATA:
     * @return array|object $processedData
     * @throws Throwable When an exception occurs in this process
     */
    public function getStartAndEndTimeHotel()
    {
        $starts_at = env('STARTS_AT');
        $ends_at = env('ENDS_AT');
        return  [
            'starts_at' => $starts_at,
            'ends_at' => $ends_at
        ];
    }
}
