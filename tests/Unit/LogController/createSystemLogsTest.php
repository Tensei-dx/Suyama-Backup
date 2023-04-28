<?php

namespace Tests\Unit\Log;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class createSystemLogsTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "createSystemLogs";

    /**
     * 取得データの検証
     */
    public function test_createSystemLogsTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $body = [

            'ERROR_MESSAGE' => 'System Error'
        ];

        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
    }
}
