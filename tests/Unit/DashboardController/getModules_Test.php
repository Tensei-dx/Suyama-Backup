<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 *
 */
class DashboardController_getModules extends TestCase
{
    use AuthenticatesUsers;

    /**
     * @var DashboardController
     */
    protected $object;

    /**
     * setUp is executed before each test method is executed
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create an object to test
        $this->object = new DashboardController();
    }

    /**
     * Verification of the acquired data
     */
    public function test_getModules()
    {
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);

        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        // Expected value
        $expected = [
            '0' => 'IR Management',
            '1' => 'Binding Management',
            '2' => 'Device Management',
            '3' => 'Gateway Management',
            '4' => 'Floor Management',
            '5' => 'User Management',
            '6' => 'Logs',
            'USERNAME' => 'admin'
        ];
        $modules = $this->object->getModules($request);
        $this->assertEquals($modules, $expected);
    }
}
