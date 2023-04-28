<?php

/**
 * <System Name> iBMS
 * <Program Name> updateUserProfile_Test.php
 *
 * Create : 2021.03.02 TP Uddin<br>
 * Update :
 */

namespace Tests\Unit\UserController;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> UserController_updateUserProfile
 *
 * <Overview> Class that is used to perform PHPUnit test
 *            to updateUserProfile method in UserController
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class UserController_updateUserProfile extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "updateUserProfile";

    /**
     * Success Case
     */
    public function test_updateUserProfile()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Request body
        $body = [
            'file' => UploadedFile::fake()->image('fake-image.png'),
            'fileName' => 'fake-image.png',
            'USERID' => 38,
            'USERNAME' => 'testAdmin',
            'USEREMAIL' => 'r-russell@tenseiph.com',
            'USERCONTACT' => '09123456789',
            'USERALERT' => json_encode([
                'sms' => false,
                'email' => false,
                'voice' => false
            ]),
            'hasFile' => 1
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
