<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoomController_deleteRoom extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "deleteRoom";

    public function test_deleteRoom()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [

            'ROOM_ID' => '1150'
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
