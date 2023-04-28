<?php

/**
 * <System Name> iBMS
 * <Program Name> disableUser_Test.php
 *
 * Create : 2021.03.02 TP Uddin<br>
 * Update :
 */

namespace Tests\Unit\UserController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> UserController_disableUser
 *
 * <Overview> Class that is used to perform PHPUnit test
 *            to disableUser method in UserController
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class UserController_disableUser extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "disableUser";

    /**
     * Success Case 1
     * USER_TYPE = 1
     */
    public function test_disableUser()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'USERID' => 38
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
