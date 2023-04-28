<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class newApplianceTest extends TestCase
{
  /** @var string $username */
  private $username = "admin";

  /** @var string $password */
  private $password = "123123123";

  /** @var string $method */
  private $method = "POST";

  /** @var string $uri */
  private $uri = "createAppliance";

  /**
   * 取得データの検証
   */
  public function test_newAppliance()
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

    $appliance_list = array(
      'APPLIANCE_NAME' => 'Room Aircon',
      'APPLIANCE_TYPE' => 'AC',
      'BRAND_NAME' => 'LG',
    );

    // dynamic propertyの設定
    $body = array(
      'APPLIANCE_LIST' => $appliance_list
    );

    // Actual Result
    $response = $this->json($this->method, $this->uri, $body);
    $response->assertStatus(200);

    //----------------------------------------------------------------------        
    // CASE2：In case of branching of else if statement / else if分岐の場合     
    //----------------------------------------------------------------------        
    // 期待値
    $expected = 'existing';

    // dynamic propertyの設定
    $appliance_list = array(
      'APPLIANCE_NAME' => 'Room Aircon',
      'APPLIANCE_TYPE' => 'AC',
      'BRAND_NAME' => 'LG',
    );

    // dynamic propertyの設定
    $body = array(
      'APPLIANCE_LIST' => $appliance_list
    );

    // Actual Result
    $response = $this->json($this->method, $this->uri, $body);
    $response->assertStatus(200);
  }
}
