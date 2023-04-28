<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

/**
 * <Class Name> ManufacturerController
 *
 * <Function Name> Manufacturer Processing<br>
 * Create : 2018.11.05 TP Robert<br>
 * Update : 2020.05.21 TP Uddin     Modify URL and Method names according to the URL List<br>
 *
 * <Overview> This controller is responsible for acquiring all the brand of the manufacturers.
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ManufacturerController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getManufacturerAll               (1.0) Get all manufacturer from database and display on screen

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all manufacturer<br>
     * <Function> Get all manufacturer from database and display on screen<br>
     *            URL: http://localhost/getManufacturerAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return Object $this->createGetResponse($request, (new Manufacturer())
     *         ->newQuery())
     */
    public function getManufacturerAll(Request $request)
    {
        return $this->createGetResponse(
            $request,
            (new Manufacturer())->newQuery()
        );
    }

    public function getAllManufacturerDevices()
    {
        return Manufacturer::where('MANUFACTURER_ID', 1)->with('devices')->get();
    }
}
