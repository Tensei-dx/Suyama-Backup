<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class deleteIrRecordTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "deleteIrRecord";

    /**     
     * IrLearningControllerMethod validation      
     * deleteIrRecordメソッドの検証       
     */
    public function test_deleteIrRecord()
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
        // dynamic propertyの設定
        $body = array(
            'DEVICE_ID' => 213213237,
            'APPLIANCE_ID' => 88,
            'OPERATION_ID' => 3,
            'LEARNING_VALUE' => 7
        );
        // Actual Result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        // $response->assertExactJson($expected); 
    }
}
