<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDeviceRoom extends TestCase
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

    public function test_getDeviceRoom()
    {

        $id = 1;
        //Query
        $this->uri = "api/getDeviceRoom/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected result
        $expected = [
            [
                "ROOM_ID" => 1,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "ROOM_STATUS" => 2,
                "ROOM_MESSAGE" => "CLEAN UP TIME",
                "ROOM_TOTAL_PEOPLE" => 6,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "stp_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 1
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
