<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getUserNotification_Test.php
 *
 * Create  => 2021.02.16 TP Uddin
 * Update  => 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_getUserNotification
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_getUserNotification extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "GET";

    /** @var string $uri */
    protected $uri;

    /**
     *
     */
    public function test_getUserNotification()
    {
        $id = 1;
        $this->uri = "client/notifications/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Expected result
        $expected = [
            [
                "NOTIFICATION_ID" => 149777,
                "OBJECT_NAME" => "Device/Sensor Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Dummy Dust was offline.",
                "CREATED_AT" => "2021/02/22 16:00"
            ],
            [
                "NOTIFICATION_ID" => 149775,
                "OBJECT_NAME" => "Device/Sensor Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Dummy Dust was offline.",
                "CREATED_AT" => "2021/02/22 16:00"
            ],
            [
                "NOTIFICATION_ID" => 149776,
                "OBJECT_NAME" => "Device/Sensor Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Dummy Dust was offline.",
                "CREATED_AT" => "2021/02/22 16:00"
            ],
            [
                "NOTIFICATION_ID" => 149774,
                "OBJECT_NAME" => "Device Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Motion_SR was triggered.",
                "CREATED_AT" => "2021/02/22 15:45"
            ],
            [
                "NOTIFICATION_ID" => 149773,
                "OBJECT_NAME" => "Device Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Motion_SR was triggered.",
                "CREATED_AT" => "2021/02/22 15:44"
            ],
            [
                "NOTIFICATION_ID" => 149772,
                "OBJECT_NAME" => "Device/Sensor Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Dummy Dust was offline.",
                "CREATED_AT" => "2021/02/22 15:30"
            ],
            [
                "NOTIFICATION_ID" => 149770,
                "OBJECT_NAME" => "Device/Sensor Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Dummy Dust was offline.",
                "CREATED_AT" => "2021/02/22 15:30"
            ],
            [
                "NOTIFICATION_ID" => 149771,
                "OBJECT_NAME" => "Device/Sensor Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Dummy Dust was offline.",
                "CREATED_AT" => "2021/02/22 15:30"
            ],
            [
                "NOTIFICATION_ID" => 149769,
                "OBJECT_NAME" => "Device Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Switch 3 was triggered.",
                "CREATED_AT" => "2021/02/22 15:21"
            ],
            [
                "NOTIFICATION_ID" => 149768,
                "OBJECT_NAME" => "Device Detected",
                "ROOM_ID" => 1,
                "SUBJECT" => "Switch 3 was triggered.",
                "CREATED_AT" => "2021/02/22 15:19"
            ]
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
