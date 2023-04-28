<?php

/**
 * <System Name> iBMS
 * <Program Name> updateUserDesignation_Test.php
 *
 * Create : 2021.03.08 TP Uddin<br>
 * Update :
 */

namespace Tests\Unit\UserController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> UserController_updateUserDesignation
 *
 * <Overview> Class that is used to perform PHPUnit test
 *            to updateUserDesignation method in UserController
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class UserController_updateUserDesignation extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "updateUserDesignation";

    /**
     * Success Case
     */
    public function test_updateUserDesignation()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'USERID' => 38,
            'FLOORS' => [
                [
                    'FLOOR_ID' => 1
                ],
                [
                    'FLOOR_ID' => 2
                ]
            ],
            'MODULES' => [
                [
                    'MODULE_ID' => 1
                ],
                [
                    'MODULE_ID' => 2
                ],
                [
                    'MODULE_ID' => 3
                ],
                [
                    'MODULE_ID' => 4
                ]
            ]
        ];
        // Expected response
        $expected = "success";
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        // Test response
        $actual->assertSeeText($expected);
    }
}
