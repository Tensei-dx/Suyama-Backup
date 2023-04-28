<?php

namespace App\Http\Controllers\NatureRemo;

use App\Http\Controllers\Controller;
use App\Http\Requests\NatureRemoDevice\RegisterRequest;
use App\Http\Requests\NatureRemoDevice\UpdateRequest;
use App\Models\NatureRemoAccount;
use App\Models\NatureRemoDevice;
use App\Services\NatureRemoService;

/**
 * <Class Name> NatureRemoDeviceController
 *
 * Create : 2021.10.28 TP Uddin<br>
 *
 * <Overview> A controller for NatureRemoDevice model <br>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoDeviceController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // index        (1.0) Display a listing of the device resource
    // store        (2.0) Store a newly created device resource in storage
    // scan         (3.0) Retrieve list of device resource from the Nature Remo Cloud API
    // register     (4.0) Register the specified device resource by completing the fields
    // update       (5.0) Update the specified device resource in storage
    // show         (6.0) Display the specified device resource
    // destroy      (7.0) Remove the specified device resource from storage

    /**
     * <Layer number> (1.0)
     *
     * <Processing Name> index <br>
     * <Function> Display a listing of the device resource <br>
     *          URL: /nature_remo_devices/
     *          METHOD: GET
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = NatureRemoDevice::query();

        /**
         * allows boolean query parameter "new" to
         * filter the devices with the REG_FLAG attribute
         */
        if (request()->boolean('registered')) {
            $query->registered(!request('new'));
        }

        /**
         * allows "show_account=true" query parameter to
         * display the account associated to the device
         */
        if (request()->boolean('show_account')) {
            $query->with('natureRemoAccount');
        }

        /**
         * allows "show_room=true" query parameter to
         * display the room associated to the device
         */
        if (request()->boolean('show_room')) {
            $query->with('room');
        }

        /**
         * allows "show_appliances_count=true" query paramater to
         * display the number of appliances associated to the device
         */
        if (request()->boolean('show_appliances_count')) {
            $query->withCount('natureRemoAppliances');
        }

        /**
         * allows "show_appliances=true" query parameter to
         * display the appliances associated to the device
         */
        if (request()->boolean('show_appliances')) {
            $query->with('natureRemoAppliances');
        }

        /**
         * allows "show_signals_count=true" query paramater to
         * display the number of signals associated to the device
         */
        if (request()->boolean('show_signals_count')) {
            $query->withCount('natureRemoSignals');
        }

        /**
         * allows "show_signals=true" query parameter to
         * display the appliances associated to the device
         */
        if (request()->boolean('show_signals')) {
            $query->with('natureRemoSignals');
        }

        return response($query->get());
    }

    /**
     * <Layer number> (2.0)
     * 
     * <Processing name> store <br>
     * <Function> Store a newly created device resource in storage <br>
     * 
     * @access private
     * @param  \App\NatureRemoAccount  $account
     * @param  array  $item
     * @return \Illuminate\Http\Response
     */
    private function store(NatureRemoAccount $account, array $item)
    {
        $account->natureRemoDevices()->create([
            'DEVICE_UUID' => $item['id'],
            'DEVICE_NAME' => $item['name'],
            'DEVICE_SERIAL_NO' => $item['serial_number'],
            'MAC_ADDRESS' => $item['mac_address'],
            'DATA' => null,
            'NEW_FLAG' => true
        ]);

        return response()->noContent();
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing Name> scan <br>
     * <Function> Retrieve list of device resource from the Nature Remo Cloud API <br>
     *          URL: /nature_remo_devices/scan
     *          METHOD: POST
     *
     * @param  \App\Services\NatureRemoService  $service
     * @return \Illuminate\Http\Response
     */
    public function scan(NatureRemoService $service)
    {
        $accounts = NatureRemoAccount::all();

        // Update NEW_FLAG attribute of all devices to false
        $devices = NatureRemoDevice::all()->each->update(['NEW_FLAG' => false]);

        foreach ($accounts as $account) {
            // Retrieve all device data from all the Nature Remo Cloud Account
            $response = $service->fetchDeviceData($account->ACCESS_TOKEN);

            // Skip to the next loop if the response failed
            if ($response->failed()) {
                continue;
            }

            // Get the response data from the API
            $data = $response->json();

            foreach ($data as $item) {
                $device = $devices->firstWhere('DEVICE_UUID', $item['id']);
                // If the device already exists, update NEW_FLAG to true then skip to the next loop
                if ($device) {
                    $device->update(['NEW_FLAG' => true]);
                    continue;
                }

                // Store the new devices
                $this->store($account, $item);
            }
        }

        // Get all devices that has NEW_FLAG as false
        $oldDevices = NatureRemoDevice::removed()->get();

        foreach ($oldDevices as $oldDevice) {
            // delete the old device
            $this->destroy($oldDevice);
        }

        return response()->noContent();
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> register <br>
     * <Function> Register the specified device resource by completing the fields <br>
     *          URL: /nature_remo_devices/:device/register
     *          METHOD: PUT
     *
     * @param  \App\Http\Requests\NatureRemoDevice\RegisterRequest  $request
     * @param  \App\NatureRemoDevice  $device
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request, NatureRemoDevice $device)
    {
        $device->update(
            $request->validated() + ['REG_FLAG' => true]
        );

        return response()->noContent();
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> update <br>
     * <Function> Update the specified device resource in storage <br>
     *          URL: /nature_remo_devices/:device
     *          METHOD: PUT
     *
     * @param  \App\Http\Requests\NatureRemoDevice\UpdateRequest  $request
     * @param  \App\NatureRemoDevice  $device
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, NatureRemoDevice $device)
    {
        $device->update(
            $request->validated() + ['REG_FLAG' => true]
        );

        return response()->noContent();
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> show <br>
     * <Function> Display the specified device resource <br>
     *          URL: /nature_remo_devices/:device
     *          METHOD: GET
     *
     * @param  \App\NatureRemoDevice  $device
     * @return \Illuminate\Http\Response
     */
    public function show(NatureRemoDevice $device)
    {
        /**
         * allows "show_account=true" query parameter to
         * display the account associated to the device
         */
        if (request()->boolean('show_account')) {
            $device->load('natureRemoAccount');
        }

        /**
         * allows "show_room=true" query parameter to
         * display the room associated to the device
         */
        if (request()->boolean('show_room')) {
            $device->load('room');
        }

        /**
         * allows "show_appliances_count=true" query paramater to
         * display the number of appliances associated to the device
         */
        if (request()->boolean('show_appliances_count')) {
            $device->loadCount('natureRemoAppliances');
        }

        /**
         * allows "show_appliances=true" query parameter to
         * display the appliances associated to the device
         */
        if (request()->boolean('show_appliances')) {
            $device->load('natureRemoAppliances');
        }

        /**
         * allows "show_signals_count=true" query paramater to
         * display the number of signals associated to the device
         */
        if (request()->boolean('show_signals_count')) {
            $device->loadCount('natureRemoSignals');
        }

        /**
         * allows "show_signals=true" query parameter to
         * display the appliances associated to the device
         */
        if (request()->boolean('show_signals')) {
            $device->load('natureRemoSignals');
        }

        return response($device);
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> destroy <br>
     * <Function> Remove the specified device resource from storage <br>
     *
     * @access private
     * @param  \App\NatureRemoDevice  $device
     * @return \Illuminate\Http\Response
     */
    private function destroy(NatureRemoDevice $device)
    {
        // this will also delete appliances and signals
        // associated to the device because they are cascaded
        $device->delete();

        return response()->noContent();
    }
}
