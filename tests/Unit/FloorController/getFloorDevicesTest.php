<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getFloorDevicesTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getFloorDevices/1";
    /**
     * 取得データの検証
     */
    public function test_getFloorDevicesTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // 期待値
        $expected = [
            [
                "DEVICE_ID" => 10,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 78,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "CE724004004B1200",
                "DEVICE_TYPE" => "co2_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 540
                ],
                "DEVICE_NAME" => "CO2 Detector",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => null,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-27 11:42:35",
                "UPDATED_AT" => "2021-04-04 02:50:04"
            ],
            [
                "DEVICE_ID" => 213213219,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 78,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "3FB5F70F004B1200",
                "DEVICE_TYPE" => "motion_detector",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "status" => 0
                ],
                "DEVICE_NAME" => "Motion Detector",
                "DEVICE_MAP_NAME" => "6cu99jbyx7",
                "EMERGENCY_DEVICE" => null,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-27 11:42:35",
                "UPDATED_AT" => "2021-04-08 16:22:01"
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
                "DEVICE_MAP_NAME" => "smt6bj85kp",
                "EMERGENCY_DEVICE" => 0,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-02-15 13:12:23",
                "UPDATED_AT" => "2021-03-31 10:36:45"
            ],
            [
                "DEVICE_ID" => 213213232,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 78,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "CA1FB805004B1200",
                "DEVICE_TYPE" => "temp_hum",
                "DEVICE_CATEGORY" => 1,
                "DATA" => [
                    "hum" => 43.9,
                    "temp" => 26
                ],
                "DEVICE_NAME" => "Temp and Humid",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => null,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 1,
                "CREATED_AT" => "2021-03-27 11:42:35",
                "UPDATED_AT" => "2021-04-08 16:33:31"
            ],
            [
                "DEVICE_ID" => 213213233,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 78,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "8AD0BF12004B1200",
                "DEVICE_TYPE" => "wall_switch_3",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "status" => "1",
                        "device_name" => "S1"
                    ],
                    [
                        "status" => "0",
                        "device_name" => "S2"
                    ],
                    [
                        "status" => "0",
                        "device_name" => "S3"
                    ]
                ],
                "DEVICE_NAME" => "Wall Switch 1",
                "DEVICE_MAP_NAME" => "s8o3gyggee",
                "EMERGENCY_DEVICE" => null,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-27 11:42:35",
                "UPDATED_AT" => "2021-04-08 13:08:01"
            ],
            [
                "DEVICE_ID" => 213213235,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 78,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "E3D0BF12004B1200",
                "DEVICE_TYPE" => "wall_switch_3",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "status" => "1",
                        "device_name" => "SW1"
                    ],
                    [
                        "status" => "1",
                        "device_name" => "SW2"
                    ],
                    [
                        "status" => "1",
                        "device_name" => "SW3"
                    ]
                ],
                "DEVICE_NAME" => "Wall Switch 3",
                "DEVICE_MAP_NAME" => null,
                "EMERGENCY_DEVICE" => null,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-27 11:42:35",
                "UPDATED_AT" => "2021-04-08 08:16:01"
            ],
            [
                "DEVICE_ID" => 213213237,
                "FLOOR_ID" => 1,
                "ROOM_ID" => 1,
                "GATEWAY_ID" => 78,
                "MANUFACTURER_ID" => 1,
                "DEVICE_SERIAL_NO" => "BF6A6A0F004B1200",
                "DEVICE_TYPE" => "ir_remote",
                "DEVICE_CATEGORY" => 0,
                "DATA" => [
                    [
                        "type" => "AC",
                        "brand" => "Panasonic",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "AC2",
                        "brand" => "Samsung",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "AC",
                        "brand" => "BIONAIRE",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "TV",
                        "brand" => "Devant",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "TV",
                        "brand" => "Samsung",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "TV",
                        "brand" => "Phillips",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "TV",
                        "brand" => "Relx",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "TV",
                        "brand" => "Lenovo",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ],
                    [
                        "type" => "NOPQRSTUVWXYZ",
                        "brand" => "SAMSUNGLENOVO",
                        "status" => "0",
                        "temp_value" => "25",
                        "aircon_power" => true
                    ]
                ],
                "DEVICE_NAME" => "IR Remote",
                "DEVICE_MAP_NAME" => "ejz841qtfh",
                "EMERGENCY_DEVICE" => null,
                "REG_FLAG" => 1,
                "ONLINE_FLAG" => 0,
                "CREATED_AT" => "2021-03-27 11:42:35",
                "UPDATED_AT" => "2021-04-08 13:08:02"
            ]
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);
    }
}
