<?php

namespace App\Http\Controllers\RemoteLock;

use App\Http\Controllers\Controller;
use App\Http\Requests\RemoteLock\RegisterRequest;
use App\Http\Requests\RemoteLock\UpdateRequest;
use App\Models\Device;
use App\Models\Manufacturer;
use App\Models\Room;
use App\Services\RemoteLockService;

/**
 * <Class Name> RemoteLockDeviceController
 *
 * <Function Name> Remote Lock Device CRUD Operation<br>
 * Create : 2021.10.04 TP Uddin<br>
 * Update : 2022.01.17 TP Uddin [Task525] Added unlock function<br>
 *
 * <Overview> This controller handles CRUD operation for Remote Lock Device
 *              which utilizes the Remote Lock Cloud API.
 *
 * @package RemoteLock
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class RemoteLockDeviceController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // index        (1.0) Display a listing of the resource.
    // scan         (2.0) Retrieve list of resource from the Remote Lock Cloud API.
    // register     (3.0) Register the specified resource by completing the fields.
    // show         (4.0) Display the specified resource.
    // update       (5.0) Update the specified resource in storage.
    // destroy      (6.0) Remove the specified resource from storage.
    // unlock       (7.0) Unlocks the specified remote lock device.

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> index <br>
     * <Function> Display a listing of the resource.
     *            URL: /remotelock_devices
     *            METHOD: GET
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $remoteLockDevices = Device::ofType('remote_lock')
            ->with('room')
            ->get();

        return response($remoteLockDevices);
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> scan <br>
     * <Function> Retrieve list of resource from the Remote Lock Cloud API.
     *            URL: /remotelock_devices/scan
     *            METHOD: POST
     *
     * @return \Illuminate\Http\Response
     */
    public function scan()
    {
        $service = new RemoteLockService();

        $data = $service->scanDevices();

        $manufacturer = Manufacturer::firstWhere('MANUFACTURER_NAME', 'RemoteLock');

        $data->each(function ($item) use ($manufacturer) {

            // Retrieve device from Device table
            $device = Device::where('DEVICE_TYPE', 'remote_lock')
                ->where('DEVICE_SERIAL_NO', $item['attributes']['serial_number'])
                ->where('DATA->remote_lock_id', $item['id'])
                ->first();

            // If device exists, update online flag, then continue loop
            if ($device) {
                $device->ONLINE_FLAG = $item['attributes']['connected'];
                $device->POWER_LEVEL = $item['attributes']['power_level'];
                $device->save();
                return true;
            }

            // If device is new, save device as unregistered
            $device = new Device();
            $device->DEVICE_TYPE = 'remote_lock';
            $device->DEVICE_SERIAL_NO = $item['attributes']['serial_number'];
            $device->DATA = [
                'remote_lock_id' => $item['id'],
                'access_error_counter' => 0
            ];
            $device->DEVICE_CATEGORY = 1;
            $device->FLOOR_ID = null;
            $device->ROOM_ID = null;
            $device->REG_FLAG = 0;
            $device->ONLINE_FLAG = $item['attributes']['connected'];
            $device->POWER_LEVEL = $item['attributes']['power_level'];
            $device->manufacturer()->associate($manufacturer);
            $device->save();
        });

        return response()->noContent();
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> register <br>
     * <Function> Register the specified resource by completing the fields.
     *            URL: /remotelock_devices/:id/register
     *            METHOD: PATCH
     *
     * @param  \App\Http\Requests\RemoteLock\RegisterRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request, int $id)
    {
        $validated = $request->validated();

        try {
            $floorID = Room::findOrFail($validated['room_id'])->FLOOR_ID;

            $remoteLockDevice = Device::findOrFail($id);

            $remoteLockDevice->FLOOR_ID = $floorID;
            $remoteLockDevice->ROOM_ID = $validated['room_id'];
            $remoteLockDevice->DEVICE_NAME = $validated['device_name'];
            $remoteLockDevice->REG_FLAG = 1;
            $remoteLockDevice->save();

            return response()->noContent();
        } catch (\Throwable $th) {
            return response($th)->setStatusCode(500);
        }
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> update <br>
     * <Function> Update the specified resource in storage.
     *            URL: /remotelock_devices/:id
     *            METHOD: PATCH
     *
     * @param  \App\Http\Requests\RemoteLock\UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, int $id)
    {
        $validated = $request->validated();

        try {
            $floorID = Room::findOrFail($validated['room_id'])->FLOOR_ID;

            $remoteLockDevice = Device::findOrFail($id);

            $remoteLockDevice->FLOOR_ID = $floorID;
            $remoteLockDevice->ROOM_ID = $validated['room_id'];
            $remoteLockDevice->DEVICE_NAME = $validated['device_name'];
            $remoteLockDevice->save();

            return response()->noContent();
        } catch (\Throwable $th) {
            return response($th)->setStatusCode(500);
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> destroy <br>
     * <Function> Remove the specified resource from storage.
     *            URL: /remotelock_devices/:id
     *            METHOD: DELETE
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        Device::findOrFail($id)->delete();

        return response()->noContent();
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> unlock <br>
     * <Function> Unlocks the specified remote lock device.
     *            URL: /remotelock_devices/:id/unlock
     *            METHOD: POST
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unlock(int $id)
    {
        $device = Device::find($id);

        // check if device does not exists
        if (!$device) return response('failed', 400);

        $uuid = $device->DATA['remote_lock_id'];

        // check if device uuid is not set
        if (!$uuid) return response('failed', 400);

        $response = (new RemoteLockService)->unlockDevice($uuid);

        // check if the API request failed
        if ($response->failed()) return response('failed', 400);

        return response('success');
    }

    public function lock(int $id)
    {
        $device = Device::find($id);

        // check if device does not exists
        if (!$device) return response('failed', 400);

        $uuid = $device->DATA['remote_lock_id'];

        // check if device uuid is not set
        if (!$uuid) return response('failed', 400);

        $response = (new RemoteLockService)->lockDevice($uuid);

        // check if the API request failed
        if ($response->failed()) return response('failed', 400);

        return response('success');
    }
}
