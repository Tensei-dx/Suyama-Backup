<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\Binding;
use App\Models\BindingList;
use App\Models\Device;
use App\Models\Gateway;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * <Class Name> DeviceController
 *
 * <Function Name> Device Management and Processing<br>
 * Create : 2018.06.21 TP Bryan<br>
 * Update : 2018.06.25 TP Bryan    Added new functions 4.0, 5.0
 *          2018.06.26 TP Bryan    Edited variable name, return values for all functions
 *          2018.06.27 TP Bryan    Edited query for 1.0
 *          2018.06.28 TP Bryan    Edited send message for 2.0, fixed comments
 *                                 Renamed 6.0 to getRegisteredDevices
 *          2018.06.29 TP Bryan    Edited 1.0 added socket timeout
 *          2018.07.11 TP Bryan    Added 7.0
 *          2018.07.12 TP Bryan    Edited 1.0 added event
 *          2018.07.25 TP Bryan    Inserted new functions, Fixed code structure
 *          2018.07.26 TP Bryan    Added 20.0, 21.0
 *          2018.08.07 TP Bryan    Finalized(?) functions as endpoints
 *          2018.08.20 TP Bryan    Fixed code structure
 *          2018.10.10 TP Harvey   Fix Encryption Sending
 *          2018.11.06 TP Robert   Modify the CRUD functions
 *          2019.05.28 TP Harvey   Applying COnding Standard
 *          2019.06.04 TP Jethro   Modified return for getDeviceBindingListDevices and getMultiDeviceBindingListDevices and deleted use of Illuminate/Paginate/LengthAwarePaginator
 *          2019.06.11 TP Jethro   Checked coding standard and fixed mismatching code with comments
 *          2019.06.21 TP Mark     Applying PG Implementation Matrix (Frontend)
 *          2019.07.08 TP Mark     Applying Horizontal Expansion
 *          2019.07.10 TP Ivin     Remove Scan Device Function
 *          2019.08.01 TP Ivin     Remove Insert Audit Logs in get Functions
 *          2019.08.05 TP Ivin     Remove unnecessary codes in updateDevice
 *          2019.08.06 TP Ivin     Remove searchDevices and getDeviceInstructions functions and reorder Hierarchy
 *          2019.09.09 TP Jethro   Found a bug with device update where the function returns 'exists' when updating device category with the same name and have fixed it. Changed getRegisteredDevices,
 *                                 getUnregisteredDevices, and getBlockedDevices functions to enable key sorting in tables.
 *          2019.10.30 TP Harvey   Fix bugs in getDeviceBindingListDevices
 *          2020.03.13 TP Harvey   Apply Custom binding in getDeviceBindingListDevices
 *          2020.05.20 TP Uddin    Modify URLs and method names according to the URL list
 *          2020.09.23 TP Russell  Modify getDevicesWithBindings for Binding Threshold<br>
 *          2021.05.27 TP Shannie  Modify scan device function for NetVox Devices
 *                                 and add payload converter
 *          2021.07.13 TP Ivin     Modify Scan Function for Remote Lock
 *          2021.07.13 TP Ivin     Modify the getRegisteredDevice, getUnRegisteredDevice and blockDevice to display the remote lock in each functions
 *          2021.07.13 TP Jermaine Comment out Wulian function on getDeletedDevices, unblockDevice, createDevice, deleteDeviceFromMC, offlineDevice and onlineDeviceByGateway
 *          2021.07.15 TP Harvey   Comment out Wulian function scanDeviceAll
 *
 * <Overview> This controller is responsible for managing devices and retrieving data from the devices
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class DeviceController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // scanDeviceAll                   (1.0) Scan network for available devices
    // getDeviceAll                    (2.0) Retrieve all devices from database
    // getDevice                       (3.0) Retrieve a specific device from database
    // getUnregisteredDevices          (4.0) Retrieve all devices with REG_FLAG of 0 in the database
    // getRegisteredDevices            (5.0) Retrieve all devices with REG_FLAG of 1 in the database
    // getBlockedDevices               (6.0) Retrieve all devices with REG_FLAG of 4 in the database
    // getDeletedDevices               (7.0) Retrieve all devices with REG_FLAG of 9 in the database
    // getDeviceFloor                  (8.0) Retrieve floor information associated with the specified device
    // getDeviceRoom                   (9.0) Retrieve room information associated with the specified device
    // getDeviceGateway                (10.0) Retrieve gateway information associated with the specified device
    // getDeviceProcessedData          (11.0) Retrieve current processed data of the specified device
    // getDeviceBindings               (12.0) Retrieve device/sensor bindings information associated with the specified device
    // registerDevice                  (13.0) Update device's REG_FLAG to 1
    // updateDevice                    (14.0) Update a device information
    // deleteDevice                    (15.0) Request to MC to delete a specific device
    // blockDevice                     (16.0) Update device's REG_FLAG to 4
    // unblockDevice                   (17.0) Update device's REG_FLAG to 0
    // createDevice                    (18.0) Create new device entry in database (API)
    // getDevicesWithBindings          (19.0) Retrieve all devices with registered bindings
    // getDeviceBindingList            (20.0) Retrieve all binding list associated with the device
    // getDeviceBindingListCondition   (21.0) Retrieve all binding list condition for this device
    // getDeviceBindingListDevices     (22.0) Retrieve all device infomation bound with the specified device
    // getMultiDeviceBindingListDevices(23.0) Retrieve all bound devices according to the specified device type
    // deleteDeviceFromMC              (24.0) Deleting Device from MC
    // offlineDevice                   (25.0) Force offline a device through MC
    // onlineDeviceByGateway           (26.0) Change status of Device based on status of gateway
    // convertPayload                  (27.0) Covert the Payload from the API to HEX

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Scan all devices<br>
     * <Function> Scan network for available devices<br>
     *            URL: http://localhost/scanDeviceAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $sRet
     * @throws Throwable When an exception occurs in this process
     */
    public function scanDeviceAll(Request $request)
    {
        // Scanned serial no. of devices
        $dataList = [];
        try {
            // Get gateway for the NetVox Device
            $gateways = Gateway::where('MANUFACTURER_ID', 6)
                ->where('REG_FLAG', 1)
                ->select("GATEWAY_IP", "FLOOR_ID", "ROOM_ID", "GATEWAY_ID")
                ->get();

            // Throw Error
            if (!$gateways) {
                throw new \Exception("0:Gateway Not Found", 210050000);
            }

            // Loop for gateway
            foreach ($gateways as $gateway) {
                // Gateway
                $ipGW = $gateway->GATEWAY_IP;

                // Get Token
                $getAccessToken = $this->getAccessTokenForNetvoxGateway($ipGW);
                $response = Http::withToken($getAccessToken)
                    ->get("http://$ipGW/application/spn/end_devices/OTAA")
                    ->json();

                //extracting the list data from the response data
                $listDatas = $response['list'];

                //Loop for serial no. and device type of device
                foreach ($listDatas as $listData) {
                    // serial no. of devices
                    $serial_arr = $listData['dev_eui'];
                    // device type
                    $devType = $this->convertPayload($request, $serial_arr, $ipGW);

                    // If $devType has value
                    if ($devType) {
                        // add column to $listData
                        $listData['dev_type'] = $devType;
                        array_push($dataList, $listData);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }

        // If $dataList has value
        if ($dataList) {

            // Loop for saving scanned device to DB
            foreach ($dataList as $list) {

                // Get device info from DB
                $typeDev = Device::where('MANUFACTURER_ID', 6)
                    ->where('DEVICE_SERIAL_NO', $list['dev_eui'])
                    ->first();
            }

            // If $typeDev is empty
            if (!$typeDev) {
                // save new device info to DB
                try {
                    $createDev = new Device();
                    $createDev->MANUFACTURER_ID = 6;
                    $createDev->DEVICE_SERIAL_NO = $list['dev_eui'];
                    $createDev->DEVICE_TYPE = $list['dev_type'];
                    $createDev->REG_FLAG = 0;
                    $createDev->ONLINE_FLAG = 0;
                    $createDev->FLOOR_ID = $gateway->FLOOR_ID;
                    $createDev->ROOM_ID = $gateway->ROOM_ID;
                    $createDev->GATEWAY_ID = $gateway->GATEWAY_ID;
                    $createDev->save();
                } catch (\Throwable $e) {
                    // Insert to new logs
                    $uri = $request->getUri();
                    $this->processError($uri, $e);
                }
            }
        }

        // [Wulian Function]
        // try{

        //     $gateways = Gateway::where('MANUFACTURER_ID',1)
        //         ->select("GATEWAY_IP")
        //         ->get();
        //     foreach ($gateways as $gateway) {
        //         // MC network details
        //         $remote_ip = $gateway->GATEWAY_IP;
        //         $remote_port = env('PORT_GATEWAY');
        //         $data = '{"mode":"scanAllDevices"}';
        //         $message = $this->encryptMessage($data);
        //         $timeout =["sec"=>0,"usec"=>100000];
        //         $sRet = $this->sendToSocket($remote_ip, $remote_port, $message,
        //             $timeout);
        //     }
        //     //return $sRet;
        // }catch(\Throwable $e){
        //     //Insert System Logs
        //     $type='3';
        //     $instructionType = 'System Error';
        //     $uri = $request->route()->uri();
        //     $content = $uri . " : " . $e->getMessage();
        //     $ip = $request->ip();
        //     $username = auth()->user()->USERNAME;
        //     $this->storeLogs($type,$instructionType,$content,$ip,$username);
        //     return $e->getMessage();
        // }



        //Remote Lock Scan Function
        $id = 3;
        //Remote lock Access Token
        $access_token = $this->getAccessToken($id);

        $errorToken = [];
        if (isset($access_token['error'])) {
            $errorToken[] = $access_token['error_description'];
            return $errorToken;
        }
        $token = $access_token;
        $headers = [
            'Accept' => 'application/vnd.lockstate+json; version=1',
            'Content-Type' => 'application/json'
        ];

        $getRemote = Http::withToken($token)
            ->withHeaders($headers)
            ->get('https://api.remotelock.jp/devices')
            ->json();

        $remoteDatas = $getRemote['data'];

        foreach ($remoteDatas as $remoteData) {

            $remoteSerial = $remoteData['attributes']['serial_number'];
            $remoteIds = $remoteData['id'];
            $remoteId = '{"remote_lock_id" : "' . $remoteIds . '"}';
            $remoteId = json_decode($remoteId);

            try {
                $serialData = Device::where('MANUFACTURER_ID', 5)
                    ->where('DEVICE_SERIAL_NO', $remoteSerial)
                    ->first();
                //Save all the new remote lock to DB
                if (!$serialData) {
                    $createRemote = new Device();
                    $createRemote->MANUFACTURER_ID = 5;
                    $createRemote->DEVICE_SERIAL_NO = $remoteSerial;
                    $createRemote->DEVICE_TYPE =  'Remote lock';
                    $createRemote->DEVICE_CATEGORY = 1;
                    $createRemote->DATA = $remoteId;
                    $createRemote->REG_FLAG = 0;
                    $createRemote->ONLINE_FLAG = 0;
                    $createRemote->FLOOR_ID = null;
                    $createRemote->ROOM_ID = null;
                    $createRemote->GATEWAY_ID = null;
                    $createRemote->save();
                }
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get all devices<br>
     * <Function> Retrieve all devices from database<br>
     *            URL: http://localhost/getDeviceAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $this->createGetResponse($request, (new Device)->newQuery())
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceAll(Request $request)
    {
        try {
            return $this->createGetResponse($request, (new Device())->newQuery());
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get a device<br>
     * <Function> Retrieve a specific device from database<br>
     *            URL: http://localhost/getDevice/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request,
     *                (new Device)->with('deviceBindings')->newQuery(), $id)
     * @throws Throwable When an exception occurs in this process
     */
    public function getDevice(Request $request, int $id)
    {
        try {
            return $this->createGetResponse(
                $request,
                (new Device())->with('deviceBindings')->newQuery(),
                $id
            );
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Get unregistered devices<br>
     * <Function> Retrieve all devices with REG_FLAG of 0 in the database<br>
     *            URL: http://localhost/getUnregisteredDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $deviceArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getUnregisteredDevices(Request $request)
    {
        try {
            $devices = Device::where('REG_FLAG', 0)
                ->with('gateway:GATEWAY_NAME,GATEWAY_IP,GATEWAY_ID')
                ->with('floor:FLOOR_NAME,FLOOR_ID')
                ->with('room:ROOM_ID,ROOM_NAME')
                ->get();
            $deviceArr = [];
            foreach ($devices as $device) {
                array_push($deviceArr, [
                    "REG_FLAG"  => $device->REG_FLAG,
                    "GATEWAY_ID" => (isset($device->gateway->GATEWAY_ID))  ? $device->gateway->GATEWAY_ID : 0,
                    "GATEWAY_NAME" => (isset($device->gateway->GATEWAY_NAME)) ? $device->gateway->GATEWAY_NAME : '-',
                    "DEVICE_SERIAL_NO" => $device->DEVICE_SERIAL_NO,
                    "FLOOR_ID" => $device->FLOOR_ID,
                    "FLOOR_NAME" => (isset($device->floor->FLOOR_NAME)) ? $device->floor->FLOOR_NAME : '-',
                    "ROOM_ID" => $device->ROOM_ID,
                    "ROOM_NAME" => (isset($device->room->ROOM_NAME)) ? $device->room->ROOM_NAME : '-',
                    "GATEWAY_IP" => (isset($device->gateway->GATEWAY_IP)) ? $device->gateway->GATEWAY_IP : '-',
                    "DEVICE_NAME" => null,
                    "DEVICE_TYPE" => $device->DEVICE_TYPE,
                    "DEVICE_CATEGORY" => $device->DEVICE_CATEGORY,
                    "DEVICE_ID" => $device->DEVICE_ID,
                    "DATA" => $device->DATA
                ]);
            }
            return $deviceArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Get registered devices<br>
     * <Function> Retrieve all devices with REG_FLAG of 1 in the database<br>
     *            URL: http://localhost/getRegisteredDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $deviceArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getRegisteredDevices(Request $request)
    {
        try {
            $devices = Device::where('REG_FLAG', 1)
                ->with('gateway:GATEWAY_NAME,GATEWAY_IP,GATEWAY_ID')
                ->with('floor:FLOOR_NAME,FLOOR_ID')
                ->with('room:ROOM_ID,ROOM_NAME')
                ->with('deviceBindings')
                ->get();

            // Throw Error
            if (!$devices) {
                throw new \Exception("0:No Registered Device", 400050000);
            }

            $deviceArr = [];

            foreach ($devices as $device) {
                array_push($deviceArr, [
                    "REG_FLAG"  => $device->REG_FLAG,
                    "GATEWAY_ID" => (isset($device->gateway->GATEWAY_ID))  ? $device->gateway->GATEWAY_ID : 0,
                    "GATEWAY_NAME" => (isset($device->gateway->GATEWAY_NAME)) ? $device->gateway->GATEWAY_NAME : '-',
                    "DEVICE_SERIAL_NO" => $device->DEVICE_SERIAL_NO,
                    "FLOOR_ID" => $device->FLOOR_ID,
                    "FLOOR_NAME" => (isset($device->floor->FLOOR_NAME)) ? $device->floor->FLOOR_NAME : '-',
                    "ROOM_ID" => $device->ROOM_ID,
                    "ROOM_NAME" => (isset($device->room->ROOM_NAME)) ? $device->room->ROOM_NAME : '-',
                    "GATEWAY_IP" => (isset($device->gateway->GATEWAY_IP)) ? $device->gateway->GATEWAY_IP : '-',
                    "DEVICE_NAME" => $device->DEVICE_NAME,
                    "DEVICE_TYPE" => $device->DEVICE_TYPE,
                    "DEVICE_CATEGORY" => $device->DEVICE_CATEGORY,
                    "DEVICE_ID" => $device->DEVICE_ID,
                    "DATA" => $device->DATA
                ]);
            }
            return $deviceArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Get blocked devices<br>
     * <Function> Retrieve all devices with REG_FLAG of 4 in the database<br>
     *            URL: http://localhost/getBlockedDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $deviceArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getBlockedDevices(Request $request)
    {
        try {
            $devices = Device::where('REG_FLAG', 4)
                ->with('gateway:GATEWAY_NAME,GATEWAY_IP,GATEWAY_ID')
                ->with('floor:FLOOR_NAME,FLOOR_ID')
                ->with('room:ROOM_ID,ROOM_NAME')
                ->get();
            $deviceArr = [];
            foreach ($devices as $device) {
                array_push($deviceArr, [
                    "REG_FLAG"  => $device->REG_FLAG,
                    "GATEWAY_ID" => (isset($device->gateway->GATEWAY_ID)) ? $device->gateway->GATEWAY_ID : 0,
                    "GATEWAY_NAME" => (isset($device->gateway->GATEWAY_NAME)) ? $device->gateway->GATEWAY_NAME : '-',
                    "FLOOR_ID" => $device->FLOOR_ID,
                    "FLOOR_NAME" => (isset($device->floor->FLOOR_NAME)) ? $device->floor->FLOOR_NAME : '-',
                    "ROOM_ID" => $device->ROOM_ID,
                    "ROOM_NAME" => (isset($device->room->ROOM_NAME)) ? $device->room->ROOM_NAME : '-',
                    "GATEWAY_IP" => (isset($device->gateway->GATEWAY_IP)) ? $device->room->ROOM_NAME : '-',
                    "DEVICE_NAME" => null,
                    "DEVICE_TYPE" => $device->DEVICE_TYPE,
                    "DEVICE_CATEGORY" => $device->DEVICE_CATEGORY,
                    "DEVICE_ID" => $device->DEVICE_ID,
                    "DEVICE_SERIAL_NO" => $device->DEVICE_SERIAL_NO,
                    "DATA" => $device->DATA
                ]);
            }
            return $deviceArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return "failed";
        }
    }

    // - [Task023]
    // /**
    //  * <Layer number> (7.0)
    //  *
    //  * <Processing name> Get deleted devices<br>
    //  * <Function> Retrieve all devices with REG_FLAG of 9 in the database<br>
    //  *            URL: http://localhost/getDeletedDevices<br>
    //  *            METHOD: GET
    //  *
    //  * @param Request $request
    //  * @return object $this->createGetResponse($request,
    //  *                Device::where('REG_FLAG', 9))
    //  * @throws Throwable When an exception occurs in this process
    //  */
    // public function getDeletedDevices(Request $request)
    // {
    //     try{
    //         return $this->createGetResponse($request, Device::where('REG_FLAG', 9));
    //     }catch(\Throwable $e){
    //         //Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $uri = $request->route()->uri();
    //         $content = $uri . " : " . $e->getMessage();
    //         $ip = $request->ip();
    //         $username = auth()->user()->USERNAME;
    //         $this->storeLogs($type,$instructionType,$content,$ip,$username);
    //         return "failed";
    //     }
    // }
    // - [Task023]

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Acquire device's floor information<br>
     * <Function> Retrieve floor information associated with the specified device<br>
     *            URL: http://localhost/getDeviceFloor/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, Device::findOrFail($id)->floor())
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceFloor(Request $request, int $id)
    {
        try {
            return $this->createGetResponse($request, Device::findOrFail($id)->floor());
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return "failed";
        }
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Acquire device's room information<br>
     * <Function> Retrieve room information associated with the specified device<br>
     *            URL: http://localhost/getDeviceRoom/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, Device::findOrFail($id)->room())
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceRoom(Request $request, int $id)
    {
        try {
            $deviceRoom = $this->createGetResponse($request, Device::find($id)->room());
            // Throw Error
            if (!$deviceRoom) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("0:No Device in Room", 300030000);
            }
            return $deviceRoom;
        } catch (\Throwable $e) {
            //Insert System Logs
            // $type='3';
            // $instructionType = 'System Error';
            // $uri = $request->route()->uri();
            // $content = $uri . " : " . $e->getMessage();
            // $ip = $request->ip();
            // $username = auth()->user()->USERNAME;
            // $this->storeLogs($type,$instructionType,$content,$ip,$username);
            // return "failed";

            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> Acquire device's gateway information<br>
     * <Function> Retrieve gateway information associated with the specified device<br>
     *            URL: http://localhost/getDeviceGateway/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, Device::findOrFail($id)
     *                ->gateway())
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceGateway(Request $request, int $id)
    {
        try {
            return $this->createGetResponse($request, Device::findOrFail($id)->gateway());
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return "failed";
        }
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> Acquire device's processed data<br>
     * <Function> Retrieve current processed data of the specified device<br>
     *            URL: http://localhost/getDeviceProcessedData/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, Device::findOrFail($id)->processedData())
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceProcessedData(Request $request, int $id)
    {
        try {
            return $this->createGetResponse($request, Device::findOrFail($id)->processedData());
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return "failed";
        }
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> Acquire device's binding information<br>
     * <Function> Retrieve device/sensor bindings information associated with the specified device<br>
     *            URL: http://localhost/getDeviceBindings/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, Device::findOrFail($id)->bindings())
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceBindings(Request $request, int $id)
    {
        try {
            return $this->createGetResponse($request, Device::findOrFail($id)->bindings());
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return "failed";
        }
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> Register a device<br>
     * <Function> Update device's REG_FLAG to 1<br>
     *            URL: http://localhost/registerDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function registerDevice(Request $request)
    {
        try {
            if (!isset(
                $request->DEVICE_CATEGORY,
                $request->DEVICE_NAME
            ) || $request->DEVICE_NAME == "-") {
                //Insert Audit Logs
                $ip = $request->ip();
                $username = auth()->user();
                $host = $username->USERNAME;
                $module = 'Device Management';
                $instruction = 'Device Registration:Malformed Syntax';
                $this->auditLogs($ip, $host, $module, $instruction);
                return "failed : malformed syntax";
            }
            $device = Device::findOrFail($request->DEVICE_ID);
            if ($device->REG_FLAG == 1) {
                // Throw Error
                throw new \Exception("0:Entity is already registered", 210030001);
            } else {
                $device->FLOOR_ID = $request->FLOOR_ID;
                $device->ROOM_ID = $request->ROOM_ID;
                $device->GATEWAY_ID = $request->GATEWAY_ID;
                $device->DEVICE_NAME = $request->DEVICE_NAME;
                $device->DEVICE_CATEGORY = $request->DEVICE_CATEGORY;
                $device->DATA = $request->DEVICE_DATA;
                $device->REG_FLAG = 1;
                $device->ONLINE_FLAG = 1;
                $device->save();
                //Insert Audit Logs
                $ip = $request->ip();
                $username = auth()->user();
                $host = $username->USERNAME;
                $module = 'Device Management';
                $instruction = 'Updated a Device to be Registered';
                $this->auditLogs($ip, $host, $module, $instruction);
            }
            return "success";
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> Update a device<br>
     * <Function> Update a device information<br>
     *            URL: http://localhost/updateDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" or "exist"
     * @throws Throwable When an exception occurs in this process
     */
    public function updateDevice(Request $request)
    {
        try {
            //Check Device Names
            $deviceName = Device::where('DEVICE_NAME', $request->DEVICE_NAME)
                ->get();
            $device = Device::findOrFail($request->DEVICE_ID);
            if (
                count($deviceName) > 0 &&
                $request->DEVICE_CATEGORY == $deviceName[0]->DEVICE_CATEGORY &&
                $deviceName[0]->DEVICE_MAP_NAME == $request->DEVICE_MAP_NAME
            ) {
                return 'exist';
            } else {
                $device->DEVICE_NAME = $request->DEVICE_NAME ?
                    $request->DEVICE_NAME : $device->DEVICE_NAME;
                $device->DEVICE_CATEGORY = $request->DEVICE_CATEGORY ?
                    $request->DEVICE_CATEGORY : '0';
                $device->DATA = $request->DEVICE_DATA ?
                    $request->DEVICE_DATA : $device->DATA;
                $device->DEVICE_MAP_NAME = $request->DEVICE_MAP_NAME ?
                    $request->DEVICE_MAP_NAME : $device->DEVICE_MAP_NAME;
                $device->save();
                //Insert Audit Logs
                $ip = $request->ip();
                $username = auth()->user();
                $host = $username->USERNAME;
                $module = 'Device Management';
                $instruction = 'Updated the Details of a Device';
                $this->auditLogs($ip, $host, $module, $instruction);
                return 'success';
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (15.0)
     *
     * <Processing name> Delete a device<br>
     * <Function> Request to MC to delete a specific device<br>
     *            URL: http://localhost/deleteDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteDevice(Request $request)
    {
        try {
            $device = Device::findOrFail($request->DEVICE_ID);
            //Delete Plotted Device
            $this->deleteDevicePlot($device);
            //Send Delete Device command to MC
            // $remote_ip = $device->gateway->GATEWAY_IP;
            // $remote_port = env("PORT_GATEWAY");
            //Delete to MC
            // $data = '{"mode":"deleteDevice","device_id":"' . $device->DEVICE_SERIAL_NO . '"}';
            // $message = $this->encryptMessage($data);
            // $sRet = $this->sendToSocket($remote_ip,$remote_port,$message);
            // $retArr = json_decode($sRet,true);
            $this->deleteAllDeviceRelation($device);
            $device->delete();
            //Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Device Management';
            $instruction = 'Updated a Device to be Deleted';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> Block a device<br>
     * <Function> Update device's REG_FLAG to 4<br>
     *            URL: http://localhost/blockDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function blockDevice(Request $request)
    {
        try {
            $device = Device::findOrFail($request->DEVICE_ID);
            $device->REG_FLAG = 4;
            $device->save();
            // Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Device Management';
            $instruction = 'Updated a Device to be Blocked';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    // - [Task023]
    // /**
    //  * <Layer number> (17.0)
    //  *
    //  * <Processing name> Unblock a device<br>
    //  * <Function> Update device's REG_FLAG to 0<br>
    //  *            URL: http://localhost/unblockDevice<br>
    //  *            METHOD: POST
    //  *
    //  * @param Request $request
    //  * @return string "success"
    //  * @throws Throwable When an exception occurs in this process
    //  */
    // public function unblockDevice(Request $request)
    // {
    //     try{
    //         $device = Device::findOrFail($request->DEVICE_ID);
    //         $device->REG_FLAG = 0;
    //         $device->save();
    //         // Insert Audit Logs
    //         $ip = $request->ip();
    //         $username = auth()->user();
    //         $host = $username->USERNAME;
    //         $module = 'Device Management';
    //         $instruction = 'Update a Device to Unregistered';
    //         $this->auditLogs($ip,$host,$module,$instruction);
    //         return 'success';
    //     }catch(\Throwable $e){
    //         // Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $uri = $request->route()->uri();
    //         $content = $uri . " : " . $e->getMessage();
    //         $ip = $request->ip();
    //         $username = auth()->user()->USERNAME;
    //         $this->storeLogs($type,$instructionType,$content,$ip,$username);
    //         return $e->getMessage();
    //     }
    // }
    // - [Task023]

    // - [Task023]
    // /**
    //  * <Layer number> (18.0)
    //  *
    //  * <Processing name> Create new device<br>
    //  * <Function> Create new device entry in database (API)<br>
    //  *            URL: http://localhost/createDevice<br>
    //  *            METHOD: POST
    //  *
    //  * @param Request $request
    //  * @return object $device
    //  * @throws Throwable When an exception occurs in this process
    //  */
    // public function createDevice(Request $request)
    // {
    //     $message = [
    //         'content' => $request->MESSAGE,
    //         'iv' => $request->IV
    //     ];
    //     $decrypted = $this->decryptMessage($message);
    //     $decryptedArray = json_decode($decrypted,true);
    //     $gateway = Gateway::where('GATEWAY_IP', $request->GATEWAY_IP)
    //         ->select('GATEWAY_ID', 'FLOOR_ID','ROOM_ID','REG_FLAG','MANUFACTURER_ID')
    //         ->first();
    //     $device = Device::where('DEVICE_SERIAL_NO', $decryptedArray['device_id'])
    //                     ->first();
    //     try {
    //         $processedData = new ProcessedData();
    //         $processedData->DEVICE_ID = 999;
    //         $processedData->DATA =  array_merge($message,json_decode($gateway,true));
    //         $processedData->SEND_FLAG = 0;
    //         $processedData->save();
    //         if (!$gateway) {
    //             return "Gateway not found";
    //         }
    //         elseif ($gateway->REG_FLAG == 0) {
    //             return "Gateway not yet registered";
    //         }
    //         if (!$device) {
    //             $device = new Device();
    //             $device->FLOOR_ID = $gateway->FLOOR_ID;
    //             $device->ROOM_ID = $gateway->ROOM_ID;
    //             $device->GATEWAY_ID = $gateway->GATEWAY_ID;
    //             $device->MANUFACTURER_ID = $gateway->MANUFACTURER_ID;
    //             $device->DEVICE_SERIAL_NO = $decryptedArray['device_id'];
    //             $device->DEVICE_TYPE = $this->convertDeviceType($decryptedArray['device_type']);
    //             $device->DATA = $this->convertDeviceData($device->DEVICE_TYPE,$decryptedArray['device_data'],'newDeviceData');
    //             $device->REG_FLAG = 0;
    //             $device->ONLINE_FLAG = 0;
    //             $device->save();
    //             event(new NewDeviceEvent($device));
    //             return $device;
    //         }else{
    //             //Make Device Online
    //             //Trigger Event Here Change Column online status
    //             $processedData = new ProcessedData();
    //             $processedData->DEVICE_ID = 333;
    //             $processedData->DATA = '{"ID":"' . $device->DEVICE_SERIAL_NO .'","DEVICE_TYPE":"' . $device->DEVICE_TYPE .'"}';
    //             $processedData->SEND_FLAG = 1;
    //             $processedData->save();
    //             $device->ONLINE_FLAG = 1;
    //             $device->save();
    //             if ($device->REG_FLAG == 9) {
    //                 $device->REG_FLAG = 0;
    //                 $device->save();
    //                 event(new NewDeviceEvent($device));
    //                 return $device;
    //             }
    //             elseif ($device->REG_FLAG == 0) {
    //                 event(new NewDeviceEvent($device));
    //                 return $device;
    //             }
    //         }
    //     }catch(\Throwable $e){
    //         //Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $content = $request->URL . " : " . $e->getMessage();
    //         $ip = $request->GATEWAY_IP;
    //         $username = $decryptedArray['device_id'];
    //         $this->storeLogs($type,$instructionType,$content,$ip,$username);
    //         return $e->getMessage();
    //     }
    // }
    // - [Task023]

    /**
     * <Layer number> (19.0)
     *
     * <Processing name>  Get devices with bindings<br>
     * <Function> Retrieve all devices with registered bindings<br>
     *            URL: http://localhost/getDevicesWithBindings<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @throws Throwable When an exception occurs in this process
     */
    public function getDevicesWithBindings(Request $request)
    {
        try {
            //return $request['FLOOR_ID'];
            $test = $this->createGetResponse(
                $request,
                Device::where('FLOOR_ID', $request['FLOOR_ID'])
                    ->where('ROOM_ID', $request['ROOM_ID'])
                    ->with(
                        'bindings.bindingList',
                        'bindings.targetDevice:DEVICE_ID,DEVICE_TYPE,DEVICE_NAME'
                    )
                    ->has('bindings')
                    // 9/23/2020 Update whereHas to orWhereHas
                    ->whereHas('bindings.bindingList', function ($query) use ($request) {
                        $query->where('TARGET_DEVICE_CATEGORY', $request->BINDING_CATEGORY);
                        // 9/23/2020 Added
                    })
                    ->orWhereHas('bindings', function ($query) use ($request) {
                        $query->where('BINDING_LIST_ID', 0);
                    })

            );
            return $test;
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (20.0)
     *
     * <Processing name> Get a device with its binding list<br>
     * <Function> Retrieve all binding list associated with the device<br>
     *            URL: http://localhost/getDeviceBindingList/:id<br>
     *            METHOD: GET
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, BindingList::where('SOURCE_DEVICE_TYPE', $device->DEVICE_TYPE))
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceBindingList(Request $request, int $id)
    {
        try {
            $device = Device::select('DEVICE_TYPE')->findOrFail($id);
            return $this->createGetResponse($request, BindingList::where('SOURCE_DEVICE_TYPE', $device->DEVICE_TYPE));
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $content = $request->URL . " : " . $e->getMessage();
            $ip = $request->GATEWAY_IP;
            $username = "-";
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return "failed";
        }
    }

    /**
     * <Layer number> (21.0)
     *
     * <Processing name> Get device binding list conditions<br>
     * <Function> Retrieve all binding list condition for this device<br>
     *            URL: http://localhost/getDeviceBindingListCondition/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $arr
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceBindingListCondition(Request $request, int $id)
    {
        try {
            $arr = [];
            $deviceType = Device::select('DEVICE_TYPE')
                ->findOrFail($id)->DEVICE_TYPE;
            $bindingList = BindingList::where('SOURCE_DEVICE_TYPE', $deviceType)
                ->select('SOURCE_DEVICE_CONDITION')
                ->groupBy('SOURCE_DEVICE_CONDITION')
                ->get();
            $bindingList = [["DATA" => $bindingList]];
            foreach ($bindingList as $key => $obj) {
                if ($key == 0) {
                    $firstkey = $obj['DATA'][0]['SOURCE_DEVICE_CONDITION'];
                }
                $bindingList[$key] = array_push($arr, ["SELECTED" => $firstkey, "DATA" => $obj['DATA']]);;
            }
            return $arr;
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (22.0)
     *
     * <Processing name> Get device's device binding list<br>
     * <Function> Retrieve all device infomation bound with the specified device<br>
     *            URL: http://localhost/getDeviceBindingListDevices/:id/:condition/:devicetypeid<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @param string $condition
     * @param int $devicetypeid
     * @return object $itemCollection
     */
    public function getDeviceBindingListDevices(Request $request, int $id, string $condition, int $devicetypeid)
    {
        try {
            $sourceDevice = Device::select('DEVICE_ID', 'GATEWAY_ID', 'DEVICE_TYPE')
                ->findOrFail($id);
            $bindingList = BindingList::where(
                'SOURCE_DEVICE_TYPE',
                $sourceDevice->DEVICE_TYPE
            )     //Collect all Binding List based
                // ->where('SOURCE_DEVICE_CONDITION', $condition)
                ->where('TARGET_DEVICE_CATEGORY', $devicetypeid)
                ->select(
                    'BINDING_LIST_ID',
                    'TARGET_DEVICE_TYPE',
                    'TARGET_DEVICE_CONDITION',
                    'SOURCE_DEVICE_TYPE',
                    'SOURCE_DEVICE_CONDITION',
                    'TARGET_DEVICE_CATEGORY'
                )
                ->get();
            $devices = [];
            foreach ($bindingList as $list) {
                $targetDevice = Device::where('DEVICE_TYPE', $list->TARGET_DEVICE_TYPE)
                    ->where('ROOM_ID', $request->targetRoomId)
                    ->where('REG_FLAG', 1)
                    ->select(
                        'DEVICE_ID AS TARGET_DEVICE_ID',
                        'DEVICE_TYPE',
                        'DEVICE_NAME',
                        'DATA'
                    )
                    ->first();
                if ($targetDevice != null) {
                    //Function : if exist, disable outisde
                }
                //Check if this binding already exist
                $binding = $targetDevice ? Binding::where(
                    'SOURCE_DEVICE_ID',
                    $sourceDevice->DEVICE_ID
                )
                    ->where('TARGET_DEVICE_ID', $targetDevice->TARGET_DEVICE_ID)
                    ->where('BINDING_LIST_ID', $list->BINDING_LIST_ID)
                    ->first()
                    : null;
                $arr = [];
                $arr['binding_list'] = $list;
                $arr['target_device'] = $targetDevice ? $targetDevice : null;
                $arr['binding'] = $binding ? $binding : null;
                if ($arr['target_device']) {
                    array_push($devices, $arr);
                }
            }
            $itemCollection = collect($devices);
            return $itemCollection;
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (23.0)
     *
     * <Processing name> Retrieve all binding list associated with the many device<br>
     * <Function> Retrieve all bound devices according to the specified device type<br>
     *            URL: http://localhost/getMultiDeviceBindingListDevices/:id/:devicetype/:condition/:devicetypeid<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @param int $device_type
     * @param int $condition
     * @param int $devicetypeid
     * @return object $itemCollection
     * @throws Throwable When an exception occurs in this process
     */
    public function getMultiDeviceBindingListDevices(Request $request, int $id, string $device_type,  string $condition, int $devicetypeid)
    {
        try {
            $sourceDevice = Device::select('DEVICE_ID', 'GATEWAY_ID', 'DEVICE_TYPE')->findOrFail($id);
            $bindingList = BindingList::where('SOURCE_DEVICE_TYPE', $device_type)
                ->where('SOURCE_DEVICE_CONDITION', $condition)
                ->where('TARGET_DEVICE_CATEGORY', $devicetypeid)
                ->select(
                    'BINDING_LIST_ID',
                    'TARGET_DEVICE_TYPE',
                    'TARGET_DEVICE_CONDITION',
                    'SOURCE_DEVICE_TYPE',
                    'SOURCE_DEVICE_CONDITION'
                )
                ->get();
            $devices = [];
            foreach ($bindingList as $list) {
                $targetDevice = Device::where('DEVICE_TYPE', $list->TARGET_DEVICE_TYPE)
                    ->where('GATEWAY_ID', $sourceDevice->GATEWAY_ID)
                    ->where('REG_FLAG', 1)
                    ->select('DEVICE_ID', 'DEVICE_TYPE', 'DEVICE_NAME')
                    ->first();
                $binding = $targetDevice ? Binding::where('SOURCE_DEVICE_ID', $sourceDevice->DEVICE_ID)
                    ->where('TARGET_DEVICE_ID', $targetDevice->DEVICE_ID)
                    ->where('BINDING_LIST_ID', $list->BINDING_LIST_ID)
                    ->first() : null;
                $arr = [];
                $arr['binding_list'] = $list;
                $arr['target_device'] = $targetDevice ? $targetDevice : null;
                $arr['binding'] = $binding ? $binding : null;
                if ($arr['target_device']) {
                    array_push($devices, $arr);
                }
            }
            $itemCollection = collect($devices);
            return $itemCollection;
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    // - [Task023]
    //   /**
    //    * <Layer number> (24.0)
    //    *
    //    * <Processing name> Delete a device from MC<br>
    //    * <Function> Request deletion of a device to MC<br>
    //    *            URL: http://localhost/deleteDeviceFromMC<br>
    //    *            METHOD: POST
    //    * @param Request $request
    //    * @return string "Device Not Deleted"
    //    * @throws Throwable When an exception occurs in this process
    //    */
    //     public function deleteDeviceFromMC(Request $request)
    //     {
    //         $message = [
    //             'content' => $request->MESSAGE,
    //             'iv' => $request->IV
    //         ];
    //         $decrypted = $this->decryptMessage($message);
    //         $decryptedArray = json_decode($decrypted,true);
    //         $device = Device::where('DEVICE_SERIAL_NO', $decryptedArray['device_id'])
    //                         ->first();
    //         try{
    //             if($device){
    //                 $this->deleteAllDeviceRelation($device);
    //                 $device->delete();
    //                 //Insert Audit Logs
    //                 $ip = $request->ip();
    //                 $username = auth()->user();
    //                 $host = $username->USERNAME;
    //                 $module = 'Device Management';
    //                 $instruction = 'Deleted a Device From MC';
    //                 $this->auditLogs($ip,$host,$module,$instruction);
    //                 return "success";
    //             }else{
    //                 return "Device Not Deleted";
    //             }
    //         }catch(\Throwable $e){
    //             //Insert System Logs
    //             $type='3';
    //             $instructionType = 'System Error';
    //             $content = $request->URL . " : " . $e->getMessage();
    //             $ip = $request->GATEWAY_IP;
    //             $username = $decryptedArray['device_id'];
    //             $this->storeLogs($type,$instructionType,$content,$ip,$username);
    //             return $e->getMessage();
    //         }
    //     }
    // - [Task023]

    // - [Task023]
    // /**
    //  * <Layer number> (25.0)
    //  *
    //  * <Processing name> Set device status to offline<br>
    //  * <Function> Force offline a device through MC<br>
    //  *            URL: http://localhost/offlineDevice<br>
    //  *            METHOD: POST
    //  *            Note: If a device is manually deleted from the gateway/MC,
    //  *                  it should not be automatically deleted from the system's
    //  *                  database, rather, a notification should be made that a
    //  *                  device is forcefully deleted from the system
    //  *
    //  * @param Request $request
    //  * @return string "Device Not Deleted" or "here"
    //  * @throws Throwable When an exception occurs in this process
    //  */
    // public function offlineDevice(Request $request)
    // {
    //     $message = [
    //         'content' => $request->MESSAGE,
    //         'iv' => $request->IV
    //     ];
    //     $decrypted = $this->decryptMessage($message);
    //     $decryptedArray = json_decode($decrypted,true);
    //     $device = Device::where('DEVICE_SERIAL_NO', $decryptedArray['device_id'])
    //                     ->first();
    //     try{
    //         if($device){
    //             //Trigger Event Here Change Column online status
    //             $processedData = new ProcessedData();
    //             $processedData->DEVICE_ID = 333;
    //             $processedData->DATA = '{"ID":"' . $device->DEVICE_SERIAL_NO
    //                 .'","DEVICE_TYPE":"' . $device->DEVICE_TYPE .'"}';
    //             $processedData->SEND_FLAG = 0;
    //             $processedData->save();
    //             $device->ONLINE_FLAG = 0;
    //             $device->save();
    //             $this->insertDevicetoNotification($device);

    //             //Insert System Logs
    //             $type='4';
    //             $instructionType = 'Automatic';
    //             $content = "Device Offline " . $device->DEVICE_ID . "-" . $device->DEVICE_TYPE;
    //             $ip = $request->GATEWAY_IP;
    //             $username = $decryptedArray['device_id'];
    //             $this->storeLogs($type,$instructionType,$content, $ip,$username);
    //             return 'here';
    //         }else{
    //             return "Device Not Deleted";
    //         }
    //     }catch(\Throwable $e){
    //         //Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $content = $request->URL . " : " . $e->getMessage();
    //         $ip = $request->GATEWAY_IP;
    //         $username = $decryptedArray['device_id'];
    //         $this->storeLogs($type,$instructionType,$content,$ip,$username);
    //         return $e->getMessage();
    //     }
    // }
    // - [Task023]

    /**
     * <Layer number> (26.0)
     *
     * <Processing name> Update device status<br>
     * <Function> Change status of Device based on status of gateway<br>
     *            URL: <br>
     *            METHOD: POST
     *
     * @throws Throwable When an exception occurs in this process
     */

    // - [Task023]
    // public function onlineDeviceByGateway()
    // {
    //     try{
    //         $gateways = Gateway::where([["ONLINE_FLAG","=","0"],["MANUFACTURER_ID","=","1"]])->get();
    //         foreach ($gateways as $key => $gateway) {
    //             $devices = Device::where("GATEWAY_ID",$gateway->GATEWAY_ID)->get();
    //             foreach ($devices as $keys => $device) {
    //                 $device->ONLINE_FLAG = 0;
    //                 $device->save();
    //             }
    //             event(new OnlineDeviceEvent($devices));
    //         }
    //     }catch(\Throwable $e){
    //         //Insert System Logs
    //         $type='3';
    //         $instructionType = 'System Error';
    //         $request = "onlineDeviceByGateway";
    //         $uri = $request;
    //         $content = $uri . " : " . $e->getMessage();
    //         $ip = "-";
    //         $username = "-";
    //         $this->storeLogs($type,$instructionType,$content,$ip,$username);
    //         return $e->getMessage();
    //     }
    // }
    // - [Task023]

    public function deleteAllDeviceRelation(object $devices)
    {

        $deviceList = (array) new \stdClass;
        //Check if device is single or in a group
        if (isset($devices->DEVICE_ID)) {
            array_push($deviceList, $devices);
        } else {
            $deviceList = $devices;
        }
        foreach ($deviceList as $device) {
            $device->bindings()->delete();               //Delete Binding
            $device->deviceBindings()->delete();         //Delete Binding for Target
            $device->bindingAlerts()->delete();          //Delete Binding Alert
            $device->bindingCameraSource()->delete();    //Delete Binding Camera Source
            $device->bindingCamera()->delete();          //Delete Binding Camera for Target
            $device->processedData()->delete();          //Delete Processed Data
            $device->irLearningList()->delete();         //Delete LearningList
        }
    }

    /**
     * <Layer number> (27.0)
     *
     * <Processing name> CovertPayload<br>
     * <Function> Covert the Payload from the API to HEX<br>
     *            URL: <br>
     *            METHOD: GET
     *
     * @throws Throwable When an exception occurs in this process
     */
    public function convertPayload(Request $request, string $serial_arr, string $ipGW)
    {
        try {
            // Get Token
            $getAccessToken = $this->getAccessTokenForNetvoxGateway($ipGW);
            $recieveData = Http::withToken($getAccessToken)
                ->get("http://$ipGW/application/spn/rx_data/$serial_arr?page=1&page_size=1")
                ->json();

            // Get the data of device
            $load = $recieveData['list'];

            //If there is a payload
            if (isset($load[0]['payload'])) {

                //Payload of device
                $payloadDev = $load[0]['payload'];

                // Convert $payloadDev to HEX
                $payload = bin2hex(base64_decode($payloadDev));

                // split $payload by 2
                $parsedPayload = str_split($payload, 2);

                // Get the device code column in $parsedPayload
                $devCode = $parsedPayload[1];

                // Get the device type of $devCode
                $deviceType = $this->convertDeviceType($devCode, 6);
                return $deviceType;
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * @author TP Uddin
     * @uses for testing
     * @since 2021.05.05
     */
    public function getNatureRemoAppliances()
    {
        $response = Http::withToken(env("NATURE_REMO_TOKEN"))
            ->get('https://api.nature.global/1/appliances');

        return $response->json();
    }

    /**
     * @author TP Uddin
     * @uses for testing
     * @since 2021.05.06
     */
    public function getNatureRemoApplianceSignals(Request $request)
    {
        $response = Http::withToken(env("NATURE_REMO_TOKEN"))
            ->get("https://api.nature.global/1/appliances/$request->APPLIANCE_ID/signals");

        return $response->json();
    }

    /**
     * @author TP Uddin
     * @uses for testing
     * @since 2021.05.10
     */
    public function sendNatureRemoSignal(Request $request)
    {
        $applianceID = "6b422c89-c852-457f-9153-3cc5b69488b8";
        $buttonName = $request->BUTTON;
        $nickName = $request->NICKNAME;
        $response = Http::withToken(env("NATURE_REMO_TOKEN"))
            ->asForm()
            ->post(
                "https://api.nature.global/1/appliances/$applianceID",
                [
                    "button" => $buttonName,
                    "nickname" => $nickName
                ]
            );
        return $response->json();
    }
}
