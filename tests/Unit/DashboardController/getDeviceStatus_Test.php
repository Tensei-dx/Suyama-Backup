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
class DashboardController_getDeviceStatus extends TestCase
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
    public function test_getDeviceStatus()
    {
        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        // Expected value
        $expected = [
            [
                'onlineDevice' => '',
                'count' => null
            ],
            [
                'onlineDevice' => '',
                'count' => null
            ],
            [
                'onlineDevice' => '',
                'count' => null
            ],
            [
                'onlineDevice' => '',
                'count' => null
            ],
            [
                'onlineDevice' => '',
                'count' => null
            ],
            [
                'offlineDevice' => 'Wall Switch 2',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],
            [
                'offlineDevice' => 'Motion Detector',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],

            [
                'offlineDevice' => 'Co2 Detector',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],
            [
                'offlineDevice' => 'Dust Detector',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],
            [
                'offlineDevice' => 'H2o Detector',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],
            [
                'offlineDevice' => 'Panic Button',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],

            [
                'offlineDevice' => 'Embedded Switch 2',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ],

            [
                'offlineDevice' => 'Light Detector',
                'offlineFloor' => 'Ground Floor',
                'offlineRoom' => 'ROOM 101'
            ]
        ];

        $device = $this->object->getDeviceStatus($request);
        $this->assertEquals($device, $expected);
    }
}
