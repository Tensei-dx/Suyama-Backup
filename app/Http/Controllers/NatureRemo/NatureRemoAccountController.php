<?php

namespace App\Http\Controllers\NatureRemo;

use App\Http\Controllers\Controller;
use App\Http\Requests\NatureRemoAccount\StoreRequest;
use App\Models\NatureRemoAccount;

/**
 * <Class Name> NatureRemoAccountController
 *
 * Create : 2021.10.28 TP Uddin<br>
 *
 * <Overview> A controller for NatureRemoAccount model <br>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoAccountController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // index        (1.0) Display a listing of the Nature Remo Account resource
    // store        (2.0) Store a newly created Nature Remo Account resource in storage
    // show         (3.0) Display the specified Nature Remo Account resource
    // destroy      (4.0) Remove the specified Nature Remo Account resource from storage

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> index <br>
     * <Function> Display a listing of the Nature Remo Account resource <br>
     *          URL: /nature_remo_accounts/
     *          METHOD: GET
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = NatureRemoAccount::query();

        /**
         * allows "devices_count=true" query parameter to
         * display the number of devices associated to the account
         */
        if (request()->boolean('show_devices_count')) {
            $query->withCount('natureRemoDevices');
        }

        /**
         * allows "devices=true" query parameter to
         * display the devices associated to the account
         */
        if (request()->boolean('show_devices')) {
            $query->with('natureRemoDevices');
        }

        /**
         * allows "appliances_count=true" query parameter to
         * display the number of appliances associated to the account
         */
        if (request()->boolean('show_appliances_count')) {
            $query->withCount('natureRemoAppliances');
        }

        /**
         * allows "appliances=true" query parameter to
         * display the appliances associated to the account
         */
        if (request()->boolean('show_appliances_count')) {
            $query->with('natureRemoAppliances');
        }

        return response($query->get());
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> store <br>
     * <Function> Store a newly created Nature Remo Account resource in storage <br>
     *          URL: /nature_remo_accounts/
     *          METHOD: POST
     *
     * @param  \App\Http\Requests\NatureRemoAccount\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        NatureRemoAccount::create($request->validated());

        return response()->noContent();
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> show <br>
     * <Function> Display the specified Nature Remo Account resource <br>
     *          URL: /nature_remo_accounts/:account
     *          METHOD: GET
     *
     * @param  \App\NatureRemoAccount  $account
     * @return \Illuminate\Http\Response
     */
    public function show(NatureRemoAccount $account)
    {
        /**
         * allows "devices_count=true" query parameter to
         * display the number of devices associated to the account
         */
        if (request()->boolean('show_devices_count')) {
            $account->loadCount('natureRemoDevices');
        }

        /**
         * allows "devices=true" query parameter to
         * display the devices associated to the account
         */
        if (request()->boolean('show_devices')) {
            $account->load('natureRemoDevices');
        }

        /**
         * allows "appliances_count=true" query parameter to
         * display the number of appliances associated to the account
         */
        if (request()->boolean('show_appliances_count')) {
            $account->loadCount('natureRemoAppliances');
        }

        /**
         * allows "appliances=true" query parameter to
         * display the appliances associated to the account
         */
        if (request()->boolean('show_appliances_count')) {
            $account->load('natureRemoAppliances');
        }

        return response($account);
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> destroy <br>
     * <Function> Remove the specified Nature Remo Account resource from storage <br>
     *          URL: /nature_remo_accounts/:account
     *          METHOD: DELETE
     *
     * @param  \App\NatureRemoAccount  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(NatureRemoAccount $account)
    {
        // this will also delete the devices, appliances, and signals
        // associated to the account because there are cascaded
        $account->delete();

        return response()->noContent();
    }
}
