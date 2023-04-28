<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class updateFloorTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "updateFloorTest";

    /**
     * 取得データの検証
     */
    public function test_updateFloorTest()
    {
        // Requestクラスのモックを作成
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            'FLOOR_ID' => '101',
            'floorName' => '101st'
        ];

        $actual = $this->json($this->method, $this->uri, $body);
        $actual->assertStatus(200);
    }
}
