<?php

namespace Tests\Unit\Log;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getSystemLogsTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "getSystemLogs";

    /**
     * 取得データの検証
     */
    public function test_getSystemLogsTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $body = [
            'START_DATE' => '2021-03-20',
            'END_DATE' => '2021-03-20'
        ];

        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
    }
}
