<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDevicesWithBindings extends TestCase
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

    public function test_getDevicesWithBindings()
    {

        //Query
        $this->uri = "api/getDevicesWithBindings";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $expected = [];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
