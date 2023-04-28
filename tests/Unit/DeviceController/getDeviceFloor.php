<?php

namespace Tests\Unit;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeviceController_getDeviceFloor extends TestCase
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

    public function test_getDeviceFloor()
    {

        $id = 1;
        //Query
        $this->uri = "api/getDeviceFloor/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected result
        $expected = [
            [
                "FLOOR_ID" => 1,
                "FLOOR_NAME" => "Ground Floor",
                "FLOOR_MAP_DATA" => [
                    "roomMap" => [
                        [
                            "coor" => "M0.410 2.131 L0.319 43.325 L4.781 40.839 L4.872 0.355Z",
                            "status" => "hilight-green",
                            "roomMap" => "stp_room",
                            "roomImage" => "imgs/rooms/stp_room.png",
                            "deviceCoor" => [
                                [
                                    "coor" => [
                                        "cx" => "22.895",
                                        "cy" => "11.092"
                                    ],
                                    "name" => "zzn0q7bw5z"
                                ]
                            ]
                        ],
                        [
                            "coor" => "M5.084 16.909 L8.090 17.264 L7.998 41.768 L5.084 41.768Z",
                            "status" => "hilight-green",
                            "roomMap" => "elect_room",
                            "roomImage" => "imgs/rooms/elect_room.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M8.727 17.264 L8.727 41.768 L15.102 41.412 L15.011 31.114 L13.827 30.404 L13.827 27.918 L15.011 27.918 L15.011 17.264 L12.279 17.619 L12.461 30.404 L11.550 30.759 L11.550 17.264Z",
                            "status" => "hilight-green",
                            "roomMap" => "chief_hospital_office_room",
                            "roomImage" => "imgs/rooms/chief_hospital_office_room.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M15.740 17.264 L15.649 41.768 L30.585 41.768 L30.585 36.441 L31.404 36.441 L31.404 41.412 L34.228 41.768 L34.137 31.469 L30.494 32.534 L30.494 28.273 L34.228 28.628 L34.228 17.264 L31.222 17.619 L31.222 23.301 L30.494 23.301 L30.403 17.619 L23.754 17.619 L23.663 33.600 L22.935 33.600 L23.026 17.264Z",
                            "status" => "hilight-green",
                            "roomMap" => "kitchen_room",
                            "roomImage" => "imgs/rooms/kitchen_room.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M34.865 17.619 L46.432 17.619 L46.432 41.768 L39.692 41.057 L39.692 25.077 L38.964 25.432 L38.964 41.768 L34.865 41.412Z",
                            "status" => "hilight-green",
                            "roomMap" => "pharmacy",
                            "roomImage" => "imgs/rooms/pharmacy.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M47.069 17.619 L47.069 33.600 L52.442 33.600 L52.351 17.264Z",
                            "status" => "hilight-green",
                            "roomMap" => "cadaver",
                            "roomImage" => "imgs/rooms/cadaver.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M59.546 17.619 L59.546 41.768 L63.645 41.768 L63.554 29.338 L67.470 28.628 L67.379 17.264Z",
                            "status" => "hilight-green",
                            "roomMap" => "minor_or",
                            "roomImage" => "imgs/rooms/minor_or.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M67.925 17.264 L81.586 17.264 L81.677 28.628 L84.956 28.628 L85.047 77.280 L82.041 77.280 L82.041 60.589 L79.218 60.589 L79.309 41.412 L77.670 41.412 L77.761 43.898 L78.854 44.253 L78.763 60.234 L75.120 60.589 L75.302 47.805 L74.665 47.450 L74.756 60.234 L71.477 60.234 L71.477 44.253 L72.752 44.609 L72.752 41.412 L67.925 41.768Z",
                            "status" => "hilight-green",
                            "roomMap" => "emergency_room",
                            "roomImage" => "imgs/rooms/emergency_room.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M0.683 64.277 L5.237 63.212 L5.146 96.238 L0.956 96.238Z",
                            "status" => "hilight-green",
                            "roomMap" => "pump_room",
                            "roomImage" => "imgs/rooms/pump_room.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M13.463 63.075 L13.463 97.167 L10.002 96.812 L10.002 90.420 L9.365 90.420 L9.456 96.812 L5.631 96.457 L5.722 84.027 L9.729 84.738 L10.184 85.093 L10.002 80.831 L5.813 80.476 L5.631 80.476 L5.722 63.075 L9.547 63.075 L9.456 75.504 L10.093 75.860 L10.093 62.720Z",
                            "status" => "hilight-green",
                            "roomMap" => "supply_room",
                            "roomImage" => "imgs/rooms/supply_room.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M14.100 63.075 L23.026 63.075 L23.026 96.102 L14.100 96.457Z",
                            "status" => "hilight-green",
                            "roomMap" => "accounting_department",
                            "roomImage" => "imgs/rooms/accounting_department.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => "M28.689 62.146 L41.621 63.212 L41.257 84.164 L37.432 83.098 L37.158 95.172 L23.497 96.593 L23.953 84.874 L29.053 83.809Z",
                            "status" => "hilight-green",
                            "roomMap" => "laboratory",
                            "roomImage" => "imgs/rooms/laboratory.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "deleteFloorTest",
                            "roomImage" => "imgs/rooms/deleteFloorTest.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "getFloorRooms",
                            "roomImage" => "imgs/rooms/getFloorRooms.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "getFloorTest",
                            "roomImage" => "imgs/rooms/getFloorTest.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "getFloorTest",
                            "roomImage" => "imgs/rooms/getFloorTest.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "getFloorTest",
                            "roomImage" => "imgs/rooms/getFloorTest.PNG",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"stp_room\"}",
                            "roomImage" => "imgs/rooms/{\"ROOM_MAP\": \"stp_room\"}",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "food1",
                            "roomImage" => "/imgs/rooms/food1.jpg",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "food1",
                            "roomImage" => "/imgs/rooms/food1.jpg",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "key-simple-shape-with-circular-top",
                            "roomImage" => "/imgs/rooms/key-simple-shape-with-circular-top.png",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"stp_room\"}",
                            "roomImage" => "/imgs/rooms/{\"ROOM_MAP\": \"stp_room\"}",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"stp_room\"}",
                            "roomImage" => "/imgs/rooms/{\"ROOM_MAP\": \"stp_room\"}",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"private_room\"}",
                            "roomImage" => "/imgs/rooms/{\"ROOM_MAP\": \"private_room\"}",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"private_room\"}",
                            "roomImage" => "/imgs/rooms/{\"ROOM_MAP\": \"private_room\"}",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"private_room\"}",
                            "roomImage" => "/imgs/rooms/{\"ROOM_MAP\": \"private_room\"}",
                            "deviceCoor" => []
                        ],
                        [
                            "coor" => null,
                            "status" => "hilight-green",
                            "roomMap" => "{\"ROOM_MAP\": \"private_room\"}",
                            "roomImage" => "/imgs/rooms/{\"ROOM_MAP\": \"private_room\"}",
                            "deviceCoor" => []
                        ]
                    ],
                    "floorImage" => "imgs/floors/1st Floor.png"
                ]
            ]
        ];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
