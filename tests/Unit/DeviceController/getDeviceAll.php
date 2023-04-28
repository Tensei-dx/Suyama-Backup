<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDeviceAll extends TestCase
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

    public function test_getDeviceAll()
    {

        //Query
        $this->uri = "/getDeviceAll";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected result
        $expected = [
            [
                "DEVICE_ID" => 1,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "8AD0BF12004B1200",
                "DEVICE_TYPE" => "wall_switch_3",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "status" => "1",
                        "device_name" => "Entrance"
                    ],
                    [
                        "status" => "0",
                        "device_name" => "Storage Room"
                    ],
                    [
                        "status" => "0",
                        "device_name" => "GA"
                    ]
                ],
                "DEVICE_NAME" => "3 Gang SW GA",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2020-07-08 09:45:04",
                "UPDATED_AT" => "2021-03-20 07:43:27"
            ],
            [
                "DEVICE_ID" => 2,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "E3D0BF12004B1200",
                "DEVICE_TYPE" => "wall_switch_3",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "status" => "1",
                        "device_name" => "SE3"
                    ],
                    [
                        "status" => "1",
                        "device_name" => "SE1"
                    ],
                    [
                        "status" => "1",
                        "device_name" => "SE2"
                    ]
                ],
                "DEVICE_NAME" => "3 Gang SW SE",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-01-08 09:45:04",
                "UPDATED_AT" => "2021-03-20 07:43:26"
            ],
            [
                "DEVICE_ID" => 3,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "dummy_wall_switch_2",
                "DEVICE_TYPE" => "wall_switch_2",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "status" => "1",
                        "device_name" => "Dummy_1"
                    ],
                    [
                        "status" => "1",
                        "device_name" => "Dummy_2"
                    ]
                ],
                "DEVICE_NAME" => "Switch 2",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-01-08 09:45:04",
                "UPDATED_AT" => "2021-01-19 04:09:02"
            ],
            [
                "DEVICE_ID" => 4,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "3FB5F70F004B1200",
                "DEVICE_TYPE" => "motion_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 0
                ],
                "DEVICE_NAME" => "Motion_SR",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-01 15:17:35",
                "UPDATED_AT" => "2021-03-02 09:56:12"
            ],
            [
                "DEVICE_ID" => 5,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "8AZ23F12004B1200",
                "DEVICE_TYPE" => "co2_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 100
                ],
                "DEVICE_NAME" => "Dummy CO2",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-01-19 12:29:27",
                "UPDATED_AT" => "2021-01-19 04:35:03"
            ],
            [
                "DEVICE_ID" => 6,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "4697EA04004B1200",
                "DEVICE_TYPE" => "dust_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 208
                ],
                "DEVICE_NAME" => "Dummy Dust",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-01-19 12:30:49",
                "UPDATED_AT" => "2021-03-23 11:00:03"
            ],
            [
                "DEVICE_ID" => 7,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 5,
                "DEVICE_SERIAL_NO" => "dummy_remote_lock",
                "DEVICE_TYPE" => "remote_lock",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "remote_lock_id" => "1f3b223e-19be-4db1-9c02-91041f1c4b06"
                ],
                "DEVICE_NAME" => "Remote Lock 101",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-01-11 06:00:00",
                "UPDATED_AT" => "2021-03-14 06:06:15"
            ],
            [
                "DEVICE_ID" => 8,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 5,
                "DEVICE_SERIAL_NO" => "dummy_remote_lock",
                "DEVICE_TYPE" => "remote_lock",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "remote_lock_id" => "a0deb303-5308-490f-a10b-b64a7d37436e"
                ],
                "DEVICE_NAME" => "Remote Lock Carry Cube",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-01-11 06:00:00",
                "UPDATED_AT" => "2021-01-19 07:31:03"
            ],
            [
                "DEVICE_ID" => 9,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "612DD814004B1200",
                "DEVICE_TYPE" => "h2o_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 1
                ],
                "DEVICE_NAME" => "Dummy H2O Detector",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-02-24 08:24:53",
                "UPDATED_AT" => "2021-02-24 18:04:14"
            ],
            [
                "DEVICE_ID" => 10,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "dummy_panic_button",
                "DEVICE_TYPE" => "panic_button",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    "status" => 0
                ],
                "DEVICE_NAME" => "Dummy Panic Button",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-02-24 08:30:02",
                "UPDATED_AT" => "2021-03-14 06:06:15"
            ],
            [
                "DEVICE_ID" => 14,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "13D5C112004B1200",
                "DEVICE_TYPE" => "embedded_switch_2",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "status" => 0,
                        "device_name" => "SW1"
                    ],
                    [
                        "status" => 0,
                        "device_name" => "SW2"
                    ]
                ],
                "DEVICE_NAME" => "Office Embedded SW",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 1,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2020-10-20 07:26:58",
                "UPDATED_AT" => "2020-11-05 04:44:34"
            ],
            [
                "DEVICE_ID" => 213213219,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "BF6A6A0F004B1200",
                "DEVICE_TYPE" => "ir_remote",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    [
                        "type" => "AC",
                        "brand" => "Panasonic",
                        "status" => "1",
                        "temp_value" => "16",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "TV",
                        "brand" => "Lenovo",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ]
                ],
                "DEVICE_NAME" => "IR",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-02-01 15:59:33",
                "UPDATED_AT" => "2021-03-14 06:06:38"
            ],
            [
                "DEVICE_ID" => 213213225,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "CA1FB805004B1200",
                "DEVICE_TYPE" => "temp_hum",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "hum" => 60.5,
                    "temp" => 29.1
                ],
                "DEVICE_NAME" => "temp hum",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-02-09 10:18:09",
                "UPDATED_AT" => "2021-03-20 08:02:51"
            ],
            [
                "DEVICE_ID" => 213213229,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 71,
                "MANUFACTURER_ID" => 4,
                "DEVICE_SERIAL_NO" => "12933_76f9c543-79a1-4925-85d9-aedec5516d51",
                "DEVICE_TYPE" => "camera",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    [
                        "Model" => "AXIS M3065-V",
                        "HasPtz" => true,
                        "Address" => "192.168.40.78",
                        "HasAudio" => false,
                        "HostName" => "",
                        "HttpPort" => 80,
                        "MacAddress" => "ACCC8EFF7B71",
                        "Applications" => [
                            [
                                "name" => "Metadata",
                                "status" => "Running",
                                "niceName" => "AXIS Video Content Stream"
                            ],
                            [
                                "name" => "fenceguard",
                                "status" => "Running",
                                "niceName" => "AXIS Fence Guard"
                            ],
                            [
                                "name" => "loiteringguard",
                                "status" => "Running",
                                "niceName" => "AXIS Loitering Guard"
                            ],
                            [
                                "name" => "tvpc",
                                "status" => "Running",
                                "niceName" => "AXIS People Counter"
                            ],
                            [
                                "name" => "vmd",
                                "status" => "Running",
                                "niceName" => "AXIS Video Motion Detection"
                            ]
                        ],
                        "LiveViewAudio" => false,
                        "RecordingAudio" => false,
                        "FirmwareVersion" => "10.1.0"
                    ]
                ],
                "DEVICE_NAME" => "M3065-V",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-02-15 13:12:23",
                "UPDATED_AT" => "2021-02-15 13:12:33"
            ],
            [
                "DEVICE_ID" => 213213230,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 3,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "dummy dust",
                "DEVICE_TYPE" => "light_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 25
                ],
                "DEVICE_NAME" => "dummy light detector",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-12 10:29:22",
                "UPDATED_AT" => "2021-03-14 06:06:15"
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        //$response->assertExactJson($expected);   
    }
}
