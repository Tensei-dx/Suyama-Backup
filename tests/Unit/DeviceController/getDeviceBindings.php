<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDeviceBinding extends TestCase
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

    public function test_getDeviceBinding()
    {

        $id = 213213225;
        //Query
        $this->uri = "api/getDeviceBindings/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $expected = [
            [
                "BINDING_ID" => 93,
                "SOURCE_DEVICE_ID" => 213213225,
                "TARGET_DEVICE_ID" => 213213219,
                "BINDING_LIST_ID" => 116,
                "SOURCE_DEVICE_CONDITION" => [],
                "CUSTOM_CONDITION" => [
                    "command" => "status",
                    "operator" => "TEMP_24"
                ],
                "TIME_INTERVAL" => 60,
                "BINDING_STATUS" => 1,
                "MANUAL" => 0,
                "CREATED_AT" => "2021-03-20 14:29:46",
                "UPDATED_AT" => "2021-03-23 11:14:32",
                "LAST_ACTIVITY" => "2021-03-20 13:29:46",
                "NEXT_ACTIVITY" => "2021-03-23 11:14:32"
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
