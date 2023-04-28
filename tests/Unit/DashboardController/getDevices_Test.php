<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\DashboardController;
use App\Models\Device;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 *
 */
class DashboardController_getDevices extends TestCase
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
    public function test_getDevices()
    {
        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        // Expected value
        $expected = [
            [
                'status' => 'Online Device/s',
                'count' => 5,
                'color' => '#28a745'
            ],
            [
                'status' => 'Offline Device/s',
                'count' => 8,
                'color' => '#cc0000'
            ]
        ];

        $device = $this->object->getDevices($request);
        $this->assertEquals($device, $expected);
    }
}
