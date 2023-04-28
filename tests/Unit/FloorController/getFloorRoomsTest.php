<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getFloorTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "getFloorRooms/2";
    /**
     * 取得データの検証
     */
    public function test_getFloor()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // 期待値
        $expected = [
            [
                "ROOM_ID" => 13,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 113",
                "ROOM_STATUS" => 3,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 5,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "delivery_roo"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 14,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 114",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 4,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "labor_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 15,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 115",
                "ROOM_STATUS" => 2,
                "ROOM_MESSAGE" => "CLEAN UP TIME",
                "ROOM_TOTAL_PEOPLE" => 5,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "recovery_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 16,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 116",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 4,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "dr_lounge"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 17,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 117",
                "ROOM_STATUS" => 2,
                "ROOM_MESSAGE" => "CLEAN UP TIME",
                "ROOM_TOTAL_PEOPLE" => 4,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "chief_nurses_office"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 18,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 118",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 3,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "dark_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 19,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 119",
                "ROOM_STATUS" => 1,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 4,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "clinic"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 20,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "ROOM 120",
                "ROOM_STATUS" => 3,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 45,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "operating_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 21,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "Others",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 4,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "others"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 22,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "Central ",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 6,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "central_stereleing_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 23,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "Dental Clinic",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 6,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "dental_clinic"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ],
            [
                "ROOM_ID" => 24,
                "FLOOR_ID" => 2,
                "ROOM_NAME" => "Medical Records ",
                "ROOM_STATUS" => 3,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 68,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "medical_records_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 0
            ]
        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);
    }
}
