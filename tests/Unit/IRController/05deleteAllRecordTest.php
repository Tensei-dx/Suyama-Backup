<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class deleteAllRecordTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "deleteAllRecord";

    /**
     * 取得データの検証
     */
    public function test_deleteAllRecord()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        //----------------------------------------------------------------------		
        // CASE1：In case of branching of if statement / if分岐の場合		
        //----------------------------------------------------------------------		
        // 期待値
        $expected = 'success';

        $body = [
            'DEVICE_ID' => '213213237'
        ];      // dynamic propertyの設定

        // Actual Result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
    }
}
