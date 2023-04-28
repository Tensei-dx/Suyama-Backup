<?php

namespace Tests\Unit;

use App\Http\Controllers\RoomController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoomController_getRoomAll extends TestCase
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

    public function test_getRoomAll()
    {

        //Query
        $this->uri = "/getRoomAll";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        //Expected Results
        $expected = [

            [
                "ROOM_ID" => 1,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 101",
                "ROOM_STATUS" => 2,
                "ROOM_MESSAGE" => "CLEAN UP TIME",
                "ROOM_TOTAL_PEOPLE" => 6,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "stp_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => 1
            ],
            [
                "ROOM_ID" => 2,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 102",
                "ROOM_STATUS" => 1,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 5,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "elect_room"
                ],
                "STATUS_ID" => 202,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 3,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 103",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 5,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "chief_hospital_office_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 4,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 104",
                "ROOM_STATUS" => 1,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 5,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "kitchen_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 5,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 105",
                "ROOM_STATUS" => 4,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 19,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "pharmacy"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 6,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 106",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 19,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "cadaver"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 7,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 107",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 19,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "minor_or"
                ],
                "STATUS_ID" => 202,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 8,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 108",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 19,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "emergency_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 9,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 109",
                "ROOM_STATUS" => 4,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 1,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "pump_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 10,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 110",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 1,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "supply_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 11,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 111",
                "ROOM_STATUS" => 2,
                "ROOM_MESSAGE" => "DON'T DISTURB",
                "ROOM_TOTAL_PEOPLE" => 7,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "accounting_department"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 12,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "ROOM 112",
                "ROOM_STATUS" => 1,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 7,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "laboratory"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
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
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 25,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Private Room 1",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 7,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_1"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [

                "ROOM_ID" => 26,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Female Ward",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 1,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "female_ward"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 27,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Treatment 1",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 1,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "treatment_med_room"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 28,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Private Room 2",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 21,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_3"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 29,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Private Room 3",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_4"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 30,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Private Room 4",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_5"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 31,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "New Room Name",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_2"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 32,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Male Ward",
                "ROOM_STATUS" => 3,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "male_ward"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 33,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Treatment 2",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "treatment_med_room_2"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 34,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Private Room 6",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_8"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 35,
                "FLOOR_ID" => 3,
                "ROOM_NAME" => "Private Room 7",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 31,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room_7"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 38,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 2",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room2"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 40,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 4",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room4"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 41,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 5",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 313,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room5"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 42,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Treatment 3",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "treatment_med_room1"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 43,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 6",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room6"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 44,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 7",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room7"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 45,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 8",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 123,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room8"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 46,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 9",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 123,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room9"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 47,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 10",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 3,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room10"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 48,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 11",
                "ROOM_STATUS" => 3,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room11"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 49,
                "FLOOR_ID" => 4,
                "ROOM_NAME" => "Private Room 12",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room12"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 50,
                "FLOOR_ID" => 5,
                "ROOM_NAME" => "Private Room 13",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 2,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room13"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 1139,
                "FLOOR_ID" => 5,
                "ROOM_NAME" => "Private Room 14",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 1,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "private_room14"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ],
            [
                "ROOM_ID" => 1141,
                "FLOOR_ID" => 1,
                "ROOM_NAME" => "Xreersx",
                "ROOM_STATUS" => 0,
                "ROOM_MESSAGE" => "NO MESSAGE",
                "ROOM_TOTAL_PEOPLE" => 0,
                "ROOM_MAP_DATA" => [
                    "ROOM_MAP" => "key-simple-shape-with-circular-top"
                ],
                "STATUS_ID" => 201,
                "ROOM_TYPE_ID" => null
            ]
        ];

        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
