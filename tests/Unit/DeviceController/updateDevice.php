<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_updateDevice extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "api/updateDevice";

    public function test_updateDevice()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected Result
        $response = [
            'DEVICE_ID' => '213213230',
            'DEVICE_NAME' => 'Test Sensor',
            'DEVICE_CATEGORY' => '1'
        ];

        $actual = $this->json($this->method, $this->uri, $response);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
