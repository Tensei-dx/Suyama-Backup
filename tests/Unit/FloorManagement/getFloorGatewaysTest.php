<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getFloorGatewaysTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getFloorGateways/1";

    /**
     * 取得データの検証
     */
    public function test_getFloorGatewaysTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // 期待値
        $expected = [
            [
                "CREATED_AT" => "2021-03-27 11:35:16", "FLOOR_ID" => 1, "GATEWAY_ID" => 78, "GATEWAY_IP" => "192.168.40.160", "GATEWAY_NAME" => "GW 160", "GATEWAY_SERIAL_NO" => "50:29:4D:10:47:E1", "MANUFACTURER_ID" => 1, "ONLINE_FLAG" => 1, "REG_FLAG" => 1, "ROOM_ID" => 1, "UPDATED_AT" => "2021-04-08 17:10:02"
            ]
        ];

        /*   $body = array("GATEWAY_ID"=> 78,
        );*/

        // Actual response
        $actual = $this->json($this->method, $this->uri);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        //      $actual->assertExactJson($expected); 
    }
}
