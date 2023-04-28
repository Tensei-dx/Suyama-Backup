<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getAllIrLearningListTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getAllIrLearningList";

    /**
     * 取得データの検証
     */
    public function test_getAllIrLearningList()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        //----------------------------------------------------------------------		
        // CASE1：In case of branching of if statement / if分岐の場合		
        //----------------------------------------------------------------------		
        // 期待値
        $expected = [
            [
                "IR_LEARNING_LIST_ID" => 91,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 1,
                "LEARNING_VALUE" => 22,
                "CREATED_AT" => "2020-10-06 05:52:56",
                "UPDATED_AT" => "2020-10-06 05:52:56",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 1,
                    "OPERATION_NAME" => "TEMP_16",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 92,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 16,
                "LEARNING_VALUE" => 53,
                "CREATED_AT" => "2020-10-07 00:45:54",
                "UPDATED_AT" => "2020-10-07 00:45:54",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 16,
                    "OPERATION_NAME" => "TEMP_27",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 93,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 11,
                "LEARNING_VALUE" => 19,
                "CREATED_AT" => "2020-10-07 00:54:19",
                "UPDATED_AT" => "2020-10-07 00:54:19",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 11,
                    "OPERATION_NAME" => "TEMP_22",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 95,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 32,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 20,
                "CREATED_AT" => "2020-10-07 01:14:53",
                "UPDATED_AT" => "2020-10-07 01:14:53",
                "appliances" => [
                    "APPLIANCE_ID" => 32,
                    "APPLIANCE_NAME" => "Aircon-01",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-07-31 10:07:57",
                    "UPDATED_AT" => "2019-07-31 10:07:57"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 96,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 32,
                "OPERATION_ID" => 5,
                "LEARNING_VALUE" => 17,
                "CREATED_AT" => "2020-10-07 01:15:17",
                "UPDATED_AT" => "2020-10-07 01:15:17",
                "appliances" => [
                    "APPLIANCE_ID" => 32,
                    "APPLIANCE_NAME" => "Aircon-01",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-07-31 10:07:57",
                    "UPDATED_AT" => "2019-07-31 10:07:57"
                ],
                "operation" => [
                    "OPERATION_ID" => 5,
                    "OPERATION_NAME" => "AC_POWER_OFF",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 124,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 14,
                "LEARNING_VALUE" => 14,
                "CREATED_AT" => "2020-10-22 02:05:40",
                "UPDATED_AT" => "2020-10-22 02:05:40",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 14,
                    "OPERATION_NAME" => "TEMP_25",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 126,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 15,
                "LEARNING_VALUE" => 10,
                "CREATED_AT" => "2020-10-22 02:08:04",
                "UPDATED_AT" => "2020-10-22 02:08:04",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 15,
                    "OPERATION_NAME" => "TEMP_26",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 127,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 13,
                "LEARNING_VALUE" => 21,
                "CREATED_AT" => "2020-10-22 02:36:36",
                "UPDATED_AT" => "2020-10-22 02:36:36",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 13,
                    "OPERATION_NAME" => "TEMP_24",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 128,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 12,
                "LEARNING_VALUE" => 28,
                "CREATED_AT" => "2020-10-22 05:57:20",
                "UPDATED_AT" => "2020-10-22 05:57:20",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 12,
                    "OPERATION_NAME" => "TEMP_23",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 130,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 6,
                "LEARNING_VALUE" => 18,
                "CREATED_AT" => "2020-11-04 07:01:34",
                "UPDATED_AT" => "2020-11-04 07:01:34",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 6,
                    "OPERATION_NAME" => "TEMP_17",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 131,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 7,
                "LEARNING_VALUE" => 23,
                "CREATED_AT" => "2020-11-04 07:02:03",
                "UPDATED_AT" => "2020-11-04 07:02:03",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 7,
                    "OPERATION_NAME" => "TEMP_18",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 132,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 8,
                "LEARNING_VALUE" => 26,
                "CREATED_AT" => "2020-11-04 07:02:40",
                "UPDATED_AT" => "2020-11-04 07:02:40",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 8,
                    "OPERATION_NAME" => "TEMP_19",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 133,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 9,
                "LEARNING_VALUE" => 24,
                "CREATED_AT" => "2020-11-04 07:03:08",
                "UPDATED_AT" => "2020-11-04 07:03:08",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 9,
                    "OPERATION_NAME" => "TEMP_20",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 134,
                "DEVICE_ID" => 10,
                "APPLIANCE_ID" => 57,
                "OPERATION_ID" => 10,
                "LEARNING_VALUE" => 30,
                "CREATED_AT" => "2020-11-04 07:03:46",
                "UPDATED_AT" => "2020-11-04 07:03:46",
                "appliances" => [
                    "APPLIANCE_ID" => 57,
                    "APPLIANCE_NAME" => "Panasonic",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2020-01-20 14:50:26",
                    "UPDATED_AT" => "2020-01-20 14:50:26"
                ],
                "operation" => [
                    "OPERATION_ID" => 10,
                    "OPERATION_NAME" => "TEMP_21",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 135,
                "DEVICE_ID" => 213213219,
                "APPLIANCE_ID" => 32,
                "OPERATION_ID" => 1,
                "LEARNING_VALUE" => 0,
                "CREATED_AT" => "2021-02-09 11:13:09",
                "UPDATED_AT" => "2021-02-09 11:13:09",
                "appliances" => [
                    "APPLIANCE_ID" => 32,
                    "APPLIANCE_NAME" => "Aircon-01",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-07-31 10:07:57",
                    "UPDATED_AT" => "2019-07-31 10:07:57"
                ],
                "operation" => [
                    "OPERATION_ID" => 1,
                    "OPERATION_NAME" => "TEMP_16",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-07 15:30:02"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 136,
                "DEVICE_ID" => 213213219,
                "APPLIANCE_ID" => 35,
                "OPERATION_ID" => 2,
                "LEARNING_VALUE" => 27,
                "CREATED_AT" => "2021-02-09 11:14:06",
                "UPDATED_AT" => "2021-02-09 11:14:06",
                "appliances" => [
                    "APPLIANCE_ID" => 35,
                    "APPLIANCE_NAME" => "AC-room1",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-09-13 07:41:30",
                    "UPDATED_AT" => "2019-09-13 07:41:30"
                ],
                "operation" => [
                    "OPERATION_ID" => 2,
                    "OPERATION_NAME" => "TEMP_30",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-07 15:30:02"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 137,
                "DEVICE_ID" => 213213219,
                "APPLIANCE_ID" => 32,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 75,
                "CREATED_AT" => "2021-03-13 14:39:34",
                "UPDATED_AT" => "2021-03-13 14:39:34",
                "appliances" => [
                    "APPLIANCE_ID" => 32,
                    "APPLIANCE_NAME" => "Aircon-01",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-07-31 10:07:57",
                    "UPDATED_AT" => "2019-07-31 10:07:57"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-07 15:30:02"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 138,
                "DEVICE_ID" => 213213219,
                "APPLIANCE_ID" => 32,
                "OPERATION_ID" => 5,
                "LEARNING_VALUE" => 76,
                "CREATED_AT" => "2021-03-13 14:40:11",
                "UPDATED_AT" => "2021-03-13 14:40:11",
                "appliances" => [
                    "APPLIANCE_ID" => 32,
                    "APPLIANCE_NAME" => "Aircon-01",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-07-31 10:07:57",
                    "UPDATED_AT" => "2019-07-31 10:07:57"
                ],
                "operation" => [
                    "OPERATION_ID" => 5,
                    "OPERATION_NAME" => "AC_POWER_OFF",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-07 15:30:02"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 140,
                "DEVICE_ID" => 213213219,
                "APPLIANCE_ID" => 79,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 70,
                "CREATED_AT" => "2021-03-24 15:21:53",
                "UPDATED_AT" => "2021-03-24 15:21:53",
                "appliances" => [
                    "APPLIANCE_ID" => 79,
                    "APPLIANCE_NAME" => "Air Cleaner",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "BIONAIRE",
                    "CREATED_AT" => "2021-03-24 15:20:11",
                    "UPDATED_AT" => "2021-03-24 15:20:11"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-07 15:30:02"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 141,
                "DEVICE_ID" => 213213219,
                "APPLIANCE_ID" => 79,
                "OPERATION_ID" => 5,
                "LEARNING_VALUE" => 71,
                "CREATED_AT" => "2021-03-24 15:22:28",
                "UPDATED_AT" => "2021-03-24 15:22:28",
                "appliances" => [
                    "APPLIANCE_ID" => 79,
                    "APPLIANCE_NAME" => "Air Cleaner",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "BIONAIRE",
                    "CREATED_AT" => "2021-03-24 15:20:11",
                    "UPDATED_AT" => "2021-03-24 15:20:11"
                ],
                "operation" => [
                    "OPERATION_ID" => 5,
                    "OPERATION_NAME" => "AC_POWER_OFF",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-07 15:30:02"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 142,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 32,
                "OPERATION_ID" => 7,
                "LEARNING_VALUE" => 25,
                "CREATED_AT" => "2021-03-27 15:25:34",
                "UPDATED_AT" => "2021-03-27 15:25:34",
                "appliances" => [
                    "APPLIANCE_ID" => 32,
                    "APPLIANCE_NAME" => "Aircon-01",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2019-07-31 10:07:57",
                    "UPDATED_AT" => "2019-07-31 10:07:57"
                ],
                "operation" => [
                    "OPERATION_ID" => 7,
                    "OPERATION_NAME" => "TEMP_18",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 144,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 79,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 8,
                "CREATED_AT" => "2021-03-27 15:34:34",
                "UPDATED_AT" => "2021-03-27 15:34:34",
                "appliances" => [
                    "APPLIANCE_ID" => 79,
                    "APPLIANCE_NAME" => "Air Cleaner",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "BIONAIRE",
                    "CREATED_AT" => "2021-03-24 15:20:11",
                    "UPDATED_AT" => "2021-03-24 15:20:11"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 145,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 36,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 11,
                "CREATED_AT" => "2021-03-27 15:35:23",
                "UPDATED_AT" => "2021-03-27 15:35:23",
                "appliances" => [
                    "APPLIANCE_ID" => 36,
                    "APPLIANCE_NAME" => "Bedroom TV",
                    "APPLIANCE_TYPE" => "TV",
                    "BRAND_NAME" => "Devant",
                    "CREATED_AT" => "2019-09-13 10:55:56",
                    "UPDATED_AT" => "2019-09-13 10:55:56"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 146,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 75,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 12,
                "CREATED_AT" => "2021-03-27 15:42:13",
                "UPDATED_AT" => "2021-03-27 15:42:13",
                "appliances" => [
                    "APPLIANCE_ID" => 75,
                    "APPLIANCE_NAME" => "Televison",
                    "APPLIANCE_TYPE" => "TV",
                    "BRAND_NAME" => "Samsung",
                    "CREATED_AT" => "2020-12-16 01:03:19",
                    "UPDATED_AT" => "2020-12-16 01:03:19"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 147,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 72,
                "OPERATION_ID" => 5,
                "LEARNING_VALUE" => 13,
                "CREATED_AT" => "2021-03-27 15:42:49",
                "UPDATED_AT" => "2021-03-27 15:42:49",
                "appliances" => [
                    "APPLIANCE_ID" => 72,
                    "APPLIANCE_NAME" => "Television",
                    "APPLIANCE_TYPE" => "TV",
                    "BRAND_NAME" => "Phillips",
                    "CREATED_AT" => "2020-06-03 12:32:13",
                    "UPDATED_AT" => "2020-06-03 12:32:13"
                ],
                "operation" => [
                    "OPERATION_ID" => 5,
                    "OPERATION_NAME" => "AC_POWER_OFF",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 148,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 78,
                "OPERATION_ID" => 7,
                "LEARNING_VALUE" => 29,
                "CREATED_AT" => "2021-03-27 15:43:35",
                "UPDATED_AT" => "2021-03-27 15:43:35",
                "appliances" => [
                    "APPLIANCE_ID" => 78,
                    "APPLIANCE_NAME" => "Aircon",
                    "APPLIANCE_TYPE" => "AC",
                    "BRAND_NAME" => "Panasonic",
                    "CREATED_AT" => "2021-03-23 14:01:55",
                    "UPDATED_AT" => "2021-03-23 14:01:55"
                ],
                "operation" => [
                    "OPERATION_ID" => 7,
                    "OPERATION_NAME" => "TEMP_18",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 149,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 73,
                "OPERATION_ID" => 4,
                "LEARNING_VALUE" => 15,
                "CREATED_AT" => "2021-03-27 15:44:23",
                "UPDATED_AT" => "2021-03-27 15:44:23",
                "appliances" => [
                    "APPLIANCE_ID" => 73,
                    "APPLIANCE_NAME" => "Television",
                    "APPLIANCE_TYPE" => "TV",
                    "BRAND_NAME" => "Relx",
                    "CREATED_AT" => "2020-06-03 12:40:11",
                    "UPDATED_AT" => "2020-06-03 12:40:11"
                ],
                "operation" => [
                    "OPERATION_ID" => 4,
                    "OPERATION_NAME" => "AC_POWER_ON",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ],
            [
                "IR_LEARNING_LIST_ID" => 155,
                "DEVICE_ID" => 213213237,
                "APPLIANCE_ID" => 88,
                "OPERATION_ID" => 3,
                "LEARNING_VALUE" => 7,
                "CREATED_AT" => "2021-03-30 10:33:48",
                "UPDATED_AT" => "2021-03-30 10:33:48",
                "appliances" => [
                    "APPLIANCE_ID" => 88,
                    "APPLIANCE_NAME" => "ABCDEFGHIJKLM",
                    "APPLIANCE_TYPE" => "NOPQRSTUVWXYZ",
                    "BRAND_NAME" => "SAMSUNGLENOVO",
                    "CREATED_AT" => "2021-03-30 10:12:46",
                    "UPDATED_AT" => "2021-03-30 10:12:46"
                ],
                "operation" => [
                    "OPERATION_ID" => 3,
                    "OPERATION_NAME" => "POWER",
                    "CREATED_AT" => "2019-02-27 08:46:51",
                    "UPDATED_AT" => "2019-02-27 08:46:51"
                ],
                "device" => [
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
                    "UPDATED_AT" => "2021-04-04 02:50:04"
                ]
            ]
        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);
    }
}
