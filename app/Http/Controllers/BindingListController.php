<?php
/*
* <System Name> iBMS
*/

namespace App\Http\Controllers;

use App\Models\BindingList;
use App\Models\Device;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;

/**
 * <Class Name> BindingListController
 *
 * <Function Name> Binding List Management and Processing
 * Create : 2018.07.27 TP Bryan<br>
 * Update : 2018.08.13 TP Bryan    Fixed coding standard, Added functions
 *          2018.08.20 TP Bryan    Fixed code structure<br>
 *
 * <Overview> This controller is responsible for managing binding list details
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BindingListController extends Controller
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // getBindingListAll                (1.0) Retrieve all binding list from database
    // getBindingList                   (2.0) Retrieve binding list from database
    // getBindingListBindings           (3.0) Retrieve binding associated with the binding list
    // getBindingListSourceDevices      (4.0) Retrieve devices that match this binding list trigger condition
    // getBindingListTargetDevices      (5.0) Retrieve devices that match this binding list control condition

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all binding list<br>
     * <Function> Retrieve all binding list from database<br>
     *            URL: http://localhost/binding-list<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $bindingListAll
     */
    public function getBindingListAll(Request $request): object
    {
        $bindingListAll = $this->createGetResponse(
            $request,
            BindingList::with(
                'bindings.sourceDevice',
                'bindings.targetDevice:DEVICE_ID,DEVICE_TYPE,DEVICE_NAME'
            )
                ->has('sourceDevices')
        );
        return $bindingListAll;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get a binding list<br>
     * <Function> Retrieve a binding list from database<br>
     *            URL: http://localhost/binding-list/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $bindingList
     */
    public function getBindingList(Request $request, int $id): object
    {
        $bindingList = BindingList::findOrFail($id);
        return $bindingList;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get bindings in the binding list<br>
     * <Function> Retrieve binding information associated with the binding list<br>
     *            URL: http://localhost/binding-list/:id/bindings<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $bindingList
     */
    public function getBindingListBindings(Request $request, int $id): object
    {
        $bindingList = BindingList::with(
            'bindings.sourceDevice',
            'bindings.targetDevice'
        )
            ->findOrFail($id);
        return $bindingList;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Get binding list's source devices<br>
     * <Function> Retrieve devices that match this binding list trigger condition<br>
     *            URL: http://localhost/binding-list/:id/source-devices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object BindingList::findOrFail($id)->sourceDevices;
     */
    public function getBindingListSourceDevices(Request $request, int $id): object
    {
        $bindingListSourceDevices = BindingList::findOrFail($id)->sourceDevices;
        return $bindingListSourceDevices;
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Get binding list's target devices<br>
     * <Function> Retrieve devices that match this binding list control condition<br>
     *            URL: http://localhost/binding-list/:id/target-devices<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $this->createGetResponse($request, Device::where('DEVICE_TYPE',$bindingList))
     */
    public function getBindingListTargetDevices(Request $request, int $id): object
    {
        $bindingList = BindingList::findOrFail($id)->TARGET_DEVICE_TYPE;
        $bindingListTargetDevices = $this->createGetResponse(
            $request,
            Device::where('DEVICE_TYPE', $bindingList)
        );
        return $bindingListTargetDevices;
    }
}
