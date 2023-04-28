<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getAllAppliancesTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getAllAppliances";

    /**
     * 取得データの検証
     */
    public function test_getAllAppliances()
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
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "CREATED_AT" => "2019-07-31 10:07:57",
                "UPDATED_AT" => "2019-07-31 10:07:57",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 95,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 32,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 20,
                        "CREATED_AT" => "2020-10-07 01:14:53",
                        "UPDATED_AT" => "2020-10-07 01:14:53"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 96,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 32,
                        "OPERATION_ID" => 5,
                        "LEARNING_VALUE" => 17,
                        "CREATED_AT" => "2020-10-07 01:15:17",
                        "UPDATED_AT" => "2020-10-07 01:15:17"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 135,
                        "DEVICE_ID" => 213213219,
                        "APPLIANCE_ID" => 32,
                        "OPERATION_ID" => 1,
                        "LEARNING_VALUE" => 0,
                        "CREATED_AT" => "2021-02-09 11:13:09",
                        "UPDATED_AT" => "2021-02-09 11:13:09"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 137,
                        "DEVICE_ID" => 213213219,
                        "APPLIANCE_ID" => 32,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 75,
                        "CREATED_AT" => "2021-03-13 14:39:34",
                        "UPDATED_AT" => "2021-03-13 14:39:34"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 138,
                        "DEVICE_ID" => 213213219,
                        "APPLIANCE_ID" => 32,
                        "OPERATION_ID" => 5,
                        "LEARNING_VALUE" => 76,
                        "CREATED_AT" => "2021-03-13 14:40:11",
                        "UPDATED_AT" => "2021-03-13 14:40:11"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 142,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 32,
                        "OPERATION_ID" => 7,
                        "LEARNING_VALUE" => 25,
                        "CREATED_AT" => "2021-03-27 15:25:34",
                        "UPDATED_AT" => "2021-03-27 15:25:34"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 35,
                "APPLIANCE_NAME" => "AC-room1",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "CREATED_AT" => "2019-09-13 07:41:30",
                "UPDATED_AT" => "2019-09-13 07:41:30",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 136,
                        "DEVICE_ID" => 213213219,
                        "APPLIANCE_ID" => 35,
                        "OPERATION_ID" => 2,
                        "LEARNING_VALUE" => 27,
                        "CREATED_AT" => "2021-02-09 11:14:06",
                        "UPDATED_AT" => "2021-02-09 11:14:06"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 36,
                "APPLIANCE_NAME" => "Bedroom TV",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Devant",
                "CREATED_AT" => "2019-09-13 10:55:56",
                "UPDATED_AT" => "2019-09-13 10:55:56",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 145,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 36,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 11,
                        "CREATED_AT" => "2021-03-27 15:35:23",
                        "UPDATED_AT" => "2021-03-27 15:35:23"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 53,
                "APPLIANCE_NAME" => "Air con",
                "APPLIANCE_TYPE" => "AC2",
                "BRAND_NAME" => "Samsung",
                "CREATED_AT" => "2019-12-04 12:06:11",
                "UPDATED_AT" => "2019-12-04 12:06:11",
                "ir_learning" => []
            ],
            [
                "APPLIANCE_ID" => 55,
                "APPLIANCE_NAME" => "asdasd",
                "APPLIANCE_TYPE" => "asdasdasd",
                "BRAND_NAME" => "asdasdasdas",
                "CREATED_AT" => "2019-12-06 09:24:04",
                "UPDATED_AT" => "2019-12-06 09:24:04",
                "ir_learning" => []
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "CREATED_AT" => "2020-01-20 14:50:26",
                "UPDATED_AT" => "2020-01-20 14:50:26",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 91,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 1,
                        "LEARNING_VALUE" => 22,
                        "CREATED_AT" => "2020-10-06 05:52:56",
                        "UPDATED_AT" => "2020-10-06 05:52:56"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 92,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 16,
                        "LEARNING_VALUE" => 53,
                        "CREATED_AT" => "2020-10-07 00:45:54",
                        "UPDATED_AT" => "2020-10-07 00:45:54"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 93,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 11,
                        "LEARNING_VALUE" => 19,
                        "CREATED_AT" => "2020-10-07 00:54:19",
                        "UPDATED_AT" => "2020-10-07 00:54:19"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 124,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 14,
                        "LEARNING_VALUE" => 14,
                        "CREATED_AT" => "2020-10-22 02:05:40",
                        "UPDATED_AT" => "2020-10-22 02:05:40"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 126,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 15,
                        "LEARNING_VALUE" => 10,
                        "CREATED_AT" => "2020-10-22 02:08:04",
                        "UPDATED_AT" => "2020-10-22 02:08:04"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 127,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 13,
                        "LEARNING_VALUE" => 21,
                        "CREATED_AT" => "2020-10-22 02:36:36",
                        "UPDATED_AT" => "2020-10-22 02:36:36"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 128,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 12,
                        "LEARNING_VALUE" => 28,
                        "CREATED_AT" => "2020-10-22 05:57:20",
                        "UPDATED_AT" => "2020-10-22 05:57:20"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 130,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 6,
                        "LEARNING_VALUE" => 18,
                        "CREATED_AT" => "2020-11-04 07:01:34",
                        "UPDATED_AT" => "2020-11-04 07:01:34"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 131,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 7,
                        "LEARNING_VALUE" => 23,
                        "CREATED_AT" => "2020-11-04 07:02:03",
                        "UPDATED_AT" => "2020-11-04 07:02:03"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 132,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 8,
                        "LEARNING_VALUE" => 26,
                        "CREATED_AT" => "2020-11-04 07:02:40",
                        "UPDATED_AT" => "2020-11-04 07:02:40"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 133,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 9,
                        "LEARNING_VALUE" => 24,
                        "CREATED_AT" => "2020-11-04 07:03:08",
                        "UPDATED_AT" => "2020-11-04 07:03:08"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 134,
                        "DEVICE_ID" => 10,
                        "APPLIANCE_ID" => 57,
                        "OPERATION_ID" => 10,
                        "LEARNING_VALUE" => 30,
                        "CREATED_AT" => "2020-11-04 07:03:46",
                        "UPDATED_AT" => "2020-11-04 07:03:46"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 59,
                "APPLIANCE_NAME" => "New try",
                "APPLIANCE_TYPE" => "Samsung",
                "BRAND_NAME" => "TV",
                "CREATED_AT" => "2020-04-30 16:40:56",
                "UPDATED_AT" => "2020-04-30 16:40:56",
                "ir_learning" => []
            ],
            [
                "APPLIANCE_ID" => 71,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "LG",
                "CREATED_AT" => "2020-05-27 15:19:39",
                "UPDATED_AT" => "2020-05-27 15:19:39",
                "ir_learning" => []
            ],
            [
                "APPLIANCE_ID" => 72,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Phillips",
                "CREATED_AT" => "2020-06-03 12:32:13",
                "UPDATED_AT" => "2020-06-03 12:32:13",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 147,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 72,
                        "OPERATION_ID" => 5,
                        "LEARNING_VALUE" => 13,
                        "CREATED_AT" => "2021-03-27 15:42:49",
                        "UPDATED_AT" => "2021-03-27 15:42:49"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 73,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Relx",
                "CREATED_AT" => "2020-06-03 12:40:11",
                "UPDATED_AT" => "2020-06-03 12:40:11",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 149,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 73,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 15,
                        "CREATED_AT" => "2021-03-27 15:44:23",
                        "UPDATED_AT" => "2021-03-27 15:44:23"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 74,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Lenovo",
                "CREATED_AT" => "2020-06-04 14:51:35",
                "UPDATED_AT" => "2020-06-04 14:51:35",
                "ir_learning" => []
            ],
            [
                "APPLIANCE_ID" => 75,
                "APPLIANCE_NAME" => "Televison",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Samsung",
                "CREATED_AT" => "2020-12-16 01:03:19",
                "UPDATED_AT" => "2020-12-16 01:03:19",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 146,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 75,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 12,
                        "CREATED_AT" => "2021-03-27 15:42:13",
                        "UPDATED_AT" => "2021-03-27 15:42:13"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 78,
                "APPLIANCE_NAME" => "Aircon",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "CREATED_AT" => "2021-03-23 14:01:55",
                "UPDATED_AT" => "2021-03-23 14:01:55",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 148,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 78,
                        "OPERATION_ID" => 7,
                        "LEARNING_VALUE" => 29,
                        "CREATED_AT" => "2021-03-27 15:43:35",
                        "UPDATED_AT" => "2021-03-27 15:43:35"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 79,
                "APPLIANCE_NAME" => "Air Cleaner",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "BIONAIRE",
                "CREATED_AT" => "2021-03-24 15:20:11",
                "UPDATED_AT" => "2021-03-24 15:20:11",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 140,
                        "DEVICE_ID" => 213213219,
                        "APPLIANCE_ID" => 79,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 70,
                        "CREATED_AT" => "2021-03-24 15:21:53",
                        "UPDATED_AT" => "2021-03-24 15:21:53"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 141,
                        "DEVICE_ID" => 213213219,
                        "APPLIANCE_ID" => 79,
                        "OPERATION_ID" => 5,
                        "LEARNING_VALUE" => 71,
                        "CREATED_AT" => "2021-03-24 15:22:28",
                        "UPDATED_AT" => "2021-03-24 15:22:28"
                    ],
                    [
                        "IR_LEARNING_LIST_ID" => 144,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 79,
                        "OPERATION_ID" => 4,
                        "LEARNING_VALUE" => 8,
                        "CREATED_AT" => "2021-03-27 15:34:34",
                        "UPDATED_AT" => "2021-03-27 15:34:34"
                    ]
                ]
            ],
            [
                "APPLIANCE_ID" => 83,
                "APPLIANCE_NAME" => "Wall TV",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Samsung",
                "CREATED_AT" => "2021-03-29 12:03:14",
                "UPDATED_AT" => "2021-03-29 12:03:14",
                "ir_learning" => []
            ],
            [
                "APPLIANCE_ID" => 88,
                "APPLIANCE_NAME" => "ABCDEFGHIJKLM",
                "APPLIANCE_TYPE" => "NOPQRSTUVWXYZ",
                "BRAND_NAME" => "SAMSUNGLENOVO",
                "CREATED_AT" => "2021-03-30 10:12:46",
                "UPDATED_AT" => "2021-03-30 10:12:46",
                "ir_learning" => [
                    [
                        "IR_LEARNING_LIST_ID" => 155,
                        "DEVICE_ID" => 213213237,
                        "APPLIANCE_ID" => 88,
                        "OPERATION_ID" => 3,
                        "LEARNING_VALUE" => 7,
                        "CREATED_AT" => "2021-03-30 10:33:48",
                        "UPDATED_AT" => "2021-03-30 10:33:48"
                    ]
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
