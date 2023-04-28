<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getRoomGateway_Test.php
 *
 * Create : 2021.02.16 TP Uddin
 * Update : 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_getRoomGateway
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_getRoomGateway extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "GET";

    /** @var string $uri URI of the ClientController@getRoomGateway */
    protected $uri = "/client/gateway";

    /**
     *
     */
    public function test_getRoomGateway()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            "ROOM_ID" => 1
        ];
        $expected = [
            "GATEWAY_ID" => 3,
            "FLOOR_ID" => 1,
            "ROOM_ID" => 1,
            "MANUFACTURER_ID" => 1,
            "GATEWAY_SERIAL_NO" => "50:29:4D:10:47:E1",
            "GATEWAY_IP" => "192.168.40.160",
            "GATEWAY_NAME" => "gateway_name",
            "ONLINE_FLAG" => 1,
            "REG_FLAG" => 1,
            "CREATED_AT" => "2021-02-17 09:23:35",
            "UPDATED_AT" => "2021-02-22 13:46:02"
        ];
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
