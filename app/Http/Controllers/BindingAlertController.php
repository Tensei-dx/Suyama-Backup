<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\DeviceCommandEvent;
use App\Events\testBinding;
use App\Models\ApplianceOperation;
use App\Models\Binding;
use App\Models\BindingAlert;
use App\Models\Device;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * <Class Name> BindingAlertController
 *
 * <Function Name> Binding Alert Processing<br>
 * Create : 2020.08.24 TP Yani<br>
 * Update : <br>
 *
 * <Overview> This controller is responsible for binding of different sensors
 *            or/and devices and its data from the database.
 * @package Controller
 * @author TP Yani <l-yani-tp@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BindingAlertController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getAlertBinding                  (1.0) Get all bindings of sensors and/or devices from the database and display on the screen
    // createAlertBinding               (2.0) Create new binding of sensors and/or devices and save it to the database
    // enableAllAlertBinding            (3.0) Enable all alert binding of sensors/devices
    // disableAllAlertBinding           (4.0) Disable all alert binding of sensors/devices
    // deleteAllAlertBinding            (5.0) Delete all alert binding
    // deleteAlertBinding               (6.0) Delete alert binding

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all binding data<br>
     * <Function> Get all bindings of sensors and/or devices from the database and display on the screen<br>
     *            URL: http://localhost/getAlertBinding<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $bindings->groupBy('sourceDevice.DEVICE_ID')
     * @throws Throwable When an exception occurs in this process
     */
    public function getAlertBinding(Request $request)
    {
        try {
            $test = $this->createGetResponse(
                $request,
                Device::with('bindingAlerts')
                    ->has('bindingAlerts')
            );
            return $test;
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
     * <Processing name> Create new alert binding<br>
     * <Function> Create new alert binding of sensors and/or devices and save it to the database<br>
     *            URL: http://localhost/createBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function createAlertBinding(Request $request)
    {
        $alertList = json_decode(
            json_encode($request->BINDING_ALERT),
            true
        );

        $existingBinding = BindingAlert::where(
            'SOURCE_DEVICE_ID',
            $alertList['SOURCE_DEVICE_ID']
        )
            ->first();
        if ($existingBinding) {
            //Update Existing Binding
            $existingBinding->SOURCE_DEVICE_ID =
                $alertList['SOURCE_DEVICE_ID'];
            $existingBinding->TARGET_USER_ALERT =
                $alertList['ALERT'];
            $existingBinding->SOURCE_DEVICE_CONDITION =
                $alertList['SOURCE_DEVICE_CONDITION'];
            $existingBinding->TIME_INTERVAL =
                $alertList['TIME_INTERVAL'] * 60;
            $existingBinding->BINDING_STATUS =
                1;
            $existingBinding->save();
            return 'exist';
        } else {
            //Create new Binding
            $bindingAlertList = new BindingAlert();
            $bindingAlertList->SOURCE_DEVICE_ID =
                $alertList['SOURCE_DEVICE_ID'];
            $bindingAlertList->TARGET_USER_ALERT =
                $alertList['ALERT'];
            $bindingAlertList->SOURCE_DEVICE_CONDITION =
                $alertList['SOURCE_DEVICE_CONDITION'];
            $bindingAlertList->TIME_INTERVAL =
                $alertList['TIME_INTERVAL'] * 60;
            $bindingAlertList->BINDING_STATUS =
                1;
            $bindingAlertList->save();
            return 'success';
        }
    }

    /**
     * <Layer Number> (3.0)
     *
     * <Processing Name> Enable all alert binding of sensors/devices<br>
     * <Function> Enable all alert binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/enableAllBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function enableAllAlertBinding(Request $request)
    {
        try {
            $time = strtotime(date("Y-m-d H:i:s"));
            // Combine the time now and interval for new date
            $timeNow = date("Y-m-d H:i:s", $time);
            BindingAlert::where('SOURCE_DEVICE_ID', $request->BINDING)
                ->update([
                    'BINDING_STATUS' => 1
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
     * <Layer Number> (4.0)
     *
     * <Processing Name> Disable all alert binding of sensors/devices<br>
     * <Function> Disable all alert binding of sensors and/or devices from the database<br>
     *            URL: http://localhost/disableAllBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function disableAllAlertBinding(Request $request)
    {
        try {
            $time = strtotime(date("Y-m-d H:i:s"));
            // Combine the time now and interval for new date
            $timeNow = date("Y-m-d H:i:s", $time);
            BindingAlert::where('SOURCE_DEVICE_ID', $request->BINDING)
                ->update([
                    'BINDING_STATUS' => 0
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
     * <Layer number> (5.0)
     *
     * <Processing name> Delete all alert binding<br>
     * <Function> Delete binding of alert from the database<br>
     *            URL: http://localhost/deleteAllAlertBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteAllAlertBinding(Request $request)
    {
        try {
            $bindings = BindingAlert::where('SOURCE_DEVICE_ID', $request->DEVICE_ID)
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
     * <Processing name> Delete alert binding<br>
     * <Function> Delete binding of alert from the database<br>
     *            URL: http://localhost/deleteAlertBinding<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteAlertBinding(Request $request)
    {
        try {
            $list = [];
            $bindings = BindingAlert::where('BINDING_ALERT_ID', $request->BINDING_ALERT_ID)
                ->select('TARGET_USER_ALERT')
                ->get();
            foreach ($bindings as $binding) {
                foreach ($binding['TARGET_USER_ALERT'] as $key => $user) {
                    if ($user['user_id'] != $request->USER_ID) {
                        $json = [
                            'sms' => $user['sms'],
                            'email' => $user['email'],
                            'user_id' => $user['user_id']
                        ];
                        array_push($list, $json);
                    }
                }
            }
            //delete selected user
            $existingBinding = BindingAlert::where(
                'BINDING_ALERT_ID',
                $request->BINDING_ALERT_ID
            )
                ->first();
            $existingBinding->TARGET_USER_ALERT =
                $list;
            $existingBinding->save();

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
}
