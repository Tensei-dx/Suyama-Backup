<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getRegisteredDevices extends TestCase
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

    public function test_getRegisteredDevices()
    {

        //Query
        $this->uri = "api/getRegisteredDevices";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $expected = [
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "8AD0BF12004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "3 Gang SW GA",
                "DEVICE_TYPE" => "wall_switch_3",
                "DEVICE_CATEGORY" => 0,
                "DEVICE_ID" => 1,
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
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "E3D0BF12004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "3 Gang SW SE",
                "DEVICE_TYPE" => "wall_switch_2",
                "DEVICE_CATEGORY" => 0,
                "DEVICE_ID" => 2,
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
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "3FB5F70F004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Motion_SR",
                "DEVICE_TYPE" => "wall_switch_3",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 4,
                "DATA" => [
                    "status" => 0
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "8AZ23F12004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Dummy CO2",
                "DEVICE_TYPE" => "co2_detector",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 5,
                "DATA" => [
                    "status" => 100
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "4697EA04004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Dummy Dust",
                "DEVICE_TYPE" => "dust_detector",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 6,
                "DATA" => [
                    "status" => 239
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "dummy_remote_lock",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Remote Lock 101",
                "DEVICE_TYPE" => "remote_lock",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 7,
                "DATA" => [
                    "remote_lock_id" => "1f3b223e-19be-4db1-9c02-91041f1c4b06"
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "dummy_remote_lock",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Remote Lock Carry Cube",
                "DEVICE_TYPE" => "remote_lock",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 8,
                "DATA" => [
                    "remote_lock_id" => "a0deb303-5308-490f-a10b-b64a7d37436e"
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "612DD814004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Dummy H2O Detector",
                "DEVICE_TYPE" => "h2o_detector",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 9,
                "DATA" => [
                    "status" => 1
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "dummy_panic_button",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Dummy Panic Button",
                "DEVICE_TYPE" => "panic_button",
                "DEVICE_CATEGORY" => 0,
                "DEVICE_ID" => 10,
                "DATA" => [
                    "status" => 0
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "13D5C112004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "Office Embedded SW",
                "DEVICE_TYPE" => "embedded_switch_2",
                "DEVICE_CATEGORY" => 0,
                "DEVICE_ID" => 14,
                "DATA" => [
                    [
                        "status" => 0,
                        "device_name" => "SW1"
                    ],
                    [
                        "status" => 0,
                        "device_name" => "SW2"
                    ]
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "BF6A6A0F004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "IR",
                "DEVICE_TYPE" => "ir_remote",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 213213219,
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
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "CA1FB805004B1200",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "temp hum",
                "DEVICE_TYPE" => "temp_hum",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 213213225,
                "DATA" => [
                    "hum" => 47.8,
                    "temp" => 29.1
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 71,
                "GATEWAY_NAME" => "PC-DEV73",
                "DEVICE_SERIAL_NO" => "12933_76f9c543-79a1-4925-85d9-aedec5516d51",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.71",
                "DEVICE_NAME" => "M3065-V",
                "DEVICE_TYPE" => "camera",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 213213229,
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
                ]
            ],
            [
                "REG_FLAG" => 1,
                "GATEWAY_ID" => 3,
                "GATEWAY_NAME" => "GW 160",
                "DEVICE_SERIAL_NO" => "dummy dust",
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "ROOM_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "GATEWAY_IP" => "192.168.40.160",
                "DEVICE_NAME" => "dummy light detector",
                "DEVICE_TYPE" => "light_detector",
                "DEVICE_CATEGORY" => 1,
                "DEVICE_ID" => 213213230,
                "DATA" => [
                    "status" => 25
                ]
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        //$response->assertExactJson($expected); 
    }
}
