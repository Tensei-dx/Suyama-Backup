<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\Appliances;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * <Class Name> ApplianceController
 *
 * <Function Name> Appliance Operation<br>
 * Create : 2019.06.25 TP Yani<br>
 * Update : 2020.05.18 TP Uddin  Modify URLs and method names according to URL一覧.xlsx
 *          2020.05.20 TP Uddin  Removed unnecessary logic and declaration<br>
 *
 * <Overview> This controller is responsible for retrieving appliance list,
 *            create new appliance and delete specific appliance
 * @package Controller
 * @author TP Yani <l-yani-tp@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ApplianceController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // getAllAppliances                 (1.0) Get all appliance list from the database and display on the screen
    // createAppliance                  (2.0) Create a new appliance entry and save it to the database
    // deleteAppliance                  (3.0) Delete a specific appliance from the database

    use AuthenticatesUsers;
    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all appliance data<br>
     * <Function> Get all appliance list from the database and display on the screen<br>
     *            URL: http://localhost/getAllAppliances<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return Object $applianceList
     * @throws Throwable When an exception occurs in this process
     */
    public function getAllAppliances(Request $request)
    {
        try {
            $applianceList = Appliances::with('IrLearning')->get();
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
        return $applianceList;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Create new appliance<br>
     * <Function> Create a new appliance entry and save it to the database<br>
     *            URL: http://localhost/createAppliance<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return String "success" or "existing"
     * @throws Throwable When an exception occurs in this process
     */
    public function createAppliance(Request $request)
    {
        $appliances = $request->APPLIANCE_LIST;
        try {
            foreach ($appliances as $appliance) {
                $applianceExist = Appliances::where(
                    'APPLIANCE_NAME',
                    $appliances['APPLIANCE_NAME']
                )
                    ->where('APPLIANCE_TYPE', $appliances['APPLIANCE_TYPE'])
                    ->where('BRAND_NAME', $appliances['BRAND_NAME'])
                    ->count();
                if ($applianceExist == 0) {
                    $appliancesList = new Appliances();
                    $appliancesList->APPLIANCE_NAME =
                        $appliances['APPLIANCE_NAME'];
                    $appliancesList->APPLIANCE_TYPE =
                        $appliances['APPLIANCE_TYPE'];
                    $appliancesList->BRAND_NAME =
                        $appliances['BRAND_NAME'];
                    $appliancesList->save();
                    return 'success';
                } else {
                    return 'existing';
                }
            }
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Delete a appliance<br>
     * <Function> Delete a specific appliance from the database<br>
     *            URL: http://localhost/deleteAppliance<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return String "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteAppliance(Request $request)
    {
        $applianceLists = $request->APPLIANCE_LIST;
        try {
            $deleteRecord = Appliances::where(
                'APPLIANCE_NAME',
                $applianceLists['APPLIANCE_NAME']
            )
                ->where('APPLIANCE_TYPE', $applianceLists['APPLIANCE_TYPE'])
                ->where('BRAND_NAME', $applianceLists['BRAND_NAME'])
                ->first();
            $deleteRecord->IrLearning()->delete();
            $deleteRecord->delete();
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
            return 'failed';
        }
    }
}
