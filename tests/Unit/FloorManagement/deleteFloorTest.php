<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FloorController_deleteFloor extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "deleteFloor";
    /**
     * 取得データの検証
     */
    public function test_deleteFloorTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $body =  [
            'FLOOR_ID' => '70'
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
    }
}
