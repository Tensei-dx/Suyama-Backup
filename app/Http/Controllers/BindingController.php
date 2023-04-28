<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\DeviceCommandEvent;
use App\Events\testBinding;
use App\Models\ApplianceOperation;
use App\Models\Binding;
use App\Models\BindingCamera;
use App\Models\Device;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * <Class Name> BindingController
 *
 * <Function Name> Binding Processing<br>
 * Create : 2018.07.27 TP Bryan<br>
 * Update : 2018.08.20 TP Bryan    Fixed code structure
 *          2018.09.25 TP Robert   Add code in checkBinding
 *          2018.09.26 TP Robert   Add code in checkNextActivity
 *          2018.09.27 TP Robert   Fixed code in checkBinding
 *          2018.10.23 TP Robert   Fixed code in checkBinding - Sensor to sensor
 *          2018.10.24 TP Robert   Fixed code in checkBinding - sensor to sensor range
 *          2018.10.25 TP Robert   Fixed code in checkBinding - sensor to sensor range
 *          2018.10.26 TP Robert   Fixed code in checkBinding - sensor to sensor / add comment
 *          2018.11.21 TP Robert   Update the disable binding
 *          2018.11.22 TP Robert   Update the disable and check next binding
 *          2019.06.20 TP Chris    Added try and catch function
 *          2020.03.23 TP Harvey   Added Custom Binding Function
 *          2020.05.15 TP Uddin    Implement coding standard for PHP7
 *          2020.05.21 TP Uddin    Modify URL and Methodnames according to the URL list
 *          2020.05.22 TP Harvey   Remove request in checkBindings()
 *			2020.09.22 TP Russell  Added Binding Threshold Setting
 *          2020.10.28 TP Harvey   Added getCameraBindingDevices Function
 *          2020.10.30 TP Harvey   Added createCameraBinding Function
 *          2020.11.03 TP Harvey   Added deleteCameraBinding Function
 *          2020.11.03 TP Harvey   Added modifyCameraBindingStatus Function
 *          2020.11.03 TP Harvey   Added getCamerasWithBindings Function
 *          2020.11.04 TP Harvey   Added updateCameraBindingTimeInterval function
 *
 * <Overview> This controller is responsible for binding of different sensors
 *            or/and devices and its data from the database.
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BindingController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getBindingAll                    (1.0) Get all bindings of sensors and/or devices from the database and display on the screen
    // getBinding                       (2.0) Get binding of sensors and/or devices from the database and display on the screen
    // createBinding                    (3.0) Create new binding of sensors and/or devices and save it to the database
    // deleteBinding                    (4.0) Delete a specific sensor/device binding from the database
    // deleteSensorBinding              (5.0) Delete binding of sensors and/or devices from the database
    // enableBinding                    (6.0) Enable binding of sensors and/or devices from the database
    // disableBinding                   (7.0) Disable binding of sensors and/or devices from the database
    // checkBindings                    (8.0) Execute instructions according to registered binding of sensors/device
    // checkNextActivity                (9.0) Check the next interval execution of sensors/device
    // updateTimeInterval               (10.0) Set time interval for the sensors/devices
    // enableAllBinding                 (11.0) Enable all binding of sensors and/or devices from the database
    // disableAllBinding                (12.0) Disable all binding of sensors and/or devices from the database
    // checkBindingCondition            (13.0) Trigger Device with Custom Condition
    // getCameraBindingDevices          (14.0) Get Devices that can be bind to camera
    // createCameraBinding              (15.0) Create Camera Binding
    // deleteCameraBinding              (16.0) Delete Camera Binding Status
    // modifyCameraBindingStatus        (17.0) Modify Camera Binding Status
    // getCamerasWithBindings           (18.0) Get Cameras with bindings registered
    // triggerCameraBinding             (19.0) Trigger Camera Binding
    // updateCameraBindingTimeInterval  (20.0) Update camera binding interval.

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all binding data<br>
     * <Function> Get all bindings of sensors and/or devices from the database and display on the screen<br>
     *            URL: http://localhost/getBindingAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $bindings->groupBy('sourceDevice.DEVICE_ID')
     * @throws Throwable When an exception occurs in this process
     */
    public function getBindingAll(Request $request)
    {
        try {
            $bindings = $this->createGetResponse($request, (new Binding())
                ->newQuery());
            return $bindings->groupBy('sourceDevice.DEVICE_ID');
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
     * <Layer number> (2.0)
     *
     * <Processing name> Acquire binding data<br>
     * <Function> Get binding of sensors and/or devices from the database and display on the screen<br>
     *            URL: http://localhost/getBinding/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object Binding::findOrFail($id);
     * @throws Throwable When an exception occurs in this process
     */
    public function getBinding(Request $request, int $id)
    {
        try {
            return Binding::findOrFail($id);
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
     * <Layer number> (3.0)
     *
     * <Processing name> Create new binding<br>
     * <Function> Create new binding of sensors and/or devices and save it to the database<br>
     *            URL: http://localhost/createBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function createBinding(Request $request)
    {
        $response = [];
        $bindingDevices = json_decode(
            json_encode($request->TARGET_DEVICES),
            true
        );
        $bindingRemoveList = json_decode(
            json_encode($request->REMOVE_LIST),
            true
        );
        //Delete Unchecked Binding
        foreach ($bindingRemoveList as $item) {
            Binding::where('SOURCE_DEVICE_ID', $item['SOURCE_DEVICE_ID'])
                ->where('TARGET_DEVICE_ID', $item['TARGET_DEVICE_ID'])
                ->where('BINDING_LIST_ID', $item['BINDING_LIST_ID'])
                ->delete();
        }

        //Update/Insert new Binding
        foreach ($bindingDevices as $bindingDevice) {
            $existingBinding = Binding::where(
                'SOURCE_DEVICE_ID',
                $bindingDevice['SOURCE_DEVICE_ID']
            )
                ->where('TARGET_DEVICE_ID', $bindingDevice['TARGET_DEVICE_ID'])
                ->where('BINDING_LIST_ID', $bindingDevice['BINDING_LIST_ID'])
                ->first();
            if ($existingBinding) {
                //Update Existing Binding
                $existingBinding->SOURCE_DEVICE_ID =
                    $bindingDevice['SOURCE_DEVICE_ID'];
                $existingBinding->TARGET_DEVICE_ID =
                    $bindingDevice['TARGET_DEVICE_ID'];
                $existingBinding->BINDING_LIST_ID =
                    $bindingDevice['BINDING_LIST_ID'];
                $existingBinding->TIME_INTERVAL =
                    $bindingDevice['TIME_INTERVAL'] * 60;
                $existingBinding->CUSTOM_CONDITION      = $bindingDevice['CUSTOM_CONDITION'];
                // 9/22/2020 Added SOURCE_DEVICE_CONDITION as new column in DB
                $existingBinding->SOURCE_DEVICE_CONDITION   = $bindingDevice['SOURCE_DEVICE_CONDITION'];
                $existingBinding->save();
            } else {
                //Create new Binding
                $bindingList = new Binding();
                $bindingList->SOURCE_DEVICE_ID =
                    $bindingDevice['SOURCE_DEVICE_ID'];
                $bindingList->TARGET_DEVICE_ID =
                    $bindingDevice['TARGET_DEVICE_ID'];
                $bindingList->BINDING_LIST_ID =
                    $bindingDevice['BINDING_LIST_ID'];
                $bindingList->TIME_INTERVAL =
                    $bindingDevice['TIME_INTERVAL'] * 60;
                $bindingList->CUSTOM_CONDITION          = $bindingDevice['CUSTOM_CONDITION'];
                // 9/22/2020 Added SOURCE_DEVICE_CONDITION as new column in DB
                $bindingList->SOURCE_DEVICE_CONDITION   = $bindingDevice['SOURCE_DEVICE_CONDITION'];
                $bindingList->save();
            }
        }

        // For audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'binding management';
        $instruction = 'created a new binding';
        $this->auditLogs($ip, $host, $module, $instruction);
        return 'success';
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Delete binding<br>
     * <Function> Delete a specific sensor/device binding from the database<br>
     *            URL: http://localhost/deleteBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteBinding(Request $request)
    {
        try {
            $binding = Binding::findOrFail($request->BINDING_ID);
            $binding->delete();
            // for audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Binding Management';
            $instruction = 'Specific Binding Device Successfully Deleted';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
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
     * <Layer number> (5.0)
     *
     * <Processing name> Delete sensor/device binding<br>
     * <Function> Delete binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/deleteSensorBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteSensorBinding(Request $request)
    {
        try {
            $bindings = Binding::where('SOURCE_DEVICE_ID', $request->DEVICE_ID)
                ->get();
            foreach ($bindings as $binding) {
                $binding->delete();
            }
            // for audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'binding management';
            $instruction = 'deleted a sensor binding';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
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
     * <Layer number> (6.0)
     *
     * <Processing name> Enable binding of sensor/device<br>
     * <Function> Enable binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/enableBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function enableBinding(Request $request)
    {
        try {
            $time = strtotime(date("Y-m-d H:i:s"));
            // Combine the time now and interval for new date
            $timeNow = date("Y-m-d H:i:s", $time);
            $binding = Binding::findOrFail($request->BINDING_ID);
            $binding->BINDING_STATUS = 1;
            $binding->MANUAL = 0;
            $binding->NEXT_ACTIVITY = $timeNow;
            $binding->save();
            // For audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'binding management';
            $instruction = 'enable binding';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
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
     * <Layer number> (7.0)
     *
     * <Processing name> Disable sensor/device binding<br>
     * <Function> Disable binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/disableBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function disableBinding(Request $request)
    {
        try {
            if ($request->TYPE == 1) {
                $data = $request->BINDING_ID;
                //Get time now
                $timeNow = strtotime(date("Y-m-d H:i:s"));
                // Time interval when the binding need to be back for future
                // need the System Settings
                $interVal = 120;
                // Combine the time now and interval for new date
                $timeInterVal = date(
                    "Y-m-d H:i:s",
                    $timeNow + $interVal
                );
                foreach ($data as $id) {
                    $binding = Binding::findOrFail($id);
                    $binding->BINDING_STATUS = 0;
                    $binding->MANUAL = 1;
                    $binding->NEXT_ACTIVITY = $timeInterVal;
                    $binding->save();
                }
            } else {
                $binding = Binding::findOrFail($request->BINDING_ID);
                $binding->BINDING_STATUS = 0;
                $binding->save();
            }
            // For audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'binding management';
            $instruction = 'disable binding';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
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
     * <Layer Number> (8.0)
     *
     * <Processing Name> Check bindings of sensor/device<br>
     * <Function> Execute instructions according to registered binding of sensors/device<br>
     *            URL: http://localhost/checkBindings<br>
     *            METHOD: POST
     *
     * @param object $processedData
     * @param object $device
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function checkBindings(object $processedData, object $device)
    {

        // Get all the bindings we have
        $bindings = Binding::with('bindingList', 'learningCommand')
            ->where('SOURCE_DEVICE_ID', $device->DEVICE_ID)
            ->where('BINDING_STATUS', 1)
            ->get();
        // Loop all the bindings we readline_callback_handler_remove()
        $bindingsPriority = Binding::with('bindingList')->get();

        $deviceData = "";
        $condition = "";
        foreach ($bindings as $binding) {
            // 9/22/2020 Added Trigger for Binding with Threshold Setting
            $sourceDeviceDB     = $binding->sourceDevice;
            $targetDevice       = Device::where('DEVICE_ID', $binding->TARGET_DEVICE_ID)->select('GATEWAY_ID', 'DEVICE_ID', 'DEVICE_TYPE')->first();
            $targetDeviceDB     = $binding->targetDevice;
            $targetGatewayId    = $targetDevice->GATEWAY_ID;
            $targetDeviceId     = $targetDevice->DEVICE_ID;
            $targetDeviceType   = $targetDevice->DEVICE_TYPE;
            $timeInterVal       = $binding->TIME_INTERVAL;
            $sourceDeviceName   = $sourceDeviceDB->DEVICE_NAME;
            $targetDeviceName   = $targetDeviceDB->DEVICE_NAME;
            $timeNow            = strtotime(date("Y-m-d H:i:s"));

            if ($binding->BINDING_LIST_ID == 0) {
                $sourceCondition    = $binding->SOURCE_DEVICE_CONDITION['operator'];
                $sourceValue        = $binding->SOURCE_DEVICE_CONDITION['data'];
                //Check Source Device Type
                if ($device->DEVICE_TYPE == "temp_hum") {
                    $deviceData = $device->DATA['temp'];
                } else if ($device->DEVICE_TYPE == "dust_detector") {
                    $deviceData = $device->DATA['status'];
                } else if ($device->DEVICE_TYPE == "co2_detector") {
                    $deviceData = $device->DATA['status'];
                }
                if (($sourceCondition == "MAX" && $deviceData > $sourceValue) ||
                    ($sourceCondition == "MIN" && $deviceData < $sourceValue)
                ) {


                    if ($this->checkBindingCondition(
                        $targetGatewayId,
                        $targetDeviceId,
                        $binding,
                        $targetDeviceType
                    )) {
                        //Insert System Logs for Binding
                        $content = $sourceDeviceName . " triggered " . $targetDeviceName . " through Binding Function";
                        $this->storeLogs(4, 'Automatic', $content, "Binding", $type);
                        continue;
                    }
                }
            } else {
                $targetDeviceId = $targetDeviceDB->DEVICE_ID;
                $targetGatewayId = $targetDeviceDB->GATEWAY_ID;
                $timeInterVal = $binding->TIME_INTERVAL;
                // ProcessedData Value
                $str = $processedData->DATA;
                // Key of the Source device command e.g(status/status_lock/temp)
                $key = $binding->bindingList->SOURCE_DEVICE_COMMAND;
                // Value of the source device e.g(1/0/range)
                $val = $binding->bindingList->SOURCE_DEVICE_VALUE;
                // Source device type
                $type = $binding->bindingList->SOURCE_DEVICE_TYPE;

                if ($binding->bindingList->TARGET_DEVICE_CATEGORY != "1") {
                    // Identify if the type of sensor is range
                    if ($type == 'dust_detector') {
                        // timeNow
                        $timeNow = strtotime(date("Y-m-d H:i:s"));
                        $timeNext = strtotime($binding->NEXT_ACTIVITY);
                        // Get time interval
                        $timeInterVal = $binding->TIME_INTERVAL;
                        // Check if the processedData value is range
                        $value = json_decode($val, true);
                        if (in_array($str[$key], range(
                            $value['value1'],
                            $value['value2']
                        ))) {
                            $learningVal = '';
                            // Target device command
                            $command = $binding->bindingList->TARGET_DEVICE_COMMAND;
                            // Target device value
                            $value = $binding->bindingList->TARGET_DEVICE_VALUE;
                            // Get the device id of the target device
                            $device = $targetDeviceDB;
                            // Instruct the device
                            $this->newInstructionRequest(
                                $device->gateway
                                    ->GATEWAY_ID,
                                $device->DEVICE_ID,
                                $command,
                                $value,
                                $learningVal
                            );
                            // Set the interval in binding
                            $this->updateTimeInterval(
                                $binding->BINDING_ID,
                                $timeNow,
                                $timeInterVal
                            );
                        }
                    } elseif ($type == 'light_detector') {
                        // ---Priority Sensor (Dont Trigger if target device is
                        // binded to other sensor that has a high priority)---
                        $targetDevice = $binding->TARGET_DEVICE_ID;
                        $counter = 0;
                        foreach ($bindingsPriority as $bind) {
                            $timeNow = strtotime(date("Y-m-d H:i:s"));
                            $timeNext = strtotime($bind->NEXT_ACTIVITY);
                            if (
                                $binding->SOURCE_DEVICE_ID != $bind->SOURCE_DEVICE_ID &&
                                $bind->bindingList->SOURCE_DEVICE_TYPE == 'motion_detector' &&
                                $targetDevice == $bind->TARGET_DEVICE_ID
                            ) {
                                if ($timeNow <= $timeNext) {
                                    $counter++;
                                }
                            }
                        }
                        if ($counter > 0) {
                            continue;
                        }
                        // timenow
                        $timeNow = strtotime(date("Y-m-d H:i:s"));
                        $timeNext = strtotime($binding->NEXT_ACTIVITY);
                        // get time interval
                        $timeInterVal = $binding->TIME_INTERVAL;
                        $value = json_decode($val, true);
                        if (in_array($str[$key], range(
                            $value['value1'],
                            $value['value2']
                        ))) {
                            $learningVal = '';
                            // Target device command
                            $command = $binding->bindingList->TARGET_DEVICE_COMMAND;
                            // Target device value
                            $value = $binding->bindingList->TARGET_DEVICE_VALUE;
                            // Get the device id of the target device
                            $device = $targetDeviceDB;
                            // Instruct the device
                            $this->newInstructionRequest(
                                $device->gateway->GATEWAY_ID,
                                $device->DEVICE_ID,
                                $command,
                                $value,
                                $learningVal
                            );
                            // Set the interval in binding
                            $this->updateTimeInterval(
                                $binding->BINDING_ID,
                                $timeNow,
                                $timeInterVal
                            );
                        }
                    } elseif ($type == 'temp_hum') {
                        // check if the processedData value is range
                        $value = json_decode($val, true);
                        $nVal1 = round($value['value1']);
                        $nVal2 = round($value['value2']);
                        $pData = round(
                            $str[$key],
                            0,
                            PHP_ROUND_HALF_DOWN
                        );
                        // timenow
                        $timeNow = strtotime(date("Y-m-d H:i:s"));
                        $timeNext = strtotime($binding->NEXT_ACTIVITY);
                        // get time interval
                        $timeInterVal = $binding->TIME_INTERVAL;
                        if (in_array($pData, range($nVal1, $nVal2))) {
                            $targetType = $binding->bindingList->TARGET_DEVICE_TYPE;
                            $learns = $binding->learningCommand;
                            if ($targetType == 'ir_remote') {
                                //new code
                                foreach ($learns as $learn) {
                                    $learningVal = $learn->LEARNING_VALUE;
                                    $operation = $learn->OPERATION_ID;
                                    $targetCondition = $binding->bindingList->TARGET_DEVICE_CONDITION;
                                    $operationName = ApplianceOperation::where(
                                        'OPERATION_ID',
                                        $operation
                                    )
                                        ->first()
                                        ->OPERATION_NAME;
                                    // get the device id of the target device
                                    $device = $targetDeviceDB;
                                    // target device command
                                    $command = $binding->bindingList->TARGET_DEVICE_COMMAND;
                                    // target device value
                                    $value = $binding->bindingList->TARGET_DEVICE_VALUE;
                                    if ($targetCondition == $operationName) {
                                        if ($timeNow >= $timeNext) {
                                            // instruct the device
                                            $this->newInstructionRequest(
                                                $device->gateway->GATEWAY_ID,
                                                $device->DEVICE_ID,
                                                $command,
                                                $value,
                                                $learningVal
                                            );
                                            // set the interval in binding
                                            $this->updateTimeInterval(
                                                $binding->BINDING_ID,
                                                $timeNow,
                                                $timeInterVal
                                            );
                                        }
                                    }
                                }
                            } else {
                                $learningVal = '';
                                if ($timeNow >= $timeNext) {
                                    // target device command
                                    $command = $binding->bindingList
                                        ->TARGET_DEVICE_COMMAND;
                                    // target device value
                                    $value = $binding->bindingList
                                        ->TARGET_DEVICE_VALUE;
                                    // get the device id of the target device
                                    $device = $targetDeviceDB;
                                    // instruct the device
                                    $this->newInstructionRequest(
                                        $device->gateway->GATEWAY_ID,
                                        $device->DEVICE_ID,
                                        $command,
                                        $value,
                                        $learningVal
                                    );
                                }
                            }
                        }
                    } elseif ($type == 'panic_button') {
                        if ($this->checkBindingCondition($targetGatewayId, $targetDeviceId, $binding)) {
                            //set the interval in binding

                            //Insert System Logs for Binding
                            $content = $sourceDeviceName . " triggered " . $targetDeviceName . " through Binding Function";
                            $this->storeLogs(4, 'Automatic', $content, "Binding", $type);
                            continue;
                        }
                        $learningVal = '';
                        // target device command
                        $command = $binding->bindingList->TARGET_DEVICE_COMMAND;
                        // target device value
                        $value = $binding->bindingList->TARGET_DEVICE_VALUE;
                        //get the device id of the target device
                        $device = $targetDeviceDB;
                        //instruct the device
                        $this->newInstructionRequest(
                            $device->gateway->GATEWAY_ID,
                            $device->DEVICE_ID,
                            $command,
                            $value,
                            $learningVal
                        );
                    } elseif (
                        $type != 'dust_detector' && $type != 'light_detector' &&
                        $type != 'temp_hum' && $type != 'panic_button'
                    ) {
                        // Will go here for default Flow of Sensor
                        // Check if the value is equal to the processdata value
                        if ($val == $str[$key]) {
                            // Timenow
                            $timeNow = strtotime(date("Y-m-d H:i:s"));
                            //Get time interval
                            $timeInterVal = $binding->TIME_INTERVAL;
                            //Time next is the next activity of the binding
                            $timeNext = strtotime($binding->NEXT_ACTIVITY);
                            // Check if the Device is triggered as ON or value as 1
                            if ($val == 1) {
                                $targetType = $binding->bindingList
                                    ->TARGET_DEVICE_TYPE;
                                $learns = $binding->learningCommand;
                                if ($targetType == 'ir_remote') {
                                    foreach ($learns as $learn) {
                                        $learningVal = $learn->LEARNING_VALUE;
                                        $operation = $learn->OPERATION_ID;
                                        $targetCondition = $binding->bindingList
                                            ->TARGET_DEVICE_CONDITION;
                                        $operationName = ApplianceOperation::where(
                                            'OPERATION_ID',
                                            $operation
                                        )
                                            ->first()
                                            ->OPERATION_NAME;
                                        //get the device id of the target device
                                        $device = $targetDeviceDB;
                                        // target device command
                                        $command = $binding->bindingList
                                            ->TARGET_DEVICE_COMMAND;
                                        // target device value
                                        $value = $binding->bindingList
                                            ->TARGET_DEVICE_VALUE;
                                        if ($targetCondition == $operationName) {
                                            if ($timeNow >= $timeNext) {
                                                // instruct the device

                                                $this->newInstructionRequest(
                                                    $device->gateway->GATEWAY_ID,
                                                    $device->DEVICE_ID,
                                                    $command,
                                                    $value,
                                                    $learningVal
                                                );
                                                // set the interval in binding
                                                $this->updateTimeInterval(
                                                    $binding->BINDING_ID,
                                                    $timeNow,
                                                    $timeInterVal
                                                );
                                            }
                                        }
                                    }
                                } else {
                                    //check if the timenow is greater than time next
                                    if ($timeNow >= $timeNext) {
                                        if ($this->checkBindingCondition($targetGatewayId, $targetDeviceId, $binding)) {

                                            // allow the off function of this
                                            // binding to trigger once by setting
                                            // the manual to 1

                                            //Insert System Logs for Binding
                                            $content = $sourceDeviceName . " triggered " . $targetDeviceName . " through Binding Function";
                                            $this->storeLogs(4, 'Automatic', $content, "Binding", $type);

                                            $bindingPartner = Binding::where('SOURCE_DEVICE_ID', $binding->SOURCE_DEVICE_ID)
                                                ->where('TARGET_DEVICE_ID', $binding->TARGET_DEVICE_ID)
                                                ->where('BINDING_ID', '!=', $binding->BINDING_ID)
                                                ->first();
                                            if ($bindingPartner) {
                                                $bindingPartner->MANUAL = 1;
                                                $bindingPartner->save();
                                            }
                                            continue;
                                        }
                                        $learningVal = '';
                                        // target device command
                                        $command = $binding->bindingList->TARGET_DEVICE_COMMAND;
                                        // target device value
                                        $value = $binding->bindingList->TARGET_DEVICE_VALUE;
                                        //get the device id of the target device
                                        $device = $targetDeviceDB;
                                        //instruct the device
                                        $this->newInstructionRequest(
                                            $device->gateway->GATEWAY_ID,
                                            $device->DEVICE_ID,
                                            $command,
                                            $value,
                                            $learningVal
                                        );
                                    }
                                    // Set next time
                                    // set the interval in binding
                                    $this->updateTimeInterval(
                                        $binding->BINDING_ID,
                                        $timeNow,
                                        $timeInterVal
                                    );
                                }
                            } else {
                                // Set next time
                                // set the interval in binding
                                $this->updateTimeInterval(
                                    $binding->BINDING_ID,
                                    $timeNow,
                                    $timeInterVal
                                );
                            }
                        }
                    }
                }
                try {
                    $device = Device::where('DEVICE_ID', $binding->TARGET_DEVICE_ID)
                        ->with('room')
                        ->with('floor')
                        ->first();
                } catch (\Throwable $e) {
                    // Insert System Logs
                    $type = '3';
                    $instructionType = 'System Error';
                    $uri = "Binding Controller";
                    $content = $uri . " : " . $e->getMessage();
                    $ip = "-";
                    $username = "MC";
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return $e->getMessage();
                }
                if (isset($type)) {
                    if ($val == 1) {
                        $this->storeLogs(
                            4,
                            'Automatic',
                            'Device: ' . $device->DEVICE_NAME .
                                ' Room: ' . $device->room->ROOM_NAME .
                                ' Floor: ' . $device->floor->FLOOR_NAME .
                                ' Event: ' . $key . ' - ON',
                            "None(Binding)",
                            $type
                        );
                    } elseif ($val == 0) {
                        $this->storeLogs(
                            4,
                            'Automatic',
                            'Device: ' . $device->DEVICE_NAME .
                                ' Room: ' . $device->room->ROOM_NAME .
                                ' Floor: ' . $device->floor->FLOOR_NAME .
                                ' Event: ' . $key . ' - OFF',
                            "None(Binding)",
                            $type
                        );
                    } else {
                        $this->storeLogs(
                            4,
                            'Automatic',
                            'Device: ' . $device->DEVICE_NAME .
                                ' Room: ' . $device->room->ROOM_NAME .
                                ' Floor: ' . $device->floor->FLOOR_NAME .
                                ' Event: ' . $key . ' - ' . $val,
                            "None(Binding)",
                            $type
                        );
                    }
                }
            }
        }
    }


    /**
     * <Layer Number> (9.0)
     *
     * <Processing Name> Check next activity<br>
     * <Function> Check the next interval execution of sensors/device<br>
     *
     * @throws Throwable When an exception occurs in this process
     */
    public function checkNextActivity()
    {
        // Get all off binding
        $bindings = null;
        try {
            $bindings = Binding::whereHas('bindingList', function ($query) {
                $query->where('SOURCE_DEVICE_VALUE', 0);
            })->with('bindingList')
                ->where('MANUAL', 1)
                ->where('BINDING_STATUS', 1)
                ->get();
        } catch (\Throwable $e) {
            $ip = '-';
            $host = 'Batch';
            $content = __FUNCTION__ . ': ' . $e->getMessage();
            $type = '3';
            $instructionType = 'System Error';
            $this->storeLogs($type, $instructionType, $content, $ip, $host);
        }
        // Loop the binding with off value
        try {
            foreach ($bindings as $binding) {
                $targetDeviceDB = Device::where('DEVICE_ID', $binding->TARGET_DEVICE_ID)->first();
                $targetDeviceId = $targetDeviceDB->DEVICE_ID;
                $targetGatewayId = $targetDeviceDB->GATEWAY_ID;
                $timeNow = strtotime(date("Y-m-d H:i:s"));
                $timeInterVal = $binding->TIME_INTERVAL;
                //check if the type of sensor is range or not
                $souceDeviceType = $binding->bindingList->SOURCE_DEVICE_TYPE;
                if ($souceDeviceType == 'light_detector') {
                } elseif ($souceDeviceType == 'temp_hum') {
                } elseif ($souceDeviceType == 'dust_detector') {
                } elseif ($souceDeviceType == 'co2_detector') {
                } else {
                    $timeNow = strtotime(date("Y-m-d H:i:s"));
                    $timeNext = strtotime($binding->NEXT_ACTIVITY);
                    $timeInterVal = $binding->TIME_INTERVAL;
                    if ($timeNow >= $timeNext) {
                        $targetType = $binding->bindingList->TARGET_DEVICE_TYPE;
                        $learns = $binding->learningCommand;
                        if ($targetType == 'ir_remote') {
                            //new code.
                            foreach ($learns as $learn) {
                                $learningVal = $learn->LEARNING_VALUE;
                                $operation = $learn->OPERATION_ID;
                                $targetCondition = $binding->bindingList
                                    ->TARGET_DEVICE_CONDITION;
                                $operationName = ApplianceOperation::where('OPERATION_ID', $operation)->first()->OPERATION_NAME;
                                // get the device id of the target device
                                $device = Device::where('DEVICE_ID', $binding->TARGET_DEVICE_ID)->first();
                                // target device command
                                $command = $binding->bindingList->TARGET_DEVICE_COMMAND;
                                // target device value
                                $value = $binding->bindingList->TARGET_DEVICE_VALUE;
                                // should be brand and operation check
                                if ($targetCondition == $operationName) {
                                    if ($timeNow >= $timeNext) {
                                        // instruct the device
                                        $this->newInstructionRequest(
                                            $device->gateway->GATEWAY_ID,
                                            $device->DEVICE_ID,
                                            $command,
                                            $value,
                                            $learningVal
                                        );
                                    }
                                }
                            }
                        } elseif ($targetType != 'ir_remote') {
                            // Check if Custom Condition is triggered
                            if ($this->checkBindingCondition($targetGatewayId, $targetDeviceId, $binding, "", false)) {
                                //Insert System Logs for Binding
                                $content = $sourceDeviceName . " triggered " . $targetDeviceName . " through Binding Function";
                                $this->storeLogs(4, 'Automatic', $content, "Binding", $type);
                                //Continue Process
                            } else {
                                $learningVal = '';
                                $command    = $binding->bindingList->TARGET_DEVICE_COMMAND;
                                $value      = $binding->bindingList->TARGET_DEVICE_VALUE;
                                $device     = Device::where('DEVICE_ID', $binding->TARGET_DEVICE_ID)->first();
                                $this->newInstructionRequest(
                                    $device->gateway->GATEWAY_ID,
                                    $device->DEVICE_ID,
                                    $command,
                                    $value,
                                    $learningVal
                                );
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            $ip = '-';
            $host = 'Batch';
            $content = __FUNCTION__ . '- binding loop: ' . $e->getMessage();
            $type = '3';
            $instructionType = 'System Error';
            $this->storeLogs($type, $instructionType, $content, $ip, $host);
        }
        try {
            // Update the BINDING_STATUS and MANUAL to it normal after the
            // time interval
            $manualBindings = Binding::where('MANUAL', 1)->get();
            foreach ($manualBindings as $manualBinding) {
                $timeNow = strtotime(date("Y-m-d H:i:s"));
                $timeNext = strtotime($manualBinding->NEXT_ACTIVITY);
                if ($timeNow >= $timeNext) {
                    $updateBinding = Binding::findOrFail(
                        $manualBinding->BINDING_ID
                    );
                    // $updateBinding->BINDING_STATUS = 1;
                    $updateBinding->MANUAL = 0;
                    $updateBinding->save();
                }
            }
        } catch (\Throwable $e) {
            $ip = '-';
            $host = 'Batch';
            $content = __FUNCTION__ . '- Manual binding check: '
                . $e->getMessage();
            $type = '3';
            $instructionType = 'System Error';
            $this->storeLogs($type, $instructionType, $content, $ip, $host);
        }
    }

    /**
     * <Layer Number> (10.0)
     *
     * <Processing Name> Update time interval<br>
     * <Function> Set time interval for the sensors/devices<br>
     *            URL: <br>
     *            METHOD:
     *
     * @param int $id
     * @param string $timeNow
     * @param string $timeInterVal
     */
    private function updateTimeInterval(int $id, string $timeNow, string $timeInterVal)
    {
        try {
            $binding = Binding::where('BINDING_ID', $id)->first();
            $binding->LAST_ACTIVITY = date("Y-m-d H:i:s", $timeNow);
            $binding->NEXT_ACTIVITY = date("Y-m-d H:i:s", $timeNow + $timeInterVal);
            $binding->save();
            $bindingPartner = Binding::where('SOURCE_DEVICE_ID', $binding->SOURCE_DEVICE_ID)
                ->where('TARGET_DEVICE_ID', $binding->TARGET_DEVICE_ID)
                ->where('BINDING_ID', '!=', $id)
                ->first();
            if ($bindingPartner) {
                $bindingPartner->LAST_ACTIVITY = date("Y-m-d H:i:s", $timeNow);
                $bindingPartner->NEXT_ACTIVITY = date("Y-m-d H:i:s", $timeNow + $timeInterVal);
                $bindingPartner->save();
            }
        } catch (\Throwable $e) {
            $ip = '-';
            $host = 'System';
            $content = __FUNCTION__ . ': ' . $e->getMessage();
            $type = '3';
            $instructionType = 'System Error';
            $this->storeLogs($type, $instructionType, $content, $ip, $host);
        }
    }

    /**
     * <Layer Number> (11.0)
     *
     * <Processing Name> Enable all binding of sensors/devices<br>
     * <Function> Enable all binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/enableAllBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function enableAllBinding(Request $request)
    {
        try {
            $time = strtotime(date("Y-m-d H:i:s"));
            // Combine the time now and interval for new date
            $timeNow = date("Y-m-d H:i:s", $time);
            Binding::where('SOURCE_DEVICE_ID', $request->BINDING)
                ->update([
                    'BINDING_STATUS' => 1,
                    'MANUAL' => 0,
                    'NEXT_ACTIVITY' => $timeNow
                ]);
            // For audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'binding management';
            $instruction = 'Enable Group binding';
            $this->auditLogs($ip, $host, $module, $instruction);
            // Return success if no error
            return 'success';
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
     * <Layer Number> (12.0)
     *
     * <Processing Name> Disable all binding of sensors/devices<br>
     * <Function> Disable all binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/disableAllBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function disableAllBinding(Request $request)
    {
        try {
            $time = strtotime(date("Y-m-d H:i:s"));
            // Combine the time now and interval for new date
            $timeNow = date("Y-m-d H:i:s", $time);
            Binding::where('SOURCE_DEVICE_ID', $request->BINDING)
                ->update([
                    'BINDING_STATUS' => 0,
                    'MANUAL' => 0,
                    'NEXT_ACTIVITY' => $timeNow
                ]);
            // For audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'binding management';
            $instruction = 'Disable Group binding';
            $this->auditLogs($ip, $host, $module, $instruction);
            // Return success if no error
            return 'success';
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
     * <Layer Number> (13.0)
     *
     * <Processing Name> checkBindingCondition<br>
     * <Function> Trigger Device with Custom Condition<br>
     *            URL: http://localhost/checkBindingCondition<br>
     *            METHOD: POST
     *
     * @param int $targetGatewayId
     * @param int $targetDeviceId
     * @param string $customCondition
     * @return bool $updateTimeInterval check if Update time interval or not
     */
    public function checkBindingCondition(
        int $targetGatewayId,
        int $targetDeviceId,
        object $binding,
        string $targetDeviceType = "",
        $updateTimeInterval = true
    ) {
        // Custom Condition Function 20200316-Harvey
        $customCondition = $binding->CUSTOM_CONDITION;
        $timeNow    = strtotime(date("Y-m-d H:i:s"));
        $timeNext   = strtotime($binding->NEXT_ACTIVITY);
        $bindingId  = $binding->BINDING_ID ? $binding->BINDING_ID : $binding->BINDING_CAMERA_ID;
        $bindingInterval = $binding->TIME_INTERVAL;
        $returnBoolean = false;
        if ($timeNow >= $timeNext) {
            if (sizeof($customCondition) > 0) {
                //Added Binding Condition for ir_remote
                if ($targetDeviceType == "ir_remote") {
                    $condition  = $customCondition['operator'];
                    $command    = $customCondition['command'];
                    $learns     = $binding->learningCommand;
                    $value      = 2;                                        //value for ir command mode
                    foreach ($learns as $learn) {
                        $learningVal = $learn->LEARNING_VALUE;
                        $operationId = $learn->OPERATION_ID;
                        $operationName = ApplianceOperation::where('OPERATION_ID', $operationId)->first()->OPERATION_NAME;
                        // target device value
                        if ($condition == $operationName) {
                            // instruct the device
                            $this->newInstructionRequest(
                                $targetGatewayId,
                                $targetDeviceId,
                                $command,
                                $value,
                                $learningVal
                            );
                        }
                    }
                } else {
                    echo "Binding has been triggered";
                    Log::info("Binding has been triggered");
                    foreach ($customCondition as $item) {
                        if ($item['enabled'] == 1) {
                            $command = $item['command'];
                            $value = $item['value'];
                            $this->newInstructionRequest(
                                $targetGatewayId,          //Gateway Id
                                $targetDeviceId,           //Target Device Id
                                $command,                  //Command
                                $value,                    //Value
                                ''
                            );
                        }
                    }
                }
                $returnBoolean = true;
                if ($updateTimeInterval == true) {
                    $this->updateTimeInterval($bindingId, $timeNow, $bindingInterval);
                }
            }
            return $returnBoolean;
        }
    }


    /**
     * <Layer number> (14.0)
     *
     * <Processing name> Get Devices that can be registered in Camera Binding<br>
     * <Function> Retrieve all devices with Camera Binding on it.<br>
     *            URL: http://localhost/getCameraBindingDevices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $deviceArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getCameraBindingDevices(Request $request)
    {
        try {
            $devices =  $this->createGetResponse($request, Device::where('REG_FLAG', 1)
                ->with('gateway:GATEWAY_NAME,GATEWAY_IP,GATEWAY_ID')
                ->with('floor:FLOOR_NAME,FLOOR_ID')
                ->with('room:ROOM_ID,ROOM_NAME')
                ->with('bindingCameraSource'));
            $deviceArr = [];
            foreach ($devices as $device) {
                array_push($deviceArr, [
                    "REG_FLAG"  => $device->REG_FLAG,
                    "GATEWAY_ID" => $device->gateway->GATEWAY_ID,
                    "GATEWAY_NAME" => $device->gateway->GATEWAY_NAME,
                    "DEVICE_SERIAL_NO" => $device->DEVICE_SERIAL_NO,
                    "FLOOR_ID" => $device->FLOOR_ID,
                    "FLOOR_NAME" => $device->floor->FLOOR_NAME,
                    "ROOM_ID" => $device->ROOM_ID,
                    "ROOM_NAME" => $device->room->ROOM_NAME,
                    "GATEWAY_IP" => $device->gateway->GATEWAY_IP,
                    "DEVICE_NAME" => $device->DEVICE_NAME,
                    "DEVICE_TYPE" => $device->DEVICE_TYPE,
                    "DEVICE_CATEGORY" => $device->DEVICE_CATEGORY,
                    "DEVICE_ID" => $device->DEVICE_ID,
                    "DATA" => $device->DATA,
                    "CAMERA_BINDING" => $device->bindingCameraSource
                ]);
            }
            return $deviceArr;
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
     * <Layer number> (15.0)
     *
     * <Processing name> Creat Camera Binding<br>
     * <Function> createCameraBinding<br>
     *            URL: http://localhost/createCameraBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return object $deviceArr
     * @throws Throwable When an exception occurs in this process
     */
    public function createCameraBinding(Request $request)
    {

        $bindingDevices = json_decode(
            json_encode($request->TARGET_DEVICES),
            true
        );
        $bindingRemoveList = json_decode(
            json_encode($request->REMOVE_LIST),
            true
        );
        //Delete Unchecked Binding
        foreach ($bindingRemoveList as $item) {
            BindingCamera::where('SOURCE_DEVICE_ID', $item['SOURCE_DEVICE_ID'])
                ->where('TARGET_DEVICE_ID', $item['TARGET_DEVICE_ID'])
                ->where('SOURCE_DEVICE_CONDITION', $item['SOURCE_DEVICE_CONDITION'])
                ->delete();
        }

        //Update/Insert new Binding
        foreach ($bindingDevices as $bindingDevice) {
            $existingBinding = BindingCamera::where('SOURCE_DEVICE_ID', $bindingDevice['SOURCE_DEVICE_ID'])
                ->where('TARGET_DEVICE_ID', $bindingDevice['TARGET_DEVICE_ID'])
                ->where('SOURCE_DEVICE_CONDITION', $item['SOURCE_DEVICE_CONDITION'])
                ->first();

            if ($existingBinding) {
                //Update Existing Binding
                $existingBinding->SOURCE_DEVICE_ID  = $bindingDevice['SOURCE_DEVICE_ID'];
                $existingBinding->TARGET_DEVICE_ID  = $bindingDevice['TARGET_DEVICE_ID'];
                $existingBinding->TIME_INTERVAL     = $bindingDevice['TIME_INTERVAL'] * 60;
                $existingBinding->CUSTOM_CONDITION  = $bindingDevice['CUSTOM_CONDITION'];
                $existingBinding->BINDING_STATUS    = 1;
                $existingBinding->NEXT_ACTIVITY     = date("Y-m-d H:i:s");
                // 9/22/2020 Added SOURCE_DEVICE_CONDITION as new column in DB
                $existingBinding->SOURCE_DEVICE_CONDITION   = $bindingDevice['SOURCE_DEVICE_CONDITION'];
                $existingBinding->save();
            } else {
                //Create new Binding
                $bindingList = new BindingCamera();
                $bindingList->SOURCE_DEVICE_ID  = $bindingDevice['SOURCE_DEVICE_ID'];
                $bindingList->TARGET_DEVICE_ID  = $bindingDevice['TARGET_DEVICE_ID'];
                $bindingList->TIME_INTERVAL     = $bindingDevice['TIME_INTERVAL'] * 60;
                $bindingList->CUSTOM_CONDITION  = $bindingDevice['CUSTOM_CONDITION'];
                $bindingList->BINDING_STATUS    = 1;
                // 9/22/2020 Added SOURCE_DEVICE_CONDITION as new column in DB
                $bindingList->NEXT_ACTIVITY     = date("Y-m-d H:i:s");
                $bindingList->SOURCE_DEVICE_CONDITION   = $bindingDevice['SOURCE_DEVICE_CONDITION'];
                $bindingList->save();
            }
        }
        // For audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Binding Camera Management';
        $instruction = 'Created a new binding Camera';
        $this->auditLogs($ip, $host, $module, $instruction);
        return 'success';
    }
    /**
     * <Layer number> (16.0)
     *
     * <Processing name> Delete Camera binding<br>
     * <Function> Delete a specific sensor/device binding from the database<br>
     *            URL: http://localhost/deleteCameraBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteCameraBinding(Request $request)
    {
        try {
            //Delete specific camera binding
            if ($request->BINDING_ID) {
                $binding = BindingCamera::where('BINDING_CAMERA_ID', $request->BINDING_ID);
                $binding->delete();
            } else if ($request->DEVICE_ID) {
                //Delete whole camera binding
                $binding = BindingCamera::where('SOURCE_DEVICE_ID', $request->DEVICE_ID);
                $binding->delete();
            }
            // for audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Camera Binding Management';
            $instruction = 'Specific Camera Binding Device Successfully Deleted';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
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
     * <Layer number> (17.0)
     *
     * <Processing name>modifyCameraBindingStatus<br>
     * <Function> Modify Camera Binding Status<br>
     *            URL: http://localhost/modifyCameraBindingStatus<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function modifyCameraBindingStatus(Request $request)
    {
        try {
            //Delete specific camera binding
            if ($request->COUNT == "specific") {
                $binding = BindingCamera::where('BINDING_CAMERA_ID', $request->BINDING_CAMERA_ID)->first();
                $binding->BINDING_STATUS = $request->STATUS;
                $binding->save();
            } else if ($request->COUNT == "all") {
                //Delete whole camera binding
                $binding = BindingCamera::where('SOURCE_DEVICE_ID', "=", $request->DEVICE_ID)
                    ->update(['BINDING_STATUS' => $request->STATUS]);
            }
            // for audit logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Camera Binding Management';
            $instruction = 'Specific Camera Binding Device Successfully Deleted';
            $this->auditLogs($ip, $host, $module, $instruction);
            return 'success';
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
     * <Layer number> (18.0)
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
                ->where('REG_FLAG', 1)
                ->has('bindingCamera')
                ->with('bindingCamera.targetDevice')
        );

        return $return;
    }

    /**
     * <Layer number> (19.0)
     *
     * <Processing name> triggerCameraBinding<br>
     * <Function> Trigger Camera Binding<br>
     *            URL: http://localhost/triggerCameraBinding<br>
     *            METHOD: POST
     *
     * @param object Device,String Condition
     * @throws string 'failed' When an exception occurs in this process
     */
    public function triggerCameraBinding(object $sourceDevice = NULL, string $sourceCondition = "")
    {

        try {
            // $sourceDevice       = $sourceDevice ? $sourceDevice : Device::where('DEVICE_ID',213213229)->first();     //Delete this
            // $sourceCondition    = 'Motion Detection';                       //Delete this

            //Get All Binding Camera
            $cameraBindings = BindingCamera::where('BINDING_STATUS', 1)
                ->where('SOURCE_DEVICE_ID', $sourceDevice->DEVICE_ID)
                ->where('SOURCE_DEVICE_CONDITION', $sourceCondition)->get();
            foreach ($cameraBindings as $cameraBinding) {

                $sourceDeviceName = $cameraBinding->sourceDevice->DEVICE_NAME;
                $sourceDeviceType = $cameraBinding->sourceDevice->DEVICE_TYPE;
                $bindingId       = $cameraBinding->BINDING_CAMERA_ID;
                $timeInterval    = $cameraBinding->TIME_INTERVAL;
                $timeNow         = strtotime(date("Y-m-d H:i:s"));
                $targetDevice    = Device::where('DEVICE_ID', $cameraBinding->TARGET_DEVICE_ID)->first();
                $targetGatewayId = $targetDevice->GATEWAY_ID;
                $targetDeviceId  = $targetDevice->DEVICE_ID;
                $targetDeviceType = $targetDevice->DEVICE_TYPE;
                $targetDeviceName = $targetDevice->DEVICE_NAME;

                //Trigger target Device
                if ($this->checkBindingCondition($targetGatewayId, $targetDeviceId, $cameraBinding, $targetDeviceType, false)) { //Insert System Logs for Binding

                    //Insert System Logs
                    $content = $sourceDeviceName . " triggered " . $targetDeviceName . " through Binding Function";
                    $this->storeLogs(4, 'Automatic', $content, "Binding", $sourceDeviceType);

                    $this->updateCameraBindingTimeInterval($bindingId, $timeNow, $timeInterval);
                    continue;
                }
            }
        } catch (\Throwable $e) {
            $ip = '-';
            $host = 'System';
            $content = __FUNCTION__ . ': ' . $e->getMessage();
            $type = '3';
            $instructionType = 'System Error';
            $this->storeLogs($type, $instructionType, $content, $ip, $host);
        }
        Log::info('message');
        return "</br>-triggerCameraBinding";
    }

    /**
     * <Layer Number> (20.0)
     *
     * <Processing Name> updateCameraBindingTimeInterval<br>
     * <Function> Set time interval for the Binding Camera<br>
     *            URL: <br>
     *            METHOD:
     *
     * @param int $id
     * @param string $timeNow
     * @param string $timeInterVal
     */
    private function updateCameraBindingTimeInterval(int $id, string $timeNow, string $timeInterVal)
    {
        try {

            $bindingCamera = BindingCamera::where('BINDING_CAMERA_ID', $id)->first();
            $bindingCamera->NEXT_ACTIVITY = date("Y-m-d H:i:s", $timeNow + $timeInterVal);
            $bindingCamera->save();
        } catch (\Throwable $e) {
            $ip = '-';
            $host = 'System';
            $content = __FUNCTION__ . ': ' . $e->getMessage();
            $type = '3';
            $instructionType = 'System Error';
            $this->storeLogs($type, $instructionType, $content, $ip, $host);
        }
    }
}
