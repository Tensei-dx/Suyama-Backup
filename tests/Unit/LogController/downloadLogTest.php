<?php

namespace Tests\Unit\Log;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class downloadLogTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "downloadLog";

    /**
     * 取得データの検証
     */
    public function test_downloadLogTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $body = [

            'log.' => 'in',
            'filetype.' => 'format',
            'INSTRUCTION' => '-'
        ];

        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
    }
}
