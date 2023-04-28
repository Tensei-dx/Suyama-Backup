<?php

namespace Tests\Unit\Log;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class updateNotificationTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri;

    /**
     * 取得データの検証
     */
    public function test_updateNotificationTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $id = 1;
        //Query
        $this->uri = "api/updateNotification";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        // Expected result
        $expected = [];

        // Actual Result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
    }
}
