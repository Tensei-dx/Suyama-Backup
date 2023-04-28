<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoginController_validateLogin extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "validateLogin";

    /**
     * 取得データの検証
     */

    // Valid User
    public function test_validateLogin()
    {
        $credential = [
            'username' => 'admin',
            'password' => '123123123',

        ];
        $this->post('login', $credential)->assertRedirect('/');
    }

    // Wrong password
    public function test_validateLogin2()
    {
        $credential = [
            'username' => 'admin',
            'password' => 'wrongpass',
        ];
        $this->post('login', $credential)->assertStatus(401);
    }

    // Disabled User
    public function test_validateLogin3()
    {
        $credential = [
            'username' => 'janitor',
            'password' => '123123123',
        ];
        $this->post('login', $credential)->assertStatus(401);
    }

    // Wrong credentials
    public function test_validateLogin4()
    {
        $credential = [
            'username' => 'unknownuser',
            'password' => 'As123123',
        ];
        $this->post('login', $credential)->assertStatus(401);
    }
}
