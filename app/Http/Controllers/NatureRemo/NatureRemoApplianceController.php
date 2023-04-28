<?php

namespace App\Http\Controllers\NatureRemo;

use App\Http\Controllers\Controller;
use App\Models\NatureRemoAccount;
use App\Models\NatureRemoAppliance;
use App\Models\NatureRemoDevice;
use App\Services\NatureRemoService;

/**
 * <Class Name> NatureRemoApplianceController
 *
 * Create : 2021.10.28 TP Uddin<br>
 *
 * <Overview> A controller for NatureRemoAppliance model <br>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoApplianceController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // index                        (1.0) Display a listing of the appliance resource
    // store                        (2.0) Store a newly created Nature Remo appliance resource in storage
    // scan                         (3.0) Retrieve list of appliance resource from the Nature Remo Cloud API
    // show                         (4.0) Display the specified appliance resource
    // destroy                      (5.0) Remove the specified appliance resource from the storage

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> index <br>
     * <Function> Display a listing of the appliance resource.
     *          URL: /nature_remo_appliances/
     *          METHOD: GET
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = NatureRemoAppliance::query();

        /**
         * allows "type" query parameter to
         * filter the appliances with the APPLIANCE_TYPE
         * attribute
         */
        if (request()->has('type')) {
            $query->ofType(request('type'));
        }

        /**
         * allows "show_device=true" query parameter to
         * display the device associated to the appliance
         */
        if (request()->boolean('show_device')) {
            $query->with('natureRemoDevice');
        }

        /**
         * allows "show_device_room=true" query parameter to
         * display the room and the device associated to the appliance
         */
        if (request()->boolean('show_device_room')) {
            $query->with('natureRemoDevice.room');
        }

        /**
         * allows "show_signals_count=true" query paramater to
         * display the number of signals associated to the appliance
         */
        if (request()->boolean('show_signals_count')) {
            $query->withCount('natureRemoSignals');
        }

        /**
         * allows "show_signals=true" query parameter to
         * display the signals associated to the appliance
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
     * <Function> Store a newly created Nature Remo appliance resource in storage <br>
     *
     * @access private
     * @param  \App\NatureRemoDevice  $device
     * @param  array  $item
     * @return \App\NatureRemoAppliance
     */
    private function store(NatureRemoDevice $device, array $item)
    {
        return $device->natureRemoAppliances()->create([
            'APPLIANCE_UUID' => $item['id'],
            'APPLIANCE_TYPE' => $item['type'],
            'APPLIANCE_NAME' => $item['nickname'],
            'APPLIANCE_SETTINGS' => $item['settings'],
            'NEW_FLAG' => true
        ]);
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> scan <br>
     * <Function> Retrieve list of appliance resource from the Nature Remo Cloud API <br>
     *          URL: /nature_remo_appliances/scan
     *          METHOD: POST
     *
     * @param  \App\Services\NatureRemoService  $service
     * @return \Illuminate\Http\Response
     */
    public function scan(NatureRemoService $service)
    {
        $accounts = NatureRemoAccount::all();
        $devices = NatureRemoDevice::all();

        // Update NEW_FLAG attribute of all appliances to false
        $appliances = NatureRemoAppliance::all()->each->update(['NEW_FLAG' => false]);

        foreach ($accounts as $account) {
            // Retrieve all appliance data from all the Nature Remo Cloud Account
            $response = $service->fetchApplianceData($account->ACCESS_TOKEN);

            // Skip to the next loop if the response failed
            if ($response->failed()) {
                continue;
            }

            // Get the response data from the API
            $data = $response->json();

            foreach ($data as $item) {
                // If the device of the appliance does not exists, skip to the next loop.
                $device = $devices->firstWhere('DEVICE_UUID', $item['device']['id']);
                if (!$device) {
                    continue;
                }

                // If the appliance was already exists, update NEW_FLAG to true then skip to the next loop.
                $appliance = $appliances->firstWhere('APPLIANCE_UUID', $item['id']);
                if ($appliance) {
                    $appliance->update(['NEW_FLAG' => true]);
                    continue;
                }

                // Store the appliance
                $appliance = $this->store($device, $item);

                // Extract and store the available signals from the appliance according to their type
                switch ($item['type']) {
                    case 'IR':
                        $service->storeInfraredSignals($appliance, $item);
                        break;

                    case 'TV':
                        $service->storeTelevisionSignals($appliance, $item);
                        break;

                    case 'LIGHT':
                        $service->storeLightSignals($appliance, $item);
                        break;

                    case 'AC':
                        $service->storeAirConditionerSignals($appliance, $item);
                        break;
                }
            }
        }

        // Get all appliances that has NEW_FLAG as false
        $oldAppliances = NatureRemoAppliance::removed()->get();

        foreach ($oldAppliances as $oldAppliance) {
            $this->destroy($oldAppliance);
        }

        return response()->noContent();
    }

    /**
     * <Layer number (4.0)
     *
     * <Processing name> show <br>
     * <Function> Display the specified appliance resource <br>
     *          URL: /nature_remo_appliances/:appliance
     *          METHOD: GET
     *
     * @param  \App\NatureRemoAppliance  $appliance
     * @return \Illuminate\Http\Response
     */
    public function show(NatureRemoAppliance $appliance)
    {
        /**
         * allows "show_device=true" query parameter to
         * display the device associated to the appliance
         */
        if (request()->boolean('show_device')) {
            $appliance->load('natureRemoDevice');
        }

        /**
         * allows "show_device_room=true" query parameter to
         * display the room and the device associated to the appliance
         */
        if (request()->boolean('show_device_room')) {
            $appliance->load('natureRemoDevice.room');
        }

        /**
         * allows "show_signals_count=true" query paramater to
         * display the number of signals associated to the appliance
         */
        if (request()->boolean('show_signals_count')) {
            $appliance->loadCount('natureRemoSignals');
        }

        /**
         * allows "show_signals=true" query parameter to
         * display the signals associated to the appliance
         */
        if (request()->boolean('show_signals')) {
            $appliance->load('natureRemoSignals');
        }

        return response($appliance);
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> destroy <br>
     * <Function> Remove the specified appliance resource from storage <br>
     *
     * @access private
     * @param  \App\NatureRemoAppliance  $appliance
     * @return \Illuminate\Http\Response
     */
    private function destroy(NatureRemoAppliance $appliance)
    {
        // this will also delete the signals associated
        // to the appliance because they are cascaded
        $appliance->delete();

        return response()->noContent();
    }

    public function getSignals(NatureRemoAppliance $appliance)
    {
        return $appliance->natureRemoSignals()->get();
    }
}
