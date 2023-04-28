<?php

namespace Tests\Unit\Log;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class createAuditLogsTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "createAuditLogs";

    /**
     * 取得データの検証
     */
    public function test_createAuditLogsTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $body = [

            'module' => '-',
            'function' => '-',
            'INSTRUCTION' => '-'
        ];

        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);

        // Data for Array Value
        $body = [

            'module' => '-',
            'function' => '-',
            'INSTRUCTION' => array('')
        ];

        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
    }
}
