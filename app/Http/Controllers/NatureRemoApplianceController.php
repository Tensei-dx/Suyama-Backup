<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\NatureRemoAppliance;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

/**
 * <Class Name> NatureRemoApplianceController
 *
 * Create : 2021.06.09 TP Uddin
 * Update : 2021.06.10 TP Uddin         Add createNatureRemoAppliance method
 *                                      Add deleteNatureRemoAppliance method
 *          2021.07.08 TP Uddin         Execute storeLogs when error occurs
 *
 * <Overview>
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoApplianceController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getNatureRemoAppliances          (1.0) Get all appliances for Nature Remo devices
    // createNatureRemoAppliance        (2.0) Add new entry in Nature Remo Appliance table
    // deleteNatureRemoAppliance        (3.0) Delete a Nature Remo appliance

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name>Get Nature Remo Appliances<br>
     * <Function>Get all appliances for Nature Remo devices
     *           URL: http://localhost/getNatureRemoAppliances
     *           METHOD: GET
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @throws \Exception $e When an exception occurs in this process
     */
    public function getNatureRemoAppliances(Request $request)
    {
        try {
            // Initialize query
            $query = NatureRemoAppliance::query();
            // Select default columns
            $query->select('APPLIANCE_ID', 'APPLIANCE_NAME', 'APPLIANCE_TYPE', 'BRAND_NAME');
            // Add eloquent relations
            // Use '>' as delimiter for relations
            if (isset($request->WITH)) {
                foreach (explode(">", $request->WITH) as $relation) {
                    $query->with($relation);
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
     * <Layer number> (2.0)
     *
     * <Processing name>Create Nature Remo Appliance<br>
     * <Function>Add new entry in Nature Remo Appliance table
     *           URL: http://localhost/createNatureRemoAppliance
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function createNatureRemoAppliance(Request $request)
    {
        try {
            $appliance = new NatureRemoAppliance();
            $appliance->APPLIANCE_NAME = $request->APPLIANCE_NAME;
            $appliance->APPLIANCE_TYPE = $request->APPLIANCE_TYPE;
            $appliance->BRAND_NAME = $request->BRAND_NAME;
            $appliance->save();
            return response('success', 200);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name>Delete Nature Remo Appliance<br>
     * <Function>Delete a Nature Remo appliance and its signals
     *           URL: http://localhost/deleteNatureRemoAppliance
     *           METHOD: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable $e When an exception occurs in this process
     */
    public function deleteNatureRemoAppliance(Request $request)
    {
        try {
            $appliance = NatureRemoAppliance::findOrFail($request->APPLIANCE_ID);
            $appliance->devices()->detach();
            $appliance->natureRemoSignals()->delete();
            $appliance->delete();
            return response('success', 200);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }
}
