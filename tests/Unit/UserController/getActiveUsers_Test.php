<?php

/**
 * <System Name> iBMS
 * <Program Name> getUsers_Test.php
 *
 * Create : 2021.03.02 TP Uddin<br>
 * Update :
 */

namespace Tests\Unit\UserController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> UserController_getActiveUsers
 *
 * <Overview> Class that is used to perform PHPUnit test
 *            to getActiveUsers method in UserController
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class UserController_getActiveUsers extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "getActiveUsers";

    /**
     * Success Case 1
     * USER_TYPE = 1
     */
    public function test_getActiveUsers_1()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'USER_TYPE' => 1
        ];
        // Expected response
        /* Response is too big to test */

        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }

    /**
     * Success Case 2
     * USER_TYPE = 2
     */
    public function test_getActiveUsers_2()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'USER_TYPE' => 2
        ];
        // Expected response
        /* Response is too big to test */

        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
