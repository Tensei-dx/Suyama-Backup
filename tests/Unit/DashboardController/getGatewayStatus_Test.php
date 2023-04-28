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
class DashboardController_getGatewayStatus extends TestCase
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
    public function test_getGatewayStatus()
    {
        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        // Expected value
        $expected = [
            [
                'onlineGatewayIP' => '192.168.40.160',
                'onlineFloor' => 'Ground Floor',
                'onlineRoom' => 'ROOM 101'
            ],
            [
                'onlineGatewayIP' => '192.168.40.71',
                'onlineFloor' => 'All',
                'onlineRoom' => 'All'
            ],
        ];

        $gateway = $this->object->getGatewayStatus($request);
        $this->assertEquals($gateway, $expected);
    }
}
