<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getAuthFloorTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "getAuthFloor";
    /**
     * 取得データの検証
     */
    public function test_getAuthFloorTest()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        //pass
        $response = $this->post(
            $this->uri,
            [
                'USERID' => '1',
            ]
        );

        // 期待値
        $this->assertTrue(is_object($response->original));
        //exists
        $response = $this->post(
            $this->uri,
            [
                'USERID' => '39',
            ]
        );
        $expected = 'no assigned floors';
        $this->assertEquals($expected, $response->original);
    }
}
