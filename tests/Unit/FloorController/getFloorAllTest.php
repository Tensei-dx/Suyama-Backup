<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getFloorAllTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getFloorAll";
    /**
     * 取得データの検証
     */
    public function test_getFloorAll()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // 期待値
        $expected = [
            ["FLOOR_ID" => 1, "FLOOR_MAP_DATA" => ["floorImage" => "imgs\/floors\/1st Floor.png", "roomMap" => [["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/food1.jpg", "roomMap" => "food1", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/food1.jpg", "roomMap" => "food1", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/key-simple-shape-with-circular-top.png", "roomMap" => "key-simple-shape-with-circular-top", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/[\"ROOM_MAP\"=> \"private_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"private_room\"]", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/[\"ROOM_MAP\"=> \"private_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"private_room\"]", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/[\"ROOM_MAP\"=> \"private_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"private_room\"]", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/[\"ROOM_MAP\"=> \"private_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"private_room\"]", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/[\"ROOM_MAP\"=> \"stp_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"stp_room\"]", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "\/imgs\/rooms\/[\"ROOM_MAP\"=> \"stp_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"stp_room\"]", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "imgs\/rooms\/deleteFloorTest.PNG", "roomMap" => "deleteFloorTest", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "imgs\/rooms\/getFloorRooms.PNG", "roomMap" => "getFloorRooms", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "imgs\/rooms\/getFloorTest.PNG", "roomMap" => "getFloorTest", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "imgs\/rooms\/getFloorTest.PNG", "roomMap" => "getFloorTest", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "imgs\/rooms\/getFloorTest.PNG", "roomMap" => "getFloorTest", "status" => "hilight-green"], ["coor" => null, "deviceCoor" => [], "roomImage" => "imgs\/rooms\/[\"ROOM_MAP\"=> \"stp_room\"]", "roomMap" => "[\"ROOM_MAP\"=> \"stp_room\"]", "status" => "hilight-green"], ["coor" => "M0.683 64.277 L5.237 63.212 L5.146 96.238 L0.956 96.238Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/pump_room.png", "roomMap" => "pump_room", "status" => "hilight-green"], ["coor" => "M13.463 63.075 L13.463 97.167 L10.002 96.812 L10.002 90.420 L9.365 90.420 L9.456 96.812 L5.631 96.457 L5.722 84.027 L9.729 84.738 L10.184 85.093 L10.002 80.831 L5.813 80.476 L5.631 80.476 L5.722 63.075 L9.547 63.075 L9.456 75.504 L10.093 75.860 L10.093 62.720Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/supply_room.PNG", "roomMap" => "supply_room", "status" => "hilight-green"], ["coor" => "M14.100 63.075 L23.026 63.075 L23.026 96.102 L14.100 96.457Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/accounting_department.PNG", "roomMap" => "accounting_department", "status" => "hilight-green"], ["coor" => "M15.740 17.264 L15.649 41.768 L30.585 41.768 L30.585 36.441 L31.404 36.441 L31.404 41.412 L34.228 41.768 L34.137 31.469 L30.494 32.534 L30.494 28.273 L34.228 28.628 L34.228 17.264 L31.222 17.619 L31.222 23.301 L30.494 23.301 L30.403 17.619 L23.754 17.619 L23.663 33.600 L22.935 33.600 L23.026 17.264Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/kitchen_room.png", "roomMap" => "kitchen_room", "status" => "hilight-green"], ["coor" => "M28.689 62.146 L41.621 63.212 L41.257 84.164 L37.432 83.098 L37.158 95.172 L23.497 96.593 L23.953 84.874 L29.053 83.809Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/laboratory.PNG", "roomMap" => "laboratory", "status" => "hilight-green"], ["coor" => "M34.865 17.619 L46.432 17.619 L46.432 41.768 L39.692 41.057 L39.692 25.077 L38.964 25.432 L38.964 41.768 L34.865 41.412Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/pharmacy.png", "roomMap" => "pharmacy", "status" => "hilight-green"], ["coor" => "M42.395 61.436 L42.031 96.593 L50.592 97.303 L50.956 61.436Z", "deviceCoor" => [["coor" => ["cx" => "39.390", "cy" => "17.349"], "name" => "a85v763v8i"]], "roomImage" => "imgs\/rooms\/stp_room.png", "roomMap" => "stp_room", "status" => "hilight-green"], ["coor" => "M47.069 17.619 L47.069 33.600 L52.442 33.600 L52.351 17.264Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/cadaver.png", "roomMap" => "cadaver", "status" => "hilight-green"], ["coor" => "M5.084 16.909 L8.090 17.264 L7.998 41.768 L5.084 41.768Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/elect_room.png", "roomMap" => "elect_room", "status" => "hilight-green"], ["coor" => "M59.546 17.619 L59.546 41.768 L63.645 41.768 L63.554 29.338 L67.470 28.628 L67.379 17.264Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/minor_or.png", "roomMap" => "minor_or", "status" => "hilight-green"], ["coor" => "M67.925 17.264 L81.586 17.264 L81.677 28.628 L84.956 28.628 L85.047 77.280 L82.041 77.280 L82.041 60.589 L79.218 60.589 L79.309 41.412 L77.670 41.412 L77.761 43.898 L78.854 44.253 L78.763 60.234 L75.120 60.589 L75.302 47.805 L74.665 47.450 L74.756 60.234 L71.477 60.234 L71.477 44.253 L72.752 44.609 L72.752 41.412 L67.925 41.768Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/emergency_room.PNG", "roomMap" => "emergency_room", "status" => "hilight-green"], ["coor" => "M8.727 17.264 L8.727 41.768 L15.102 41.412 L15.011 31.114 L13.827 30.404 L13.827 27.918 L15.011 27.918 L15.011 17.264 L12.279 17.619 L12.461 30.404 L11.550 30.759 L11.550 17.264Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/chief_hospital_office_room.png", "roomMap" => "chief_hospital_office_room", "status" => "hilight-green"]]], "FLOOR_NAME" => "Ground Floor A"], ["FLOOR_ID" => 2, "FLOOR_MAP_DATA" => ["floorImage" => "imgs\/floors\/2nd Floor.png", "roomMap" => [["coor" => "M0.530 2.099 L0.530 36.527 L23.026 36.205 L22.935 2.742Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/delivery_roo.PNG", "roomMap" => "delivery_roo", "status" => "hilight-empty"], ["coor" => "M0.530 63.408 L22.935 62.443 L23.026 96.870 L0.713 96.549Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/operating_room.PNG", "roomMap" => "operating_room", "status" => "hilight-empty"], ["coor" => "M23.572 1.952 L23.572 36.380 L30.949 36.059 L30.949 13.536 L29.310 13.536 L29.310 10.640 L30.949 10.318 L30.858 2.596 L27.761 2.596 L27.853 12.249 L26.851 12.249 L26.942 2.596Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/labor_room.PNG", "roomMap" => "labor_room", "status" => "hilight-empty"], ["coor" => "M31.678 2.918 L31.678 36.702 L38.872 36.380 L38.872 2.596Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/recovery_room.PNG", "roomMap" => "recovery_room", "status" => "hilight-empty"], ["coor" => "M40.239 61.799 L30.312 61.799 L30.220 78.530 L31.496 78.852 L31.496 81.104 L23.663 81.426 L23.663 97.192 L29.674 97.192 L29.674 88.183 L30.403 88.183 L30.403 96.870 L35.047 97.192 L34.956 69.843 L35.776 70.486 L35.685 84.000 L40.239 84.322 L40.056 72.095 L37.324 71.773 L37.324 69.843 L40.239 70.165Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/others.PNG", "roomMap" => "others", "status" => "hilight-empty"], ["coor" => "M40.785 61.799 L52.260 62.443 L52.260 97.192 L47.342 97.192 L47.433 93.975 L46.887 93.975 L46.887 96.870 L40.876 96.870 L40.785 81.426 L46.796 81.426 L46.887 88.183 L47.525 88.183 L47.525 79.496 L40.785 79.496Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/central_stereleing_room.PNG", "roomMap" => "central_stereleing_room", "status" => "hilight-empty"], ["coor" => "M48.800 2.274 L56.085 2.596 L56.085 36.059 L39.601 36.059 L39.601 15.466 L41.240 15.466 L41.240 12.892 L39.510 12.892 L39.510 2.274 L42.698 2.274 L42.698 6.457 L43.517 6.457 L43.335 2.596 L48.071 2.596 L48.071 12.892 L43.426 12.570 L43.335 9.996 L42.698 10.318 L42.789 30.267 L43.426 29.945 L43.426 15.788 L48.800 15.788Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/dr_lounge.PNG", "roomMap" => "dr_lounge", "status" => "hilight-empty"], ["coor" => "M61.914 2.596 L61.914 36.380 L66.650 36.380 L66.832 14.179 L63.462 13.857 L63.462 10.640 L64.191 10.640 L64.191 11.605 L66.650 11.283 L66.741 2.274 L64.282 2.274 L64.282 4.848 L63.462 5.170 L63.462 2.274Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/chief_nurses_office.PNG", "roomMap" => "chief_nurses_office", "status" => "hilight-empty"], ["coor" => "M67.196 36.059 L85.047 36.380 L85.047 24.475 L85.684 24.475 L85.776 36.380 L88.872 36.059 L88.872 2.918 L81.586 2.918 L81.586 16.110 L86.959 15.788 L86.959 18.684 L80.858 17.718 L80.858 2.918 L77.670 2.918 L77.761 15.788 L79.400 15.788 L79.400 18.362 L76.941 18.040 L76.941 2.596 L71.295 2.918 L71.295 3.239 L70.657 3.561 L70.657 1.952 L67.288 2.274 L67.288 11.605 L70.657 11.605 L70.657 8.388 L71.204 8.388 L71.204 15.144 L70.657 14.823 L70.475 13.857 L67.288 13.857 L67.288 23.832 L70.657 23.832 L70.657 19.971 L71.477 19.971 L71.386 26.406 L67.288 26.406Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/dark_room.PNG", "roomMap" => "dark_room", "status" => "hilight-empty"], ["coor" => "M89.601 2.596 L89.510 36.059 L99.346 36.380 L99.163 2.918Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/clinic.PNG", "roomMap" => "clinic", "status" => "hilight-empty"], ["coor" => "M91.331 63.086 L91.331 96.870 L82.588 97.192 L82.588 63.086Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/dental_clinic.PNG", "roomMap" => "dental_clinic", "status" => "hilight-empty"], ["coor" => "M91.877 97.192 L99.163 96.870 L99.163 62.764 L91.877 63.086Z", "deviceCoor" => [], "roomImage" => "imgs\/rooms\/medical_records_room.PNG", "roomMap" => "medical_records_room", "status" => "hilight-empty"]]], "FLOOR_NAME" => "Mezzanine Floor"]

        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        //    $actual->assertExactJson($expected); 
    }
}