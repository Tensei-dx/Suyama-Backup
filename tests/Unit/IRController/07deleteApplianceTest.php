<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class deleteApplianceTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "deleteAppliance";

    /**
     * 取得データの検証
     */
    public function test_deleteAppliance()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        //----------------------------------------------------------------------        
        // CASE1：In case of branching of if statement / if分岐の場合     
        //----------------------------------------------------------------------  
        $expected = 'success';

        $appliance_list = array(
            'APPLIANCE_NAME' => 'Wall TV',
            'APPLIANCE_TYPE' => 'TV',
            'BRAND_NAME' => 'Samsung',
        );

        $body = array(
            'APPLIANCE_LIST' => $appliance_list
        );

        // Actual Result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
    }
}
