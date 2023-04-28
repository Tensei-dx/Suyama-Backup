<?php

namespace App\Http\Controllers\NatureRemo;

use App\Http\Controllers\Controller;
use App\Models\NatureRemoSignal;
use App\Services\NatureRemoService;

/**
 * <Class Name> NatureRemoSignalController
 *
 * Create : 2021.10.28 TP Uddin<br>
 *
 * <Overview> A controller for NatureRemoSignal model <br>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoSignalController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // index            (1.0) Display a listing of the signal resource
    // show             (2.0) Display the specified signal resource
    // send             (3.0) Send the specified signal resource though cloud API

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> index <br>
     * <Function> Display a listing of the signal resource <br>
     *          URL: /nature_remo_signals/
     *          METHOD: GET
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = NatureRemoSignal::query();

        /**
         * allows "group" query parameter to
         * filter the signals with the SIGNAL_GROUP
         * attribute
         */
        if (request()->has('group')) {
            $query->ofGroup(request('group'));
        }

        /**
         * allows "show_appliance" query parameter to
         * display the appliance associated to the signal
         */
        if (request()->boolean('show_appliance')) {
            $query->with('natureRemoAppliance');
        }

        return response($query->get());
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> show <br>
     * <Function> Display the specified signal resource. <br>
     *          URL: /nature_remo_signals/:signal
     *          METHOD: GET
     *
     * @param  \App\NatureRemoSignal  $signal
     * @return \Illuminate\Http\Response
     */
    public function show(NatureRemoSignal $signal)
    {
        /**
         * allows "show_appliance" query parameter to
         * display the appliance associated to the signal
         */
        if (request()->boolean('show_appliance')) {
            $signal->load('natureRemoAppliance');
        }

        return response($signal);
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> send <br>
     * <Function> Send the specified signal resource though cloud API <br>
     *          URL: /nature_remo_signals/:signal/send
     *          METHOD: POST
     *
     * @param  \App\Services\NatureRemoService  $service
     * @param  \App\NatureRemoSignal  $signal
     */
    public function send(NatureRemoService $service, NatureRemoSignal $signal)
    {
        return $service->processCommand($signal);
    }
}
