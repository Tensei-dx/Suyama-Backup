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
    private $uri = "getIrLearning";

    /**     
     * IrLearningControllerMethod validation      
     * NewUserメソッドの検証       
     */
    public function test_newIrLearning()
    {
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);

        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        //----------------------------------------------------------------------        
        // CASE1：In case of branching of if statement / if分岐の場合     
        //----------------------------------------------------------------------        
        // 期待値
        $expected = 'success';

        $request->LEARNING_LIST = array(
            'APPLIANCE_ID' => 35,
            'OPERATION_ID' => 14,
            'LEARNING_VALUE' => 0,
            'DEVICE_TYPE' => 'ir_remote',
            'DEVICE_ID' => 99
        );
        // dynamic propertyの設定

        // getUserメソッドからの返り値を格納     
        $irLearning = $this->object->newIrLearning($request);

        // getUserからの返り値が正しいか検証する       
        $this->assertEquals($expected, $irLearning);

        //----------------------------------------------------------------------        
        // CASE2：In case of branching of if statement / if分岐の場合     
        //----------------------------------------------------------------------        
        // 期待値
        $expected = 'existing';

        $request->LEARNING_LIST = array(
            'APPLIANCE_ID' => 28,
            'OPERATION_ID' => 3,
            'LEARNING_VALUE' => 0,
            'DEVICE_TYPE' => 'ir_remote',
            'DEVICE_ID' => 66
        );
        // dynamic propertyの設定

        // getUserメソッドからの返り値を格納     
        $irLearning = $this->object->newIrLearning($request);

        // getUserからの返り値が正しいか検証する       
        $this->assertEquals($expected, $irLearning);
    }
}
