<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\ManagementController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 *
 */
class ManagementController_remoteLockApiTest extends TestCase
{
    use AuthenticatesUsers;

    /**
     * @var ManagementController
     */
    protected $object;

    /**
     * setUp is executed before each test method is executed
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create an object to test
        $this->object = new ManagementController();
    }

    /**
     * Verification of the acquired data
     */
    public function test_remoteLockApi()
    {
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $request->AUTH_CODE = '56a7b50e08dca8385dfd80f9a23d78d2a5aca70c697c4ee7e1e41ef128e99561';
        $request->API_ID = 4;
        $request->FIRST_NAME = 'Russell';
        $request->LAST_NAME = 'Reyes';
        $request->CHECK_IN_TIME = '2021-01-20T07:26';
        $request->CHECK_OUT_TIME = '2021-01-23T07:26';
        $request->EMAIL = 'r-russell@tenseiph.com';
        $request->PIN = '1996';
        $request->DEVICE_ID = '1f3b223e-19be-4db1-9c02-91041f1c4b06';
        $expected = 'success';
        $device = $this->object->remoteLockApi($request);
        //   echo $device;
        $this->assertEquals($device, $expected);
    }
}
