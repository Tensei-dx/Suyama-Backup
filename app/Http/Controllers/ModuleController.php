<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\SystemModule;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

/**
 * <Class Name> ModuleController
 *
 * <Function Name> Module Processing<br>
 * Create : 2018.11.15 TP Raymond<br>
 * Update : 2020.05.21 TP Uddin     Modify URL and Methodname according to the URL list<br>
 *
 * <Overview> This controller is responsible for acquiring all modules
 *            from the database 
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ModuleController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    //getModuleAll                      (1.0) Retrieve all Modules

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all module<br>
     * <Function> Get all modules from the database and display on the screen<br>
     *            URL: http://localhost/getModuleAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $this->createGetResponse($request, (new SystemModule())
     *                ->newQuery())
     */
    public function getModuleAll(Request $request)
    {
        return $this->createGetResponse(
            $request,
            (new SystemModule())->newQuery()
        );
    }
}
