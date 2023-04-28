<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class newIrLearningTest extends TestCase
{
  /** @var string $username */
  private $username = "admin";

  /** @var string $password */
  private $password = "123123123";

  /** @var string $method */
  private $method = "POST";

  /** @var string $uri */
  private $uri = "createIrLearning";

  /**     
   * IrLearningControllerMethod validation      
   * NewUserメソッドの検証       
   */
  public function test_newIrLearning()
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

    $learning_list = array(
      'APPLIANCE_ID' => 88,
      'OPERATION_ID' => 3,
      'LEARNING_VALUE' => 97,
      'DEVICE_TYPE' => 'ir_remote',
      'DEVICE_ID' => 213213237,
      'LEARNING_LIST' => 10,
    );

    $body = array(
      'LEARNING_LIST' => $learning_list
    );

    // Actual Result
    $response = $this->json($this->method, $this->uri, $body);
    $response->assertStatus(200);
    // $response->assertExactJson($expected); 

    //----------------------------------------------------------------------        
    // CASE2：In case of branching of if statement / if分岐の場合     
    //----------------------------------------------------------------------        
    // 期待値
    $expected = 'existing';


    $learning_list = array(
      'APPLIANCE_ID' => 88,
      'OPERATION_ID' => 3,
      'LEARNING_VALUE' => 97,
      'DEVICE_TYPE' => 'ir_remote',
      'DEVICE_ID' => 213213237,
      'LEARNING_LIST' => 10,
    );

    $body = array(
      'LEARNING_LIST' => $learning_list
    );
    // Actual Result
    $response = $this->json($this->method, $this->uri, $body);
    $response->assertStatus(200);
  }
}
