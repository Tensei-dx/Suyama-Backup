<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDeviceBindingList extends TestCase
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

    public function test_getDeviceBindingList()
    {

        $id = 5;
        //Query
        $this->uri = "api/getDeviceBindingList/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $expected = [
            [
                "BINDING_LIST_ID" => 259,
                "SOURCE_DEVICE_TYPE" => "co2_detector",
                "SOURCE_DEVICE_CONDITION" => "BAD",
                "SOURCE_DEVICE_CODE" => "{\"status\": 1}",
                "SOURCE_DEVICE_COMMAND" => "status",
                "SOURCE_DEVICE_VALUE" => "{\"value1\": 2501, \"value2\": 50000}",
                "TARGET_DEVICE_TYPE" => "motion_detector",
                "TARGET_DEVICE_CONDITION" => "NORMAL",
                "TARGET_DEVICE_COMMAND" => "status",
                "TARGET_DEVICE_VALUE" => "0",
                "TARGET_DEVICE_CATEGORY" => "1",
                "CREATED_AT" => "2018-08-13 14:10:51",
                "UPDATED_AT" => "2018-08-13 14:10:51"
            ],
            [
                "BINDING_LIST_ID" => 261,
                "SOURCE_DEVICE_TYPE" => "co2_detector",
                "SOURCE_DEVICE_CONDITION" => "BAD",
                "SOURCE_DEVICE_CODE" => "{\"status\": 1}",
                "SOURCE_DEVICE_COMMAND" => "status",
                "SOURCE_DEVICE_VALUE" => "{\"value1\": 2501, \"value2\": 50000}",
                "TARGET_DEVICE_TYPE" => "temp_hum",
                "TARGET_DEVICE_CONDITION" => "COMFORT",
                "TARGET_DEVICE_COMMAND" => "status",
                "TARGET_DEVICE_VALUE" => "{\"value1\": 0, \"value2\": 23}",
                "TARGET_DEVICE_CATEGORY" => "1",
                "CREATED_AT" => "2018-08-13 14:10:51",
                "UPDATED_AT" => "2018-08-13 14:10:51"
            ],
            [
                "BINDING_LIST_ID" => 263,
                "SOURCE_DEVICE_TYPE" => "co2_detector",
                "SOURCE_DEVICE_CONDITION" => "BAD",
                "SOURCE_DEVICE_CODE" => "{\"status\": 1}",
                "SOURCE_DEVICE_COMMAND" => "status",
                "SOURCE_DEVICE_VALUE" => "{\"value1\": 2501, \"value2\": 50000}",
                "TARGET_DEVICE_TYPE" => "light_detector",
                "TARGET_DEVICE_CONDITION" => "DAY",
                "TARGET_DEVICE_COMMAND" => "status",
                "TARGET_DEVICE_VALUE" => "{\"value1\": 301, \"value2\": 100000}",
                "TARGET_DEVICE_CATEGORY" => "1",
                "CREATED_AT" => "2018-08-13 14:10:51",
                "UPDATED_AT" => "2018-08-13 14:10:51"
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
