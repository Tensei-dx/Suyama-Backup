<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\NatureRemoSignal;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * <Class Name> NatureRemoSignalController
 *
 * Create : 2021.06.09 TP Uddin
 * Update : 2021.06.11 TP Uddin         Update getNatureRemoSignals method
 *          2021.06.21 TP Uddin         Add deleteNatureRemoSignal method
 *          2021.06.23 TP Uddin         Add fetchNatureRemoSignalData method
 *          2021.06.23 TP Uddin         Add testNatureRemoSignalData method
 *          2021.07.08 TP Uddin         Execute storeLogs when error occurs
 *
 * <Overview>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoSignalController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getNatureRemoSignals             (1.0) Get all signals for Nature Remo devices
    // fetchNatureRemoSignalData        (2.0) Fetch the signal data using the Nature Remo local API
    // testNatureRemoSignalData         (3.0) Send the signal data to test operation using the Nature Remo local API
    // createNatureRemoSignal           (4.0) Create a new nature remo signal
    // deleteNatureRemoSignal           (5.0) Delete a nature remo signal

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name>Get Nature Remo Signals<br>
     * <Function>Get all signals for Nature Remo devices
     *           URL: http://localhost/getNatureRemoSignals
     *           METHOD: GET
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception $e When an exception occurs in this process
     */
    public function getNatureRemoSignals(Request $request)
    {
        try {
            // Initialize query
            $query = NatureRemoSignal::query();
            // Select default columns
            $query->select('SIGNAL_ID', 'SIGNAL_NAME', 'APPLIANCE_ID');
            // filter query by APPLIANCE_ID
            if (isset($request->APPLIANCE_ID)) {
                $query->where('APPLIANCE_ID', $request->APPLIANCE_ID);
            }
            // add relation to the query
            if (isset($request->WITH)) {
                foreach (explode(">", $request->WITH) as $relation) {
                    $query->with($relation);
                }
            }
            // add column to the query
            if (isset($request->SELECT)) {
                foreach (explode(",", $request->SELECT) as $selected) {
                    $query->addSelect($selected);
                }
            }
            return response($query->get());
        } catch (\Illuminate\Database\QueryException $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name>fetchNatureRemoSignalData<br>
     * <Function>Fetch the signal data using the Nature Remo local API
     *           URL: http://localhost/fetchNatureRemoSignalData
     *           METHOD: GET
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function fetchNatureRemoSignalData(Request $request)
    {
        try {
            $irDevice = Device::findOrFail($request->DEVICE_ID);
            if ($irDevice->gateway) {
                $gatewayIp = $irDevice->gateway->GATEWAY_IP;
                $response = Http::timeout(60)
                    ->withHeaders([
                        'X-Requested-With' => 'local',
                        'Accept' => 'application/json'
                    ])
                    ->get("http://$gatewayIp/messages");
                return response($response->json());
            } else {
                throw new \Exception('0:IR Device is not registered to any gateway.', 210050002);
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name>testNatureRemoSignalData<br>
     * <Function>Send the signal data to test operation using the Nature Remo local API
     *           URL: http://localhost/testNatureRemoSignalData
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function testNatureRemoSignalData(Request $request)
    {
        try {
            $irDevice = Device::findOrFail($request->DEVICE_ID);
            $irData = $request->DATA;
            if ($irDevice->gateway) {
                $gatewayIp = $irDevice->gateway->GATEWAY_IP;
                $response = Http::timeout(60)
                    ->withHeaders([
                        'X-Requested-With' => 'local',
                    ])
                    ->post("http://$gatewayIp/messages", [
                        'freq' => $irData['freq'],
                        'data' => $irData['data'],
                        'format' => $irData['format']
                    ]);
                return response($response->json());
            } else {
                throw new \Exception('0:IR Device is not registered to any gateway.', 210050002);
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name>createNatureRemoSignal<br>
     * <Function>Create a new nature remo signal
     *           URL: http://localhost/createNatureRemoSignal
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory'
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function createNatureRemoSignal(Request $request)
    {
        try {
            $signal = new NatureRemoSignal();
            $signal->APPLIANCE_ID = $request->APPLIANCE_ID;
            $signal->SIGNAL_NAME = $request->SIGNAL_NAME;
            $signal->SIGNAL_DATA = json_encode($request->SIGNAL_DATA);
            $signal->save();
            return response('success');
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name>deleteNatureRemoSignal<br>
     * <Function>Send the signal data to test operation using the Nature Remo local API
     *           URL: http://localhost/deleteNatureRemoSignal
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function deleteNatureRemoSignal(Request $request)
    {
        try {
            $signal = NatureRemoSignal::findOrFail($request->SIGNAL_ID);
            $signal->delete();
            return response('success', 200);
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * @todo complete the method later
     */
    public function sendNatureRemoSignal(Request $request)
    {
        try {
            $natureRemo = Device::where('DEVICE_TYPE', 'nature_remo')
                ->where('ROOM_ID', $request->ROOM_ID)
                ->with('gateway')
                ->firstOrFail();
            $ip = $natureRemo->gateway->GATEWAY_IP;
            $signal = json_decode(NatureRemoSignal::findOrFail($request->SIGNAL_ID)->SIGNAL_DATA, true);
            $response = Http::timeout(60)
                ->withHeaders([
                    'X-Requested-With' => 'local',
                ])
                ->post("http://$ip/messages", [
                    'freq' => $signal['freq'],
                    'data' => $signal['data'],
                    'format' => $signal['format']
                ]);
            return response($response->json());
        } catch (\Exception $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }
}
