<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\NewDeviceDataEvent;
use App\Events\NewTempDataEvent;
use App\Models\Device;
use App\Models\Gateway;
use App\Models\ProcessedData;
use App\Models\SystemModule;
use App\Models\User;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * <Class Name> DashboardController
 *
 * <Function Name> Dashboard Processing<br>
 * Create : 2018.10.24 TP Raymond<br>
 * Update : 2020.05.13 TP Uddin         Implement coding standard for PHP7
 *          2020.05.20 TP Uddin         Modify URL and method names according to the URL List<br>
 *
 * <Overview> Retrieve status of devices and gateways and display their data in the dashboard graphically.
 *
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class DashboardController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getGateway                       (1.0) Retrieve the number of online and offline gateways
    // getGatewayStatus                 (2.0) Get assigned floor, room and IP for offline and online gateways
    // getDeviceStatus                  (3.0) Get assigned floor and room for offline and online gateways
    // getDevices                       (4.0) Retrieve the number of online and offline devices
    // getStatus                        (5.0) Get the number of all gateways and devices, online and offline gateways and devices
    // getModules                       (6.0) Retrieve the modules with user's privilege
    // getUniqueDevices                 (7.0) Retrieve unique devices in a specific floor or room
    // getProcessedData                 (8.0) Retrieve Retrieve processed data of the devices in a specific floor or rooom

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Count gateways according to their status<br>
     * <Function> Retrieve the number of online and offline gateways<br>
     *            URL: http://localhost/getGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return array[]|string $gateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getGateway(Request $request)
    {
        try {
            $onlineGateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2, 4])
                ->where('ONLINE_FLAG', 1)
                ->get()
                ->count();
            $offlineGateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2, 4])
                ->where('ONLINE_FLAG', 0)
                ->get()
                ->count();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        $gateway = [
            [
                'status' => 'Online Gateway/s',
                'count' => $onlineGateways,
                'color' => '#28a745'
            ],
            [
                'status' => 'Offline Gateway/s',
                'count' => $offlineGateways,
                'color' => '#6c757d'
            ]
        ];
        return $gateway;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get gateways information<br>
     * <Function> Get assigned floor, room and IP for offline and online gateways<br>
     *            URL: http://localhost/getGatewayStatus<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return array|string $list
     * @throws Throwable When an exception occurs in this process
     */
    public function getGatewayStatus(Request $request)
    {
        $list = [];
        try {
            $onlineGateways = Gateway::with('room')
                ->with('floor')
                // Camera Gateway has no floor so it will return error if added
                ->whereIn('MANUFACTURER_ID', [1, 2])
                ->where('ONLINE_FLAG', 1)
                ->where('REG_FLAG', 1)
                ->get();
            $onlineCameraGateways = Gateway::where('MANUFACTURER_ID', 4)
                ->where('ONLINE_FLAG', 1)
                ->where('REG_FLAG', 1)
                ->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        foreach ($onlineGateways as $onlineGateway) {
            $json = [
                'onlineGatewayIP' => $onlineGateway->GATEWAY_IP,
                'onlineFloor' => $onlineGateway->floor->FLOOR_NAME,
                'onlineRoom' => $onlineGateway['room']['ROOM_NAME'],
            ];
            array_push($list, $json);
        }
        foreach ($onlineCameraGateways as $onlineCameraGateway) {
            $json = [
                'onlineGatewayIP' => $onlineCameraGateway->GATEWAY_IP,
                'onlineFloor' => 'All',
                'onlineRoom' => 'All'
            ];
            array_push($list, $json);
        }
        try {
            $offlineGateways = Gateway::with('room')
                ->with('floor')
                // Camera Gateway has no floor so it will return error if added
                ->whereIn('MANUFACTURER_ID', [1, 2])
                ->where('ONLINE_FLAG', 0)
                ->where('REG_FLAG', 1)
                ->get();
            $offlineCameraGateways = Gateway::where('MANUFACTURER_ID', 4)
                ->where('ONLINE_FLAG', 0)
                ->where('REG_FLAG', 1)
                ->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        foreach ($offlineGateways as $offlineGateway) {
            $json = [
                'offlineGatewayIP' => $offlineGateway->GATEWAY_IP,
                'offlineFloor' => $offlineGateway->floor->FLOOR_NAME,
                'offlineRoom' => $offlineGateway['room']['ROOM_NAME'],
            ];
            array_push($list, $json);
        }
        foreach ($offlineCameraGateways as $offlineCameraGateway) {
            $json = [
                'offlineGatewayIP' => $offlineCameraGateway->GATEWAY_IP,
                'offlineFloor' => 'All',
                'offlineRoom' => 'All'
            ];
            array_push($list, $json);
        }
        return $list;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get devices information<br>
     * <Function> Get assigned floor and room for offline and online gateways<br>
     *            URL: http://localhost/getDeviceStatus<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return array|string $list
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeviceStatus(Request $request)
    {
        $list = [];
        try {
            $onlineDevices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                ->where('ONLINE_FLAG', 1)
                ->where('REG_FLAG', 1)
                ->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        // Capitalize each word and replace "_" to space
        foreach ($onlineDevices as $onlineDevice) {
            $json = [
                'onlineDevice' => ucwords(str_replace(
                    "_",
                    " ",
                    $onlineDevice->device
                )),
                'count' => $onlineDevice->count
            ];
            array_push($list, $json);
        }
        try {
            $offlineDevices = Device::with('room')
                ->with('floor')
                ->whereIn('MANUFACTURER_ID', [1, 4])
                ->where('ONLINE_FLAG', 0)
                ->where('REG_FLAG', 1)
                ->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        // Capitalize each word and replace "_" to space
        foreach ($offlineDevices as $offlineDevice) {
            $json = [
                'offlineDevice' => ucwords(str_replace(
                    "_",
                    " ",
                    $offlineDevice->DEVICE_TYPE
                )),
                'offlineFloor' => $offlineDevice->floor->FLOOR_NAME,
                'offlineRoom' => $offlineDevice->room->ROOM_NAME,
            ];
            array_push($list, $json);
        }
        return $list;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Count devices according to their status<br>
     * <Function> Retrieve the number of online and offline devices<br>
     *            URL: http://localhost/getDevices<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return array[]|string $devices
     * @throws Throwable When an exception occurs in this process
     */
    public function getDevices(Request $request)
    {
        try {
            $onlineDevices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                ->where('ONLINE_FLAG', 1)
                ->get()
                ->count();
            $offlineDevices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                ->where('ONLINE_FLAG', 0)
                ->get()
                ->count();
            $devices = [
                [
                    'status' => 'Online Device/s',
                    'count' => $onlineDevices,
                    'color' => '#28a745'
                ],
                [
                    'status' => 'Offline Device/s',
                    'count' => $offlineDevices,
                    'color' => '#cc0000'
                ]
            ];
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        return $devices;
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Count the number of gateways and devices<br>
     * <Function> Get the number of all gateways and devices, online and offline gateways and devices<br>
     *            URL: http://localhost/getStatus>br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return array|string $list
     * @throws Throwable When an exception occurs in this process
     */
    public function getStatus(Request $request)
    {
        $list = [];
        try {
            $gateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2, 4])
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
            $onlineGateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2, 4])
                ->where('ONLINE_FLAG', 1)
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
            $offlineGateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2, 4])
                ->where('ONLINE_FLAG', 0)
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
            $devices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
            $onlineDevices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                ->where('ONLINE_FLAG', 1)
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
            $offlineDevices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                ->where('ONLINE_FLAG', 0)
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        $json = [
            'totalGateway' => $gateways,
            'onlineGateway' => $onlineGateways,
            'offlineGateway' => $offlineGateways,
            'totalDevices' => $devices,
            'onlineDevices' => $onlineDevices,
            'offlineDevices' => $offlineDevices,
        ];
        array_push($list, $json);
        return $list;
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Get specific modules<br>
     * <Function> Retrieve the modules with user's privilege<br>
     *            URL: http://localhost/getModules<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string $res
     * @throws Throwable When an exception occurs in this process
     */
    public function getModules()
    {
        // $ip = $request->ip();
        $ip = request()->ip();
        $userID = Auth::id();
        $users = User::where('USER_ID', $userID)->with('authModules')->first();
        $res['USERNAME'] = $users->USERNAME;
        $userModules = $users->authModules;
        foreach ($userModules as $userModule) {
            $userModuleID = $userModule->MODULE_ID;
            try {
                $modules = SystemModule::where('MODULE_ID', $userModuleID)->get();
            } catch (\Throwable $e) {
                //Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $uri = request()->route()->uri();
                $content = $uri . " : " . $e->getMessage();
                $username = auth()->user()->USERNAME;
                $this->storeLogs($type, $instructionType, $content, $ip, $username);
                return $e->getMessage();
            }
            foreach ($modules as $module) {
                array_push($res, $module->MODULE_NAME);
            }
        }
        return $res;
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Get unique devices<br>
     * <Function> Retrieve unique devices in a specific floor or room<br>
     *            URL: http://localhost/getUniqueDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $arr
     * @throws Throwable When an exception occurs in this process
     */
    public function getUniqueDevices(Request $request)
    {
        $arr = [];
        if (isset($request->FLOOR_ID)) {
            if (isset($request->ROOM_ID)) {
                try {
                    $devices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                        ->where('REG_FLAG', 1)
                        ->where('ROOM_ID', $request->ROOM_ID)
                        ->select(
                            'DEVICE_ID',
                            'DEVICE_TYPE',
                            'DEVICE_NAME',
                            'ONLINE_FLAG',
                            'ROOM_ID',
                            'FLOOR_ID'
                        )
                        ->get();
                } catch (\Throwable $e) {
                    // Insert to new logs
                    $uri = $request->getUri();
                    $this->processError($uri, $e);
                }
            } else {
                try {
                    $devices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                        ->where('REG_FLAG', 1)
                        ->where('FLOOR_ID', $request->FLOOR_ID)
                        ->select(
                            'DEVICE_ID',
                            'DEVICE_TYPE',
                            'DEVICE_NAME',
                            'ONLINE_FLAG',
                            'ROOM_ID',
                            'FLOOR_ID'
                        )
                        ->get();
                } catch (\Throwable $e) {
                    // Insert to new logs
                    $uri = $request->getUri();
                    $this->processError($uri, $e);
                }
            }
        } else {
            try {
                $devices = Device::whereIn('MANUFACTURER_ID', [1, 4])
                    ->where('REG_FLAG', 1)
                    ->select(
                        'DEVICE_ID',
                        'DEVICE_TYPE',
                        'DEVICE_NAME',
                        'ONLINE_FLAG',
                        'ROOM_ID',
                        'FLOOR_ID'
                    )
                    ->get();
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
            }
        }
        foreach ($devices->unique('DEVICE_TYPE') as $device) {
            if ($device->DEVICE_TYPE == 'camera') {
                $apps = Device::find($device->DEVICE_ID)->DATA[0]['Applications'];
                foreach ($apps as $app) {
                    // Display the People Counter not the camera itself
                    if ($app['name'] == 'tvpc') {
                        array_push($arr, [
                            'DEVICE_ID' => $device->DEVICE_ID,
                            'DEVICE_TYPE' => 'people_counter',
                            'DEVICE_NAME' => $device->DEVICE_NAME,
                            'ONLINE_FLAG' => $device->ONLINE_FLAG,
                            'ROOM_ID' => $device->ROOM_ID,
                            'FLOOR_ID' => $device->FLOOR_ID
                        ]);
                    }
                }
            } else {
                array_push($arr, $device);
            }
        }
        return $arr;
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Get processed data of devices<br>
     * <Function> Retrieve Retrieve processed data of the devices in a specific floor or rooom<br>
     *            URL: http://localhost/getProcessedData<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return Object $data
     * @throws Throwable When exception occurs in this process
     */
    public function getProcessedData(Request $request)
    {

        // $floor = $request->FLOOR_ID;
        $room = $request->ROOM_ID;
        // Change 'people_counter' device type to 'camera'
        if ($request->DEVICE_TYPE == 'camera') {
            // $deviceType = 'camera';
            $latestData = ProcessedData::where('DEVICE_ID', $request->DEVICE_ID)
                ->latest()
                ->first();
            // Update People Counter data in Dashboard at mounted()
            event(new NewDeviceDataEvent($latestData));
        } elseif ($request->DEVICE_TYPE == 'temp_hum') {
            $latestData = ProcessedData::where('DEVICE_ID', $request->DEVICE_ID)
                ->latest()
                ->first();
            // Update People Counter data in Dashboard at mounted()
            event(new NewTempDataEvent($latestData));
        }
        $deviceType = str_replace(' ', '_', strtolower($request->DEVICE_TYPE));
        $arrID = [];
        //change query to look for device ids with specific device type
        try {
            if ($room != '') {
                $deviceIDs = Device::whereIn('MANUFACTURER_ID', [1, 4])
                    ->where('REG_FLAG', 1)
                    ->where('DEVICE_TYPE', $deviceType)
                    ->where('ROOM_ID', $room)
                    ->select('DEVICE_ID')
                    ->get();
            } else {
                $deviceIDs = Device::whereIn('MANUFACTURER_ID', [1, 4])
                    ->where('REG_FLAG', 1)
                    ->where('DEVICE_TYPE', $deviceType)
                    // ->where('FLOOR_ID', $floor)
                    ->select('DEVICE_ID')
                    ->get();
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        foreach ($deviceIDs as $deviceID) {
            array_push($arrID, $deviceID->DEVICE_ID);
        }
        //changed query to select elements in child table to optimize memory usage
        try {
            $data = ProcessedData::with('device:DEVICE_ID,DEVICE_TYPE,DEVICE_NAME')
                ->whereIn('DEVICE_ID', $arrID)
                ->limit(10000) // must be remove limit in future
                ->get();
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        return $data;
    }
}
