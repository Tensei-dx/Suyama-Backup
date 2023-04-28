<?php

/**
 * <System Name> iBMS
 * <Program Name> getUsers_Test.php
 *
 * Create : 2021.03.02 TP Uddin<br>
 * Update :
 */

namespace Tests\Unit\UserController;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> UserController_createUser
 *
 * <Overview> Class that is used to perform PHPUnit test
 *            to createUser method in UserController
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class UserController_createUser extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "createUser";

    /**
     * Success Case
     */
    public function test_createUser_success()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'file' => UploadedFile::fake()->image('fake-image.png'),
            'fileName' => 'fake-image.png',
            'USERNAME' => 'testing_fake_username',
            'PASSWORD' => 'Test123',
            'USER_TYPE' => 1,
            'CONTACT_NUMBER' => '09123456789',
            'ALLOW_ALERT_NOTIFICATION' => json_encode([
                'email' => true,
                'sms' => true,
                'voice' => true
            ]),
            'EMAIL' => 'testing.email@testing.email',
            'hasFile' => 1,
            'hasModule' => 1,
            'floorids' => '1,2,3',
            'moduleids' => '1,2,3',
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }

    /**
     * Duplicate Case
     */
    public function test_createUser_duplicate()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'file' => null,
            'fileName' => null,
            'USERNAME' => 'admin',
            'PASSWORD' => '123123123',
            'USER_TYPE' => 1,
            'CONTACT_NUMBER' => '09123456789',
            'ALLOW_ALERT_NOTIFICATION' => json_encode([
                'email' => false,
                'sms' => false,
                'voice' => false
            ]),
            'EMAIL' => 'testing.email@testing.email',
            'hasFile' => 0,
            'hasModule' => 0,
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
