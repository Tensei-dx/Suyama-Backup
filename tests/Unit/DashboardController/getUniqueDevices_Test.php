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
class DashboardController_getUniqueDevices extends TestCase
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
    public function test_getUniqueDevices()
    {
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);

        /**********************************************************************/
        /* Both FLOOR_ID and ROOM_ID is set                                   */
        /**********************************************************************/

        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->FLOOR_ID = 1;
        $request->ROOM_ID = 1;

        // Expected value
        $expected = [
            [
                'DEVICE_ID' => 1,
                'DEVICE_TYPE' => 'wall_switch_3',
                'DEVICE_NAME' => '3 Gang SW GA',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 3,
                'DEVICE_TYPE' => 'wall_switch_2',
                'DEVICE_NAME' => 'Switch 2',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 4,
                'DEVICE_TYPE' => 'motion_detector',
                'DEVICE_NAME' => 'Motion_SR',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 5,
                'DEVICE_TYPE' => 'co2_detector',
                'DEVICE_NAME' => 'Dummy CO2',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 6,
                'DEVICE_TYPE' => 'dust_detector',
                'DEVICE_NAME' => 'Dummy Dust',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 9,
                'DEVICE_TYPE' => 'h2o_detector',
                'DEVICE_NAME' => 'Dummy H2O Detector',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 10,
                'DEVICE_TYPE' => 'panic_button',
                'DEVICE_NAME' => 'Dummy Panic Button',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 14,
                'DEVICE_TYPE' => 'embedded_switch_2',
                'DEVICE_NAME' => 'Office Embedded SW',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213219,
                'DEVICE_TYPE' => 'ir_remote',
                'DEVICE_NAME' => 'IR',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213225,
                'DEVICE_TYPE' => 'temp_hum',
                'DEVICE_NAME' => 'temp hum',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213229,
                'DEVICE_TYPE' => 'people_counter',
                'DEVICE_NAME' => 'M3065-V',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],

            [
                'DEVICE_ID' => 213213230,
                'DEVICE_TYPE' => 'light_detector',
                'DEVICE_NAME' => 'dummy light detector',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ]
        ];
        $device = $this->object->getUniqueDevices($request);
        $this->assertEquals(json_encode($device), json_encode($expected));


        /**********************************************************************/
        /* Only FLOOR_ID is set                                               */
        /**********************************************************************/

        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->FLOOR_ID = 1;

        // Expected value
        $expected = [
            [
                'DEVICE_ID' => 1,
                'DEVICE_TYPE' => 'wall_switch_3',
                'DEVICE_NAME' => '3 Gang SW GA',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 3,
                'DEVICE_TYPE' => 'wall_switch_2',
                'DEVICE_NAME' => 'Switch 2',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 4,
                'DEVICE_TYPE' => 'motion_detector',
                'DEVICE_NAME' => 'Motion_SR',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 5,
                'DEVICE_TYPE' => 'co2_detector',
                'DEVICE_NAME' => 'Dummy CO2',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 6,
                'DEVICE_TYPE' => 'dust_detector',
                'DEVICE_NAME' => 'Dummy Dust',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 9,
                'DEVICE_TYPE' => 'h2o_detector',
                'DEVICE_NAME' => 'Dummy H2O Detector',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 10,
                'DEVICE_TYPE' => 'panic_button',
                'DEVICE_NAME' => 'Dummy Panic Button',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 14,
                'DEVICE_TYPE' => 'embedded_switch_2',
                'DEVICE_NAME' => 'Office Embedded SW',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213219,
                'DEVICE_TYPE' => 'ir_remote',
                'DEVICE_NAME' => 'IR',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213225,
                'DEVICE_TYPE' => 'temp_hum',
                'DEVICE_NAME' => 'temp hum',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213229,
                'DEVICE_TYPE' => 'people_counter',
                'DEVICE_NAME' => 'M3065-V',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],

            [
                'DEVICE_ID' => 213213230,
                'DEVICE_TYPE' => 'light_detector',
                'DEVICE_NAME' => 'dummy light detector',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ]
        ];
        $device = $this->object->getUniqueDevices($request);
        $this->assertEquals(json_encode($device), json_encode($expected));


        /**********************************************************************/
        /* Both FLOOR_ID and ROOM_ID is not set                               */
        /**********************************************************************/

        // Create a mock of Request class
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        // Expected value
        $expected = [
            [
                'DEVICE_ID' => 1,
                'DEVICE_TYPE' => 'wall_switch_3',
                'DEVICE_NAME' => '3 Gang SW GA',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 3,
                'DEVICE_TYPE' => 'wall_switch_2',
                'DEVICE_NAME' => 'Switch 2',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 4,
                'DEVICE_TYPE' => 'motion_detector',
                'DEVICE_NAME' => 'Motion_SR',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 5,
                'DEVICE_TYPE' => 'co2_detector',
                'DEVICE_NAME' => 'Dummy CO2',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 6,
                'DEVICE_TYPE' => 'dust_detector',
                'DEVICE_NAME' => 'Dummy Dust',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 9,
                'DEVICE_TYPE' => 'h2o_detector',
                'DEVICE_NAME' => 'Dummy H2O Detector',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 10,
                'DEVICE_TYPE' => 'panic_button',
                'DEVICE_NAME' => 'Dummy Panic Button',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 14,
                'DEVICE_TYPE' => 'embedded_switch_2',
                'DEVICE_NAME' => 'Office Embedded SW',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213219,
                'DEVICE_TYPE' => 'ir_remote',
                'DEVICE_NAME' => 'IR',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213225,
                'DEVICE_TYPE' => 'temp_hum',
                'DEVICE_NAME' => 'temp hum',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],
            [
                'DEVICE_ID' => 213213229,
                'DEVICE_TYPE' => 'people_counter',
                'DEVICE_NAME' => 'M3065-V',
                'ONLINE_FLAG' => 1,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ],

            [
                'DEVICE_ID' => 213213230,
                'DEVICE_TYPE' => 'light_detector',
                'DEVICE_NAME' => 'dummy light detector',
                'ONLINE_FLAG' => 0,
                'ROOM_ID' => 1,
                'FLOOR_ID' => 1
            ]
        ];
        $device = $this->object->getUniqueDevices($request);
        $this->assertEquals(json_encode($device), json_encode($expected));
    }
}
