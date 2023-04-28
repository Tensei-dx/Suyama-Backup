<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\BacnetData;
use App\Models\BacnetDevice;
use App\Models\BacnetDeviceList;
use App\Models\Gateway;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * <Class name> BacnetDevicesController
 *
 * <Function Name> Bacnet device management and processing<br>
 * Create : 2020.02.03 TP Uddin<br>
 * Update : 2020.02.11 TP Uddin     Remove functions that is not needed
 *          2020.02.24 TP Uddin     Add functions for object validations
 *          2020.03.12 TP Uddin     Add error_flag to fetchBacnetData
 *          2020.04.03 TP Uddin     Add functions for testing purposes
 *          2020.04.21 TP Uddin     Remove unregisterDevice
 *                                  Move auditLogs just before the return
 *                                  Assign the python path in the ENV file
 *          2020.05.21 TP Uddin     Modify URL and Method names according to the URL List<br>
 *
 * <Overview> This controller is responsible for managing the BACnet Devices and their data
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BacnetDevicesController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // sendInstruction                  (0.0) Executes Python Program
    // getUnregisteredBacnetDevices     (1.0) Retrieve all bacnet devices with REG_FLAG of 0 in the database
    // getRegisteredBacnetDevices       (2.0) Retrieve all bacnet devices with REG_FLAG of 1 in the database
    // getBacnetDeviceFloor             (3.0) Retrieve floor information of the specified bacnet device
    // getBacnetDeviceRoom              (4.0) Retrieve room information of the specified bacnet device
    // getBacnetDeviceGateway           (5.0) Retrieve gateway information of the specified bacnet device
    // getDeviceList                    (6.0) Get the list of all known predefined bacnet devicesin the database
    // getObjectList                    (7.0) Get the predefined objects list of a bacnet device
    // getDeviceObjects                 (8.0) Retrieve all valid objects of a device
    // validateBacnetObjects            (9.0) Validate objects of a bacnet device
    // registerBacnetDevice             (10.0) Set bacnet device's REG_FLAG to 1
    // updateBacnetDevice               (11.0) Change a bacnet device's information
    // deleteBacnetDevice               (12.0) Remove a bacnet device entry in the database with its data
    // scanBacnetDevices                (13.0) Scan devices connected to the network
    // getBacnetData                    (14.0) Fetch all bacnet device processed data

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (0.0)
     *
     * <Processing name> Send Python instruction<br>
     * <Function> Executes Python Program<br>
     *
     * @param string $functionName
     * @param string $params
     * @return string|null $output
     */
    private function sendInstruction(string $functionName, $params)
    {
        $params = json_encode($params);
        $params = str_replace("\"", "\\", $params);
        $command = escapeshellcmd(env('EXECUTE_BACNET_COMMAND')
            . " " . $functionName . " " . $params);
        $output = shell_exec($command);
        return $output;
    }

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all unregistered bacnet devices<br>
     * <Function> Retrieve all bacnet devices with REG_FLAG of 0 in the database<br>
     *            URL: http://localhost/getUnregisteredBacnetDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $unregisteredDevice
     */
    public function getUnregisteredBacnetDevices(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = Auth::user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = 'Retrieved unregistered devices';
        $this->auditLogs($ip, $host, $module, $instruction);

        $unregisteredDevice = BacnetDevice::with("floor:FLOOR_ID,FLOOR_NAME")
            ->with("room:ROOM_ID,ROOM_NAME")
            ->with("gateway:GATEWAY_ID,GATEWAY_NAME")
            ->where('REG_FLAG', 0)
            ->get();
        return $unregisteredDevice;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Acquire all registered bacnet devices<br>
     * <Function> Retrieve all bacnet devices with REG_FLAG of 1 in the database<br>
     *            URL: http://localhost/getRegisteredBacnetDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $registeredDevice
     */
    public function getRegisteredBacnetDevices(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = 'Retrieved registered devices';
        $this->auditLogs($ip, $host, $module, $instruction);

        $registeredDevice = BacnetDevice::with("floor:FLOOR_ID,FLOOR_NAME")
            ->with("room:ROOM_ID,ROOM_NAME")
            ->with("gateway:GATEWAY_ID,GATEWAY_NAME")
            ->where('REG_FLAG', 1)
            ->get();
        return $registeredDevice;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get the bacnet device's associated floor<br>
     * <Function> Retrieve floor information of the specified bacnet device<br>
     *            URL: http://localhost/getBacnetDeviceFloor/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $deviceFloor
     */
    public function getBacnetDeviceFloor(Request $request, int $id)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Retrieved a device's associated floor";
        $this->auditLogs($ip, $host, $module, $instruction);

        $deviceFloor = BacnetDevice::with("floor:FLOOR_ID,FLOOR_NAME")
            ->findOrFail($id);
        return $deviceFloor;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Get the bacnet device's associated room<br>
     * <Function> Retrieve room information of the specified bacnet device<br>
     *            URL: http://localhost/getBacnetDeviceRoom/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $deviceRoom
     */
    public function getBacnetDeviceRoom(Request $request, int $id)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Retrieved a device's associated room";
        $this->auditLogs($ip, $host, $module, $instruction);

        $deviceRoom = BacnetDevice::with("room:ROOM_ID,ROOM_NAME")
            ->findOrFail($id);
        return $deviceRoom;
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Get the bacnet device's associated gateway<br>
     * <Function> Retrieve gateway information of the specified bacnet device<br>
     *            URL: http://localhost/getBacnetDeviceGateway/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $deviceGateway
     */
    public function getBacnetDeviceGateway(Request $request, int $id)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Retrieved a device's associated gateway";
        $this->auditLogs($ip, $host, $module, $instruction);

        $deviceGateway = BacnetDevice::with("gateway:GATEWAY_ID,GATEWAY_NAME")
            ->findOrFail($id);
        return $deviceGateway;
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Get all unique bacnet device<br>
     * <Function> Get the list of all known predefined bacnet devices in the database<br>
     *            URL: http://localhost/getDeviceList<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $deviceList
     */
    public function getDeviceList(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Get the predefined devices list";
        $this->auditLogs($ip, $host, $module, $instruction);

        $deviceList = BacnetDeviceList::distinct('PRED_DEVICE_NUMBER')
            ->select('PRED_DEVICE_NUMBER', 'PRED_DEVICE_NAME')
            ->get();
        return $deviceList;
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Get object list of a bacnet device<br>
     * <Function> Get the predefined objects list of a bacnet device<br>
     *            URL: http://localhost/getObjectList/:name<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param string $name
     * @return object $objectList
     */
    public function getObjectList(Request $request, string $name)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Get predefined objects list of a device";
        $this->auditLogs($ip, $host, $module, $instruction);

        $objectList = BacnetDeviceList::where('PRED_DEVICE_NAME', $name)->get();
        return $objectList;
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Get bacnet device's objects<br>
     * <Function> Retrieve all valid objects of a device<br>
     *            URL: http://localhost/getDeviceObjects/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $deviceObjects
     */
    public function getDeviceObjects(Request $request, string $id)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Retrieved registered objects of a device";
        $this->auditLogs($ip, $host, $module, $instruction);

        $deviceObjects = BacnetData::where('BACNETDEVICE_ID', $id)
            ->select('BACNET_DATA_ID', 'DESCRIPTION')
            ->get();
        return $deviceObjects;
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Validate bacnet objects<br>
     * <Function> Validate objects of a bacnet device<br>
     *            URL: http://localhost/validateBacnetObjects<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "True" or "False"
     */
    public function validateBacnetObjects(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';

        $objects = $request->selectedObjects;
        $deviceID = $request->deviceID;
        $functionName = "validate_object";
        foreach ($objects as $object) {
            $obj = BacnetDeviceList::where("PRED_DEVICE_ID", $object)->first();
            $command = escapeshellcmd(
                env('EXECUTE_BACNET_COMMAND') . " "
                    . $functionName . " "
                    . $deviceID . " "
                    . $obj->OBJECT_TYPE . " "
                    . $obj->OBJECT_ID . " "
                    . $obj->OBJECT_NAME
            );
            $output = shell_exec($command);
            $output = preg_replace('/\s+/', ' ', trim($output));
            if ($output == "True") {
                continue;
            } else {
                $instruction = "Validation for objects of a device: Failed";
                $this->auditLogs($ip, $host, $module, $instruction);
                return "False";
            }
        }
        $instruction = "Validation for objects of a device: Successful";
        $this->auditLogs($ip, $host, $module, $instruction);
        return "True";
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> Register a bacnet device<br>
     * <Function> Set bacnet device's REG_FLAG to 1<br>
     *            URL: http://localhost/registerBacnetDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success', 'duplication' or 'Device is already registered'
     */
    public function registerBacnetDevice(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';
        $instruction = "Registered a Device";

        $this->validate($request, ['BACNETDEVICE_ID' => 'required']);
        $device = BacnetDevice::find($request->BACNETDEVICE_ID);
        $deviceName = BacnetDevice::select('DEVICE_NAME')->get();
        foreach ($deviceName as $name) {
            if ($name->DEVICE_NAME == $request->DEVICE_NAME) {
                return 'duplication';
            }
        }
        if ($device->REG_FLAG == 1) {
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'Device is already registered';
        } else {
            $device->FLOOR_ID           = $request->FLOOR_ID;
            $device->ROOM_ID            = $request->ROOM_ID;
            $device->DEVICE_NAME        = $request->DEVICE_NAME;
            $device->DEVICE_CATEGORY    = $request->DEVICE_CATEGORY;
            $device->DEVICE_TYPE        = $request->DEVICE_TYPE;
            $device->REG_FLAG           = 1;
            $device->ONLINE_FLAG        = 1;
            $objects = $request->selectedObjects;
            foreach ($objects as $object) {
                $obj = BacnetDeviceList::find($object);
                $data = new BacnetData();
                $data->GATEWAY_ID       = $device->GATEWAY_ID;
                $data->MANUFACTURER_ID  = $device->MANUFACTURER_ID;
                $data->BACNETDEVICE_ID  = $request->BACNETDEVICE_ID;
                $data->DEVICE_TYPE      = $request->DEVICE_TYPE;
                $data->DEVICE_ID        = $device->DEVICE_ID;
                $data->OBJECT_TYPE      = $obj->OBJECT_TYPE;
                $data->OBJECT_ID        = $obj->OBJECT_ID;
                $data->OBJECT_NAME      = $obj->OBJECT_NAME;
                $data->DESCRIPTION      = $obj->DESCRIPTION;
                $data->REG_FLAG         = 1;
                $data->ONLINE_FLAG      = 1;
                $data->save();
            }
            $device->save();
        }
        $this->auditLogs($ip, $host, $module, $instruction);
        return 'success';
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> Update a bacnet device<br>
     * <Function> Change a bacnet device's information<br>
     *            URL: http://localhost/updateBacnetDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success' or 'duplication'
     * @throws Exception When an exception occurs in this process
     */
    public function updateBacnetDevice(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';

        try {
            $this->validate($request, ['BACNETDEVICE_ID' => 'required']);
            $device = BacnetDevice::findOrFail($request->BACNETDEVICE_ID);
            $deviceName = BacnetDevice::select('DEVICE_NAME')->get();
            foreach ($deviceName as $name) {
                if ($name->DEVICE_NAME == $request->DEVICE_NAME) {
                    return 'duplication';
                }
            }
            $device->DEVICE_NAME = $request->DEVICE_NAME ?
                $request->DEVICE_NAME :
                $device->DEVICE_NAME;
            $device->DEVICE_CATEGORY = $request->DEVICE_CATEGORY ?
                $request->DEVICE_CATEGORY :
                $device->DEVICE_CATEGORY;
            $device->save();

            $instruction = "Updated Device Details";
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> Delete a bacnet device<br>
     * <Function> Remove a bacnet device entry in the database with its data<br>
     *            URL: http://localhost/deleteBacnetDevice<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success' or 'Device not found'
     */
    public function deleteBacnetDevice(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Bacnet Device Management';

        $this->validate($request, ['DEVICE_ID' => 'required']);
        $device = BacnetDevice::find($request->DEVICE_ID);
        if ($device) {
            $data = BacnetData::where('BACNETDEVICE_ID', $request->DEVICE_ID)
                ->get();
            foreach ($data as $datum) {
                $datum->delete();
            }
            $device->delete();

            $instruction = "Delete device: Successful";
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
        } else {
            $instruction = "Delete device: Failed";
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'Device not found';
        }
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> Scan bacnet devices<br>
     * <Function> Scan devices connected to the network<br>
     *            URL: http://localhost/scanBacnetDevices<br>
     *            METHOD: POST
     *
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function scanBacnetDevices()
    {
        $functionName = 'get_all_devices';
        $params = '{}';
        $output = $this->sendInstruction($functionName, $params);
        $output = json_decode($output, true);
        if ($output) {
            foreach ($output as $row) {
                $device = BacnetDevice::where('DEVICE_ID', $row['device_id'])
                    ->first();
                $gateway = Gateway::where(
                    'GATEWAY_SERIAL_NO',
                    $row['controller_serial']
                )
                    ->first();
                if (
                    !$device && $gateway && ($gateway->REG_FLAG == 1)
                    && ($gateway->ONLINE_FLAG == 1)
                ) {
                    try {
                        $device = new BacnetDevice();
                        $device->FLOOR_ID           = $gateway->FLOOR_ID;
                        $device->ROOM_ID            = $gateway->ROOM_ID;
                        $device->GATEWAY_ID         = $gateway->GATEWAY_ID;
                        $device->MANUFACTURER_ID    = $gateway->MANUFACTURER_ID;
                        $device->DEVICE_ID          = $row['device_id'];
                        $device->DEVICE_SERIAL_NO   = $row['device_serial'];
                        $device->DEVICE_NAME        = '';
                        $device->DEVICE_TYPE        = '';
                        $device->REG_FLAG           = 0;
                        $device->ONLINE_FLAG        = 1;
                        $device->save();
                    } catch (\Throwable $e) {
                        return 'failed';
                    }
                }
            }
        }
        return 'success';
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> Get bacnet device data<br>
     * <Function> Fetch all bacnet device processed data<br>
     *            URL: http://localhost/getBacnetData<br>
     *            METHOD: POST
     *
     * @return string 'success'
     */
    public function getBacnetData()
    {
        $functionName = 'fetch_all_data';
        $params = [];
        $output = [];
        $objects = BacnetData::where('ONLINE_FLAG', 1)->get();
        foreach ($objects as $object) {
            array_push($params, [
                "bacnet_data_id" => $object->BACNET_DATA_ID,
                "device_id" => $object->DEVICE_ID,
                "object_type" => $object->OBJECT_TYPE,
                "object_id" => $object->OBJECT_ID
            ]);
        }
        $output = $this->sendInstruction($functionName, $params);
        $output = json_decode($output, true);
        foreach ($output as $data) {
            $obj = BacnetData::where('BACNET_DATA_ID', $data['bacnet_data_id'])
                ->first();
            if ($data['error_flag'] == 0) {
                $obj->OBJECT_VALUE = floatval($data['object_value']);
                $obj->save();
                continue;
            } elseif ($data['error_flag'] == 1) {
                $obj->OBJECT_VALUE = null;
                $obj->save();
                continue;
            }
        }
        return 'success';
    }
}
