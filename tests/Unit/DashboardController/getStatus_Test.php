<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\DashboardController;
use Tests\TestCase;

/**
 *
 */
class DashboardController_getStatus extends TestCase
{
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
    public function test_getStatus()
    {
        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        // Expected value
        $expected = [
            [
                'totalGateway' => 2,
                'onlineGateway' => 2,
                'offlineGateway' => 0,
                'totalDevices' => 13,
                'onlineDevices' => 5,
                'offlineDevices' => 8
            ]
        ];

        $gateway = $this->object->getStatus($request);
        $this->assertEquals($gateway, $expected);
    }
}
