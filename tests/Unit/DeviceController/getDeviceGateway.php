<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDeviceGateway extends TestCase
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

    public function test_getDeviceGateway()
    {

        $id = 1;
        //Query
        $this->uri = "api/getDeviceGateway/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected result
        $expected = [
            [
                "GATEWAY_ID" => 3,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "MANUFACTURER_ID" => 1,
                "GATEWAY_SERIAL_NO" => "50:29:4D:10:47:E1",
                "GATEWAY_IP" => "192.168.40.160",
                "GATEWAY_NAME" => "GW 160",
                "ONLINE_FLAG" => 1,
                "REG_FLAG" => 1,
                "CREATED_AT" => "2021-03-24 08:33:08",
                "UPDATED_AT" => "2021-03-24 11:30:02"
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        //$response->assertExactJson($expected); 
    }
}
