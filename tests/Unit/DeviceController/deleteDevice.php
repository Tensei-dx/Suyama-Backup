<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_deleteDevice extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "api/deleteDevice";

    public function test_deleteDevice()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected Result
        $response = [
            'DEVICE_ID' => '213213230'
        ];

        $actual = $this->json($this->method, $this->uri, $response);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
