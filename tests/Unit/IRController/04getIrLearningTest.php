<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getIrLearningTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getIrLearning";

    /**
     * 取得データの検証
     */
    public function test_getIrLearning()
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
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 25,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 36,
                "APPLIANCE_NAME" => "Bedroom TV",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Devant",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 11,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 72,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Phillips",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 13,
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF"
            ],
            [
                "APPLIANCE_ID" => 73,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Relx",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 15,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 75,
                "APPLIANCE_NAME" => "Televison",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Samsung",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 12,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 78,
                "APPLIANCE_NAME" => "Aircon",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 29,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 79,
                "APPLIANCE_NAME" => "Air Cleaner",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "BIONAIRE",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 8,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 88,
                "APPLIANCE_NAME" => "ABCDEFGHIJKLM",
                "APPLIANCE_TYPE" => "NOPQRSTUVWXYZ",
                "BRAND_NAME" => "SAMSUNGLENOVO",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 7,
                "OPERATION_ID" => 3,
                "OPERATION_NAME" => "POWER"
            ]
        ];

        $body = [
            'FLOOR_ID' => '1',
            'ROOM_ID' => '1'
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);

        //----------------------------------------------------------------------       
        // CASE2：In case of branching of else-if statement / else-if分岐の場合     
        //----------------------------------------------------------------------        
        // 期待値
        $expected = [
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 25,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 36,
                "APPLIANCE_NAME" => "Bedroom TV",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Devant",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 11,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 72,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Phillips",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 13,
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF"
            ],
            [
                "APPLIANCE_ID" => 73,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Relx",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 15,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 75,
                "APPLIANCE_NAME" => "Televison",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Samsung",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 12,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 78,
                "APPLIANCE_NAME" => "Aircon",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 29,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 79,
                "APPLIANCE_NAME" => "Air Cleaner",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "BIONAIRE",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 8,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 88,
                "APPLIANCE_NAME" => "ABCDEFGHIJKLM",
                "APPLIANCE_TYPE" => "NOPQRSTUVWXYZ",
                "BRAND_NAME" => "SAMSUNGLENOVO",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 7,
                "OPERATION_ID" => 3,
                "OPERATION_NAME" => "POWER"
            ]
        ];

        $body = [
            'FLOOR_ID' => '1',
            'ROOM_ID' => null
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);

        //----------------------------------------------------------------------        
        // CASE3：In case of branching of else statement / else分岐の場合     
        //----------------------------------------------------------------------        
        // 期待値
        $expected = [
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 17,
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF"
            ],
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 20,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213219,
                "DEVICE_NAME" => "Motion Detector",
                "LEARNING_VALUE" => 0,
                "OPERATION_ID" => 1,
                "OPERATION_NAME" => "TEMP_16"
            ],
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213219,
                "DEVICE_NAME" => "Motion Detector",
                "LEARNING_VALUE" => 75,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213219,
                "DEVICE_NAME" => "Motion Detector",
                "LEARNING_VALUE" => 76,
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF"
            ],
            [
                "APPLIANCE_ID" => 32,
                "APPLIANCE_NAME" => "Aircon-01",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 25,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 35,
                "APPLIANCE_NAME" => "AC-room1",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213219,
                "DEVICE_NAME" => "Motion Detector",
                "LEARNING_VALUE" => 27,
                "OPERATION_ID" => 2,
                "OPERATION_NAME" => "TEMP_30"
            ],
            [
                "APPLIANCE_ID" => 36,
                "APPLIANCE_NAME" => "Bedroom TV",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Devant",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 11,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 10,
                "OPERATION_ID" => 15,
                "OPERATION_NAME" => "TEMP_26"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 14,
                "OPERATION_ID" => 14,
                "OPERATION_NAME" => "TEMP_25"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 18,
                "OPERATION_ID" => 6,
                "OPERATION_NAME" => "TEMP_17"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 19,
                "OPERATION_ID" => 11,
                "OPERATION_NAME" => "TEMP_22"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 21,
                "OPERATION_ID" => 13,
                "OPERATION_NAME" => "TEMP_24"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 22,
                "OPERATION_ID" => 1,
                "OPERATION_NAME" => "TEMP_16"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 23,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 24,
                "OPERATION_ID" => 9,
                "OPERATION_NAME" => "TEMP_20"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 26,
                "OPERATION_ID" => 8,
                "OPERATION_NAME" => "TEMP_19"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 28,
                "OPERATION_ID" => 12,
                "OPERATION_NAME" => "TEMP_23"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 30,
                "OPERATION_ID" => 10,
                "OPERATION_NAME" => "TEMP_21"
            ],
            [
                "APPLIANCE_ID" => 57,
                "APPLIANCE_NAME" => "Panasonic",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 10,
                "DEVICE_NAME" => "CO2 Detector",
                "LEARNING_VALUE" => 53,
                "OPERATION_ID" => 16,
                "OPERATION_NAME" => "TEMP_27"
            ],
            [
                "APPLIANCE_ID" => 72,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Phillips",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 13,
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF"
            ],
            [
                "APPLIANCE_ID" => 73,
                "APPLIANCE_NAME" => "Television",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Relx",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 15,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 75,
                "APPLIANCE_NAME" => "Televison",
                "APPLIANCE_TYPE" => "TV",
                "BRAND_NAME" => "Samsung",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 12,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 78,
                "APPLIANCE_NAME" => "Aircon",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "Panasonic",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 29,
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18"
            ],
            [
                "APPLIANCE_ID" => 79,
                "APPLIANCE_NAME" => "Air Cleaner",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "BIONAIRE",
                "DEVICE_ID" => 213213219,
                "DEVICE_NAME" => "Motion Detector",
                "LEARNING_VALUE" => 70,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 79,
                "APPLIANCE_NAME" => "Air Cleaner",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "BIONAIRE",
                "DEVICE_ID" => 213213219,
                "DEVICE_NAME" => "Motion Detector",
                "LEARNING_VALUE" => 71,
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF"
            ],
            [
                "APPLIANCE_ID" => 79,
                "APPLIANCE_NAME" => "Air Cleaner",
                "APPLIANCE_TYPE" => "AC",
                "BRAND_NAME" => "BIONAIRE",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 8,
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON"
            ],
            [
                "APPLIANCE_ID" => 88,
                "APPLIANCE_NAME" => "ABCDEFGHIJKLM",
                "APPLIANCE_TYPE" => "NOPQRSTUVWXYZ",
                "BRAND_NAME" => "SAMSUNGLENOVO",
                "DEVICE_ID" => 213213237,
                "DEVICE_NAME" => "IR Remote",
                "LEARNING_VALUE" => 7,
                "OPERATION_ID" => 3,
                "OPERATION_NAME" => "POWER"
            ]
        ];

        $body = [
            'FLOOR_ID' => null,
            'ROOM_ID' => null
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);
    }
}
