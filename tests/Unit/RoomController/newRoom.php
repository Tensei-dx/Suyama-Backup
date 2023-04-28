<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoomController_newRoom extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "createRoom";

    public function test_newRoom()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'roomImageFile' => UploadedFile::fake()->image('fake-image.png'),
            'roomImage' => 'fake-image.png',
            'roomName' => 'COVID ROOM',
            'floorId' => '1',
            'roomImageName' => '{"ROOM_MAP": "private_room"}'
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        // 200 is OK
        // 201 is Created
        $actual->assertStatus(201);
    }
}
