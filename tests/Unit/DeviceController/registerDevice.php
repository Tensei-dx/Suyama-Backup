<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_registerDevice extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "api/registerDevice";

    public function test_registerDevice()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected Result
        $response = [
            'DEVICE_ID' => '213213230',
            'FLOOR_ID' => '1',
            'ROOM_ID' => '1',
            'GATEWAY_ID' => '3',
            'DEVICE_NAME' => 'dummy light detector',
            'DEVICE_CATEGORY' => '1',
            'DEVICE_DATA' => '{"status": 25}',
            'REG_FLAG' => '0',
            'ONLINE_FLAG' => '0',
        ];

        $actual = $this->json($this->method, $this->uri, $response);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
