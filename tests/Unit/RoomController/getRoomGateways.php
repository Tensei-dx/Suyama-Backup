<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoomController_getRoomGateways extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "GET";

    /** @var string $uri */
    protected $uri;

    public function test_getRoomGateways()
    {
        $id = 1;
        $this->uri = "/getRoomGateways/$id";
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
                "CREATED_AT" => "2021-03-20 08:22:21",
                "UPDATED_AT" => "2021-03-23 10:39:01"
            ]
        ];

        // Actual result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        //$response->assertExactJson($expected);
    }
}
