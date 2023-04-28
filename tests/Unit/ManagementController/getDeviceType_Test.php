<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getDeviceType_Test.php
 *
 * Create : 2021.02.26 TP Uddin
 * Update :
 */

namespace Tests\Unit\ManagementController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ManagementController_getDeviceType
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ManagementController_getDeviceType extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = 'getDeviceType';

    public function test_getDeviceType()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            'DEVICE_TYPE' => 'temp_hum',
            'REG_FLAG' => 1
        ];
        // Expected result
        $expected = [
            [
                "DEVICE_ID" => 213213225,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "CA1FB805004B1200",
                "DEVICE_TYPE" => "temp_hum",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "hum" => 51.9,
                    "temp" => 28.2
                ],
                "DEVICE_NAME" => "temp hum",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-02-09 10:18:09",
                "UPDATED_AT" => "2021-03-01 09:10:45",
                "room" => [
                    "ROOM_ID" => 1,
                    "ROOM_NAME" => "ROOM 101"
                ]
            ]
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
