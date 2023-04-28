<?php

/**
 * <System Name> iBMS
 * <Program Name> getUser_Test.php
 *
 * Create  => 2021.03.02 TP Uddin
 * Update  =>
 */

namespace Tests\Unit\UserController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> UserController_getUser
 *
 * <Overview> Class that is used to perform PHPUnit test
 *            for getUser method in UserController
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class UserController_getUser extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "getUser";

    /**
     * Success Case
     */
    public function test_getUser()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'USER_ID' => 1
        ];
        // Expected response
        $expected = [
            "USER_ID" => 1,
            "LAST_NAME" => "Tensei",
            "FIRST_NAME" => "Tensei Taro",
            "USERNAME" => "admin",
            "EMAIL" => "tensei_admin@tp.coms",
            "CONTACT_NUMBER" => "1231231",
            "ALLOW_ALERT_NOTIFICATION" => [
                "sms" => false,
                "email" => true,
                "voice" => true
            ],
            "USER_TYPE" => 1,
            "REG_FLAG" => 1,
            "USER_LOGO" => "/storage/app/public/users/Go-Tensei-Inc(1000).png",
            "CREATED_AT" => "2018-08-08 13:55:13",
            "UPDATED_AT" => "2021-02-15 10:53:37",
            "auth_user_floor" => [
                [
                    "LOCATION_ID" => 1686,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 1,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1687,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 2,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1688,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 3,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1689,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 4,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1690,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 6,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1691,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 39,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1692,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 51,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ],
                [
                    "LOCATION_ID" => 1693,
                    "USER_ID" => 1,
                    "FLOOR_ID" => 53,
                    "CREATED_AT" => "2019-09-13T06:27:31.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:31.000000Z"
                ]
            ],
            "auth_modules" => [
                [
                    "AUTH_MODULE_ID" => 1164,
                    "USER_ID" => 1,
                    "MODULE_ID" => 9,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ],
                [
                    "AUTH_MODULE_ID" => 1165,
                    "USER_ID" => 1,
                    "MODULE_ID" => 7,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ],
                [
                    "AUTH_MODULE_ID" => 1166,
                    "USER_ID" => 1,
                    "MODULE_ID" => 6,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ],
                [
                    "AUTH_MODULE_ID" => 1167,
                    "USER_ID" => 1,
                    "MODULE_ID" => 5,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ],
                [
                    "AUTH_MODULE_ID" => 1168,
                    "USER_ID" => 1,
                    "MODULE_ID" => 4,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ],
                [
                    "AUTH_MODULE_ID" => 1169,
                    "USER_ID" => 1,
                    "MODULE_ID" => 8,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ],
                [
                    "AUTH_MODULE_ID" => 1170,
                    "USER_ID" => 1,
                    "MODULE_ID" => 3,
                    "CREATED_AT" => "2019-09-13T06:27:32.000000Z",
                    "UPDATED_AT" => "2019-09-13T06:27:32.000000Z"
                ]
            ]
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        // Test response
        $actual->assertExactJson($expected);
    }

    /**
     * Error Case
     */
    public function test_getUser_error()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'USER_ID' => 9999
        ];
        // Expected response
        $expected = "No query results for model [App\User] 9999";
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        // Test response
        $actual->assertSeeText($expected);
    }
}
