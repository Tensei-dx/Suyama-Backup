<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDevice extends TestCase
{
    use AuthenticatesUsers;
    /**
     *@var RoomController      
     */
    protected $object;

    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "GET";

    /** @var string $uri */
    protected $uri;

    public function test_getDevice()
    {

        $id = 1;
        //Query
        $this->uri = "api/getDevice/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected result
        $expected = [

            "DEVICE_ID" => 1,
            "FLOOR_ID" => 1,
            "ROOM_ID" => 1,
            "GATEWAY_ID" => 3,
            "MANUFACTURER_ID" => 1,
            "DEVICE_SERIAL_NO" => "8AD0BF12004B1200",
            "DEVICE_TYPE" => "wall_switch_3",
            "DEVICE_CATEGORY" => 0,
            "DATA" => [
                [
                    "status" => "1",
                    "device_name" => "Entrance"
                ],
                [
                    "status" => "0",
                    "device_name" => "Storage Room"
                ],
                [
                    "status" => "0",
                    "device_name" => "GA"
                ]
            ],
            "DEVICE_NAME" => "3 Gang SW GA",
            "DEVICE_MAP_NAME" => null,
            "EMERGENCY_DEVICE" => 1,
            "REG_FLAG" => 1,
            "ONLINE_FLAG" => 1,
            "CREATED_AT" => "2020-07-08 09:45:04",
            "UPDATED_AT" => "2021-03-24 08:52:39",
            "device_bindings" => []
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
