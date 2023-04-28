<?php

namespace Tests\Unit\FloorController;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class newFloorTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "createFloor";

    /**
     * 取得データの検証
     */
    public function test_newFloorTest_success()
    {

        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Request body
        $body = [
            'floorImageName' => json_encode(["FLOOR_MAP" => "private_room"]),
            'floorImageFile' => UploadedFile::fake()->image('fakefloor-image.png'),
            'floorImage' => 'fakefloor-image.png',
            'floorName' => '32th',
            'roomImageFile' => UploadedFile::fake()->image('fake-image.png'),
            'roomImage' => 'fake-image.png',
            'roomName' => 'TEST ROOM',
            'floorId' => '1',
            'roomImageName' => json_encode(["ROOM_MAP" => "private_room"])
        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        // 200 is OK
        // 201 is Created
        $actual->assertStatus(201);
    }

    /**
     * Duplicate Floor Case
     */
    public function test_newFloorTest_floorDuplicate()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Request body
        $body = [
            'floorImageName' => json_encode(["FLOOR_MAP" => "private_room"]),
            'floorImageFile' => UploadedFile::fake()->image('fakefloor-image.png'),
            'floorImage' => 'fakefloor-image.png',
            'floorName' => '31th',
            'roomImageFile' => UploadedFile::fake()->image('fake-image.png'),
            'roomImage' => 'fake-image.png',
            'roomName' => 'TEST ROOM',
            'floorId' => '1',
            'roomImageName' => json_encode(["ROOM_MAP" => "private_room"])
        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        // 200 is OK
        // 201 is Created
        $actual->assertStatus(200);
    }

    /**
     * Duplicate Room Image Case
     */
    public function test_newFloorTest_roomDuplicate()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Request body
        $body = [
            'floorImageName' => json_encode(["FLOOR_MAP" => "private_room"]),
            'floorImageFile' => UploadedFile::fake()->image('fakefloor-image.png'),
            'floorImage' => 'fakefloor-image.png',
            'floorName' => '29th',
            'roomImageFile' => UploadedFile::fake()->image('fake-image.png'),
            'roomImage' => 'fake-image.png',
            'roomName1' => 'TEST ROOM',
            'roomName2' => 'TEST ROOM2',
            'floorId' => '1',
            'roomImageName1' => "download.jpg",
            'roomImageName2' => "download.jpg"
        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        // 200 is OK
        // 201 is Created
        $actual->assertStatus(200);
    }
}
