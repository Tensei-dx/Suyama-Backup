<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Gateway;
use App\Models\Manufacturer;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

/**
 * <Class Name> NatureRemoDeviceController
 *
 * <Function Name><br>
 * Create : 2021.05.11 TP Uddin
 * Update : 2021.05.24 TP Uddin Added registerNatureRemoDevice
 *          2021.06.07 TP Uddin Added getNatureRemoSignals method
 *          2021.06.07 TP Uddin Added sendNatureRemoSignal method
 *          2021.06.28 TP Uddin Modified registerNatureRemoDevice method
 *          2021.07.08 TP Uddin Execute storeLogs when error occurs<br>
 *
 * <Overview>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class NatureRemoDeviceController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // scanNatureRemoDevices            (1.0) Get all discoverable Nature Remo devices in the network
    // registerNatureRemoDevices        (2.0) Register a Nature Remo device and its associated appliances and signals
    // getNatureRemoSignals             (3.0) Get all registered IR signals of the Nature device
    // updateNatureRemoDeviceAppliances (4.0) Sync the many-to-many relation of Device to Appliances

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name>Scan Nature Remo Devices<br>
     * <Function>Get all discoverable Nature Remo devices in the network<br>
     *           URL: http://localhost/scanNatureRemoDevices<br>
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|string
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function scanNatureRemoDevices(Request $request)
    {
        try {
            // Get MAC address prefix for Nature Remo devices
            $macAddressPrefix = Manufacturer::where('MANUFACTURER_NAME', 'NatureRemo')
                ->first()->GW_VENDOR_ID;

            // Get list of discoverable gateways and devices in the network
            $response = shell_exec("/usr/sbin/arp -a");

            // Initialize $data variable
            $data = [];

            // Convert $response string to array
            $items = explode("\n", $response);

            // Loop each item in the $items array
            foreach ($items as $item) {

                // Initialize Nature Remo device flag
                $isNatureRemoDevice = false;

                // Convert string to array using the spaces
                $item = explode(" ", $item);

                // If the array does not have 7 elements, disregard the item
                if (count($item) == 7) {
                    $ipAddress = $item[1];
                    $macAddress = strtoupper($item[3]);

                    // Determine if the item is a Nature Remo device by inspecting the MAC address
                    if (str_split($macAddress, strlen($macAddressPrefix))[0] == $macAddressPrefix) {
                        $isNatureRemoDevice = true;
                    }

                    if ($isNatureRemoDevice) {
                        // Add the item to the Device Table if it is not yet registered

                        $gateway = Gateway::where('MANUFACTURER_ID', 7)->first();

                        if (Device::where('DEVICE_SERIAL_NO', str_replace(':', '', $macAddress))->count() == 0) {
                            $device = new Device();
                            $device->FLOOR_ID = $gateway->FLOOR_ID;
                            $device->ROOM_ID = $gateway->ROOM_ID;
                            $device->GATEWAY_ID = $gateway->GATEWAY_ID;
                            $device->MANUFACTURER_ID = $gateway->MANUFACTURER_ID;
                            $device->DEVICE_SERIAL_NO = str_replace(':', '', $macAddress);
                            $device->DEVICE_TYPE = "nature_remo";
                            $device->DEVICE_CATEGORY = 1;
                            $device->REG_FLAG = 0;
                            $device->ONLINE_FLAG = 1;
                            $device->save();

                            $data[] = $device;
                        }
                    }
                }
            }
            return response($data, 200);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name>Register Nature Remo Device<br>
     * <Function>Register a Nature Remo device and its associated appliances and signals<br>
     *           URL: http://localhost/registerNatureRemoDevice<br>
     *           METHOD: POST
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function registerNatureRemoDevice(Request $request)
    {
        try {
            // Check if all the required $request attributes is complete and valid
            $validRequest = isset(
                $request->DEVICE_ID,
                $request->DEVICE_CATEGORY,
                $request->DEVICE_NAME,
                $request->FLOOR_ID,
                $request->ROOM_ID
            ) &&
                ($request->DEVICE_NAME != '');

            // If the input parameters are invalid, return 400 response
            if (!$validRequest) {
                $ip = $request->ip();
                $host = auth()->user()->USERNAME;
                $module = 'Device Management';
                $instruction = 'Device Registration: Malformed Syntax';
                $this->auditLogs($ip, $host, $module, $instruction);
                return response('Invalid parameters', 400);
            }

            // Find and retrieve the Nature Remo device
            $natureRemoDevice = Device::find($request->DEVICE_ID);

            // If the device is already registered, return 400 response
            if ($natureRemoDevice->REG_FLAG == 1) {
                $ip = $request->ip();
                $host = auth()->user()->USERNAME;
                $module = 'Device Management';
                $instruction = 'Device Registration: Entity Already Registered';
                $this->auditLogs($ip, $host, $module, $instruction);
                return response('Entity is already registered', 400);
            }

            $natureRemoDevice->FLOOR_ID = $request->FLOOR_ID;
            $natureRemoDevice->ROOM_ID = $request->ROOM_ID;
            $natureRemoDevice->DEVICE_NAME = $request->DEVICE_NAME;
            $natureRemoDevice->DEVICE_CATEGORY = $request->DEVICE_CATEGORY;
            $natureRemoDevice->REG_FLAG = 1;
            $natureRemoDevice->ONLINE_FLAG = 1;
            $natureRemoDevice->save();

            // Insert Audit Logs
            $ip = $request->ip();
            $host = auth()->user()->USERNAME;
            $module = 'Device Management';
            $instruction = 'Updated a Device to be Registered';
            $this->auditLogs($ip, $host, $module, $instruction);

            return response('success', 200);

            // If any error occurs, return error message
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name>Get Nature Remo Devices<br>
     * <Function>Get all nature remo devices
     *           URL: http://localhost/getNatureRemoDevices<br>
     *           METHOD: GET
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception $e When an exception occurs in this process
     */
    public function getNatureRemoDevices(Request $request)
    {
        try {
            $query = Device::where('DEVICE_TYPE', 'nature_remo');
            if (isset($request->FLOOR_ID)) {
                $query->where('FLOOR_ID', $request->FLOOR_ID);
            }
            if (isset($request->ROOM_ID)) {
                $query->where('ROOM_ID', $request->ROOM_ID);
            }
            if (isset($request->GATEWAY_ID)) {
                $query->where('GATEWAY_ID', $request->GATEWAY_ID);
            }
            if (isset($request->REG_FLAG)) {
                $query->where('REG_FLAG', $request->REG_FLAG);
            }
            if (isset($request->ONLINE_FLAG)) {
                $query->where('ONLINE_FLAG', $request->ONLINE_FLAG);
            }
            if (isset($request->SELECT)) {
                foreach (explode(",", $request->SELECT) as $selected) {
                    $query->addSelect($selected);
                }
            }
            return response($query->get());
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name>Update Nature Remo Device Appliances<br>
     * <Function>Sync the many-to-many relation of Device to Appliances
     *           URL: http://localhost/updateNatureRemoDeviceAppliances<br>
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception $e When an exception occurs in this process
     */
    public function updateNatureRemoDeviceAppliances(Request $request)
    {
        $applianceIds = [];
        try {
            foreach ($request->APPLIANCES as $appliance) {
                $applianceIds[] = $appliance['APPLIANCE_ID'];
            }
            $device = Device::findOrFail($request->DEVICE_ID);
            $device->natureRemoAppliances()->sync($applianceIds);
            return response('success');
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    // /**
    //  * @todo Create an online status checker for Nature Remo Devices
    //  */
    // public function checkNatureRemoDeviceStatus()
    // {
    //     $prefix = Manufacturer::where('MANUFACTURER_NAME', 'NatureRemo')
    //         ->first()->GW_VENDOR_ID;
    //     $irDevices = Device::where('DEVICE_TYPE', 'nature_remo')->get();
    //     $response = shell_exec("/usr/sbin/arp -a");
    //     $items = explode("\n", $response);
    //     $items = array_filter($items, function ($item) use ($prefix) {
    //         return stripos($item, $prefix);
    //     });
    //     return $items;
    //     $items =
    // }

    public function getNatureRemoDevice(Request $request)
    {
        try {
            $ir = Device::with('natureRemoAppliances.natureRemoSignals')
                ->findOrFail($request->DEVICE_ID);
            return response($ir);
        } catch (\Throwable $th) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $th);
        }
    }
}
