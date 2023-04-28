<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\NewDeviceDataEvent;
use App\Models\Api;
use App\Models\Device;
use App\Models\Gateway;
use App\Models\ProcessedData;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

/**
 * <Class Name> CameraController
 *
 * <Function Name> Camera Management and Processing<br>
 * Create : 2020.09.25 TP Uddin<br>
 * Update : 2020.10.07 TP Uddin     Added sendRequestToPython method
 *          2020.10.07 TP Uddin     Added methods to call ACS APIs
 *          2020.12.14 TP Uddin     Added sendPeopleCounterDataToArchibus method<br>
 *
 * <Overview>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version 1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class CameraController extends Controller
{
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
    // darkFeedAlert                    (11.0) Monitor camera for dark feed
    // sendPeopleCounterDataToArchibus  (12.0) Send People Counter Data To Archibus API every 30 minutes
    // getCamerasWithBindings            (13.0) Get cameras with Bindings.
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Scan all cameras<br>
     * <Function> Get all cameras recognized by the ACS servers<br>
     *            URL: http://localhost/scanAllDevices<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'scan complete'
     */
    public function scanAllCameras(Request $request): string
    {
        $cameraGateways = Gateway::where('MANUFACTURER_ID', 4)
            ->select('GATEWAY_ID', 'GATEWAY_IP', 'MANUFACTURER_ID')
            ->get();
        foreach ($cameraGateways as $cameraGateway) {
            $serverConfigs = $this->getAcsServerConfiguration($cameraGateway->GATEWAY_IP);
            foreach ($serverConfigs['CameraSettings'] as $cameraSettings) {
                $deviceSerial = $cameraSettings['CameraId']['Id'];
                $gatewaySerial = explode('_', $deviceSerial)[1];
                if ($cameraSettings['CameraId']['Id'] == $deviceSerial) {
                    $camSet = $cameraSettings;
                }
            }
            foreach ($serverConfigs['VideoAudioSettings'] as $videoAudioSettings) {
                if ($videoAudioSettings['Id']['Id'] == $deviceSerial) {
                    $vidAudSet = $videoAudioSettings;
                }
            }
            $applications = $this->listCameraApplications($camSet['Address']);
            $data = [];
            array_push($data, [
                "Model" => $camSet['Model'],
                "FirmwareVersion" => $camSet['FirmwareVersion'],
                "Address" => $camSet['Address'],
                "HttpPort" => $camSet['HttpPort'],
                "HostName" => $camSet['HostName'],
                "MacAddress" => $camSet['MacAddress'],
                "HasPtz" => $camSet['HasPtz'],
                "HasAudio" => $vidAudSet['HasAudio'],
                "LiveViewAudio" => $vidAudSet['LiveViewAudio'],
                "RecordingAudio" => $vidAudSet['RecordingAudio'],
                "Applications" => $applications
            ]);
            $cameras = $this->getAcsCameraList($cameraGateway->GATEWAY_IP);
            foreach ($cameras['Cameras'] as $camera) {
                // Check camera gateway status
                if (!Gateway::where('GATEWAY_SERIAL_NO', $gatewaySerial)->first()) {
                    continue;
                }
                // Check if the device is already in the Device Table
                if (Device::where('DEVICE_SERIAL_NO', $deviceSerial)->first()) {
                    continue;
                }
                // Add camera to Device Table as unregistered
                $device = new Device();
                $device->FLOOR_ID = 0;
                $device->ROOM_ID = 0;
                $device->GATEWAY_ID = $cameraGateway->GATEWAY_ID;
                $device->MANUFACTURER_ID = $cameraGateway->MANUFACTURER_ID;
                $device->DEVICE_SERIAL_NO = $deviceSerial;
                $device->DEVICE_TYPE = 'camera';
                $device->DEVICE_CATEGORY = 1;   // sensor
                $device->DATA = $data;
                $device->DEVICE_NAME = null;
                $device->REG_FLAG = 0;
                $device->ONLINE_FLAG = 1;
                $device->save();
            }
        }
        return 'scan complete';
    }

    /**
     * <layer number> (2.0)
     *
     * <Processing name> Register a camera<br>
     * <Function> Set camera's REG_FLAG to 1 and add floor and room designation<br>
     *            URL: http://localhost/registerCamera<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable $e->getMessage() When an exception occurs in this process
     */
    public function registerCamera(Request $request)
    {
        try {
            if (Device::where('DEVICE_NAME', $request->DEVICE_NAME)->get()->count() > 0) {
                return 'name exists';
            } elseif (preg_match("/[^a-zA-Z0-9-_ ]/", $request->DEVICE_NAME)) {
                return 'name invalid';
            } else {
                $device = Device::find($request->DEVICE_ID);
                $device->FLOOR_ID = $request->FLOOR_ID;
                $device->ROOM_ID = $request->ROOM_ID;
                $device->DEVICE_NAME = $request->DEVICE_NAME;
                $device->DEVICE_CATEGORY = $request->DEVICE_CATEGORY;
                $device->REG_FLAG = 1;
                $device->save();
                return 'success';
            }
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = 3;
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
     * <Layer number> (3.0)
     *
     * <Processing name> Update a camera's details<br>
     * <Function> Update camera's DEVICE_NAME and DEVICE_CATEGORY<br>
     *            URL: http://localhost/updateCamera<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success' If all input are valid
     * @return string 'name exists' If provided name already exists (case-insensitive) in the device table
     * @return string 'name invalid' If provided name contains special characters
     * @throws Throwable $e->getMessage() When an exception occurs in this process
     */
    public function updateCamera(Request $request)
    {
        try {
            // Check if no change in device name (case-insensitive e.g. from device_name to DeViCe_NaMe, still no change)
            if (strcasecmp(Device::find($request->DEVICE_ID)->DEVICE_NAME, $request->DEVICE_NAME) == 0) {
                $device = Device::find($request->DEVICE_ID);
                $device->DEVICE_NAME = $request->DEVICE_NAME;
                $device->DEVICE_CATEGORY = $request->DEVICE_CATEGORY;
                $device->save();
                return 'success';
                // Check if the device name already exists (case insensitive) in the Device Table
            } elseif (Device::where('DEVICE_NAME', $request->DEVICE_NAME)->count() > 0) {
                return 'name exists';
                // Check if the device name entered is valid (does not contain )
            } elseif (preg_match("/[^a-zA-Z0-9-_ ]/", $request->DEVICE_NAME)) {
                return 'name invalid';
                // If device name is valid and unique, update device
            } else {
                $device = Device::find($request->DEVICE_ID);
                $device->DEVICE_NAME = $request->DEVICE_NAME;
                $device->DEVICE_CATEGORY = $request->DEVICE_CATEGORY;
                $device->save();
                return 'success';
            }
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = 3;
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
     * <Layer number> (4.0)
     *
     * <Processing name> Delete a camera<br>
     * <Function> Delete a camera in the device table<br>
     *            URL: http://localhost/deleteCamera<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable $e->getMessage When an exception occurs in this process
     */
    public function deleteCamera(Request $request)
    {
        try {
            $device = Device::find($request->DEVICE_ID);
            $device->delete();
            return 'success';
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = 3;
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
     * <Layer number> (5.0)
     *
     * <Processing name> Get unregistered cameras<br>
     * <Function> Get all devices in DB with REG_FLAG of 0 and Manufacturer ID of 4<br>
     *            URL: http://localhost/getUnregisteredCameras<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object[] $cameraArr
     * @throws string 'failed' When an exception occurs in this process
     */
    public function getUnregisteredCameras(Request $request)
    {
        try {
            $cameras = Device::where('MANUFACTURER_ID', 4)
                ->where('REG_FLAG', 0)
                ->with('gateway:GATEWAY_ID,GATEWAY_IP,GATEWAY_NAME')
                ->get();
            $cameraArr = [];
            foreach ($cameras as $camera) {
                array_push($cameraArr, [
                    'DEVICE_ID' => $camera->DEVICE_ID,
                    'FLOOR_ID' => 0,
                    'FLOOR_NAME' => null,
                    'ROOM_ID' => 0,
                    'ROOM_NAME' => null,
                    'GATEWAY_ID' => $camera->GATEWAY_ID,
                    'GATEWAY_NAME' => $camera->gateway->GATEWAY_NAME,
                    'DEVICE_NAME' => $camera->DEVICE_NAME,
                    'DEVICE_TYPE' => $camera->DEVICE_TYPE,
                    'DEVICE_CATEGORY' => $camera->DEVICE_CATEGORY,
                    'DEVICE_SERIAL_NO' => $camera->DEVICE_SERIAL_NO,
                    'DATA' => $camera->DATA,
                    'REG_FLAG' => $camera->REG_FLAG,
                    'ONLINE_FLAG' => $camera->ONLINE_FLAG
                ]);
            }
            return $cameraArr;
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Get registered cameras<br>
     * <Function> Get all devices in DB with REG_FLAG of 1 and Manufacturer ID of 4<br>
     *            URL: http://localhost/getRegisteredCameras<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object[] $cameraArr
     * @throws string 'failed' When an exception occurs in this process
     */
    public function getRegisteredCameras(Request $request)
    {
        try {
            $cameras = Device::where('MANUFACTURER_ID', 4)
                ->where('REG_FLAG', 1)
                ->with('gateway:GATEWAY_ID,GATEWAY_IP,GATEWAY_NAME')
                ->with('floor:FLOOR_ID,FLOOR_NAME')
                ->with('room:ROOM_ID,ROOM_NAME')
                ->get();
            $cameraArr = [];
            foreach ($cameras as $camera) {
                array_push($cameraArr, [
                    'DEVICE_ID' => $camera->DEVICE_ID,
                    'FLOOR_ID' => $camera->floor->FLOOR_ID,
                    'FLOOR_NAME' => $camera->floor->FLOOR_NAME,
                    'ROOM_ID' => $camera->room->ROOM_ID,
                    'ROOM_NAME' => $camera->room->ROOM_NAME,
                    'GATEWAY_ID' => $camera->GATEWAY_ID,
                    'GATEWAY_NAME' => $camera->gateway->GATEWAY_NAME,
                    'DEVICE_NAME' => $camera->DEVICE_NAME,
                    'DEVICE_TYPE' => $camera->DEVICE_TYPE,
                    'DEVICE_CATEGORY' => $camera->DEVICE_CATEGORY,
                    'DEVICE_SERIAL_NO' => $camera->DEVICE_SERIAL_NO,
                    'DATA' => $camera->DATA,
                    'REG_FLAG' => $camera->REG_FLAG,
                    'ONLINE_FLAG' => $camera->ONLINE_FLAG
                ]);
            }
            return $cameraArr;
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Store camera logs<br>
     * <Function> API that receives all the logs sent by ACS servers<br>
     *            URL: http://localhost/api/storeCameraLogs<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return void
     * @throws string 'failed' When an exception occurs in this process
     */
    public function storeCameraLogs(Request $request)
    {
        try {
            $type = $request->data['log']['type'];
            $instructionType = $request->data['log']['instructionType'];
            $content = $request->data['log']['content'];
            $host = '-';

            $gateway = Gateway::where('GATEWAY_SERIAL_NO', $request->data['gateway']['gatewayId'])->first();
            $ip = $gateway->GATEWAY_IP;
            // Check if the log has no device
            if (count($request->data['devices']) == 0) {
                $this->storeLogs($type, $instructionType, $content, $ip, $host);
                foreach ($request->data['triggers'] as $trigger) {
                    $mode = $trigger['name'];
                    $this->processNotification($gateway, $mode);
                }
            } else {
                foreach ($request->data['devices'] as $device) {
                    $camera = Device::where('DEVICE_SERIAL_NO', $device['deviceId'])->first();
                    if ($camera) {
                        $this->storeLogs($type, $instructionType, $content, $ip, $host);
                        foreach ($request->data['triggers'] as $trigger) {
                            $mode = $trigger['name'];
                            $this->processNotification($camera, $mode);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Save current people counter data<br>
     * <Function> Create new entry in ProcessedData Table for People Counter data<br>
     *
     * @return void
     */
    public function postPcData()
    {
        // Get camera information
        $cameras = Device::where('MANUFACTURER_ID', 4)
            ->where('DEVICE_TYPE', 'camera')
            ->select('DEVICE_ID', 'DATA')
            ->get();

        foreach ($cameras as $camera) {

            // Check camera's applications in the DATA Column
            foreach ($camera->DATA[0]['Applications'] as $app) {

                // Check if the camera has People Counter app running
                if ($app['name'] == 'tvpc' && $app['status'] == 'Running') {

                    // Get real time people counter data
                    $peopleData = $this->getPcRealTimeData($camera->DEVICE_ID);

                    // Get latest data
                    $pastData = ProcessedData::where('DEVICE_ID', $camera->DEVICE_ID)
                        ->latest()
                        ->first();

                    // If there is no previous data
                    if (!$pastData) {
                        $yesterdayIn = 0;
                        $yesterdayOut = 0;
                        $peopleIn = $peopleData['in'];
                        $peopleOut = $peopleData['out'];

                        // If previous data is from today,
                        // just copy 'yesterdayIn' and 'yesterdayOut'
                        // then add them to the PC data
                    } elseif ($pastData->CREATED_AT >= date('Y-m-d')) {
                        $yesterdayIn = $pastData->DATA['yesterdayIn'];
                        $yesterdayOut = $pastData->DATA['yesterdayOut'];
                        $peopleIn = $peopleData['in'] + $yesterdayIn;
                        $peopleOut = $peopleData['out'] + $yesterdayOut;

                        // If previous data is not from today
                        // set yesterday In and Out to the previous In and Out
                        // then add them to the PC data
                    } else {
                        $yesterdayIn = $pastData->DATA['peopleIn'];
                        $yesterdayOut = $pastData->DATA['peopleOut'];
                        $peopleIn = $peopleData['in'] + $yesterdayIn;
                        $peopleOut = $peopleData['out'] + $yesterdayOut;
                    }
                    // Make new entry to ProcessedData Table
                    $newData = new ProcessedData();
                    $newData->DEVICE_ID = $camera->DEVICE_ID;
                    $newData->DATA = [
                        'peopleIn' => $peopleIn,
                        'peopleOut' => $peopleOut,
                        'yesterdayIn' => $yesterdayIn,
                        'yesterdayOut' => $yesterdayOut
                    ];
                    $newData->SEND_FLAG = 0;
                    $newData->save();

                    // Trigger frontend
                    event(new NewDeviceDataEvent($newData));
                }
            }
        }
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name>Update counter data at motion detected<br>
     * <Function> Get People Counter data whenever there is a detected motion<br>
     *            URL: http://localhost/api/vmdAlert<br>
     *            METHOD: GET
     *
     * @api
     * @return void
     */
    public function vmdAlert()
    {
        // Wait 5 seconds after the vmd to execute postPcData
        sleep(3);
        $this->postPcData();
        app('App\Http\Controllers\BindingController')->triggerCameraBinding();
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name>Reset People Counter data<br>
     * <Function> Create new entry in ProcessedData Table with 0 data<br>
     *
     * @return void
     */
    private function resetCountData(int $id)
    {
        // Clear People Counting Data
        $this->clearPcData($id);

        // Create new ProcessedData with data of 0
        $resetData = new ProcessedData();
        $resetData->DEVICE_ID = $id;
        $resetData->DATA = [
            'peopleIn' => 0,
            'peopleOut' => 0,
            'yesterdayIn' => 0,
            'yesterdayOut' => 0
        ];
        $resetData->SEND_FLAG = 0;
        $resetData->save();
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> Monitor camera for dark feed<br>
     * <Function> Reset counter data if dark feed last for 5 minutes<br>
     *            URL: http://localhost/api/darkFeedAlert<br>
     *            METHOD: GET
     *
     * @param Request $request Contains the MAC Address of the camera
     * @return void
     */
    public function darkFeedAlert(Request $request)
    {
        // Get all cameras
        $cameras = Device::where('MANUFACTURER_ID', 4)
            ->where('DEVICE_TYPE', 'camera')
            ->get();

        foreach ($cameras as $camera) {
            if ($camera->DATA[0]['MacAddress'] == $request->MacAddress) {
                $response = $this->resetCountData($camera->DEVICE_ID);
            }
        }
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> Send People Counter Data To Archibus<br>
     * <Function> Send People Counter Data to Archibus API every 30 minutes<br>
     *            URL: http://localhost/api/sendPeopleCounterDataToArchibus<br>
     *            METHOD: POST
     *
     * @return array json_decode($response, true)
     */
    public function sendPeopleCounterDataToArchibus()
    {
        $apiInfo = Api::where('API_NAME', 'archibus_people_counter')
            ->first();

        // Get access token
        $access_token = $this->getAccessToken($apiInfo->API_ID);

        // Content-Type of Archibus API
        $contentType = "application/json";

        // API header
        $header = [
            "Authorization: Bearer {$access_token}",
            "Content-Type: $contentType"
        ];

        // API body
        $body = [];

        // Get all cameras that has people counter feature
        $cameras = Device::where('MANUFACTURER_ID', 4)
            ->where('DEVICE_TYPE', 'camera')
            ->where('ONLINE_FLAG', 1)
            ->where('REG_FLAG', 1)
            ->get();

        foreach ($cameras as $camera) {
            foreach ($camera->DATA[0]['Applications'] as $app) {
                if (($app['name'] == 'tvpc') && ($app['status'] == 'Running')) {
                    $latestData = ProcessedData::where('DEVICE_ID', $camera->DEVICE_ID)
                        ->latest()
                        ->first();

                    $count = $latestData->DATA['peopleIn']
                        + $latestData->DATA['yesterdayIn']
                        - $latestData->DATA['peopleOut']
                        - $latestData->DATA['yesterdayOut'];

                    $peopleCounterData = [
                        "status" => "new",
                        "values" => [
                            "people_count.bl_id" => "NUL-HQ",
                            "people_count.bl_id.key" => "NUL-HQ",
                            "people_count.fl_id" => "09",
                            "people_count.fl_id.key" => "09",
                            "people_count.rm_id" => "9N",
                            "people_count.rm_id.key" => "9N",
                            "people_count.device_id" => "$camera->DEVICE_ID",
                            "people_count.device_id.key" => "$camera->DEVICE_ID",
                            "people_count.count" => $count,
                            "people_count.count_date" => date("Y/m/d H:i:s", strtotime($latestData->CREATED_AT)),
                            "people_count.count_date.key" => date("Y/m/d H:i:s", strtotime($latestData->CREATED_AT))
                        ]
                    ];
                    $body[] = $peopleCounterData;
                }
            }
        }

        // Initialize cURL
        $curl = curl_init();

        // Create cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiInfo->API_URL,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);
        $response = curl_exec($curl);

        // Close cURL
        curl_close($curl);

        return json_decode($response, true);
    }
    /**
     * <Layer number> (13.0)
     *
     * <Processing name> getCamerasWithBindings<br>
     * <Function> API that receives all the logs sent by ACS servers<br>
     *            URL: http://localhost/getCamerasWithBindings<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return $return
     * @throws string 'failed' When an exception occurs in this process
     */
    public function getCamerasWithBindings(Request $request)
    {
        $return = $this->createGetResponse(
            $request,
            Device::where('DEVICE_TYPE', 'camera')
                ->with('bindingCamera')
        );

        return $return;
    }
}
