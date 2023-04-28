<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\ManagementController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Tests\TestCase;

/**
 *
 */
class ManagementController_getDeviceType extends TestCase
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
    public function test_getDeviceType()
    {
        // Auth::attempt(['username' => 'admin', 'password' => '123123123']);
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $request->DEVICE_TYPE = 'remote_lock';
        $request->REG_FLAG = 1;
        $expected = [
            'CREATED_AT' => '2021-01-11 06:00:00',
            'DATA' => [
                'remote_lock_id' => '1f3b223e-19be-4db1-9c02-91041f1c4b06'
            ],
            'DEVICE_CATEGORY' => 1,
            'DEVICE_ID' => 69,
            'DEVICE_MAP_NAME' => null,
            'DEVICE_NAME' => 'Remote Lock 101',
            'DEVICE_SERIAL_NO' => 'XxxXXxxXX123',
            'DEVICE_TYPE' => 'remote_lock',
            'FLOOR_ID' => 1,
            'GATEWAY_ID' => 7,
            'MANUFACTURER_ID' => 4,
            'ONLINE_FLAG' => 1,
            'REG_FLAG' => 1,
            'ROOM_ID' => 11,
            'UPDATED_AT' => "2021-01-11 06:10:00"
        ];
        $device = $this->object->getDeviceTypeTest($request);
        echo $device;
        $this->assertEquals(json_decode($device, true), $expected);
    }
}
