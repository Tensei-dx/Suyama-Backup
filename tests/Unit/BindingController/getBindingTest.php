<?php

namespace Tests\Unit;

use App\Http\Controllers\BindingController;
use Tests\TestCase;

class BindingController_getBinding extends TestCase
{
    /**
     *@var BindingController      
     */
    protected $object;

    /**     
     * setUpは各テストメソッドが実行される前に実行する       
     */
    protected function setUp(): void
    {
        parent::setUp();
        // テストするオブジェクトを生成する     
        $this->object = new BindingController();
    }
    public function test_getBinding()
    {

        // Requestクラスのモックを作成        
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $expected = '{"BINDING_ID":93,"SOURCE_DEVICE_ID":213213225,"TARGET_DEVICE_ID":213213219,"BINDING_LIST_ID":116,"SOURCE_DEVICE_CONDITION":[],"CUSTOM_CONDITION":{"command":"status","operator":"TEMP_24"},"TIME_INTERVAL":60,"BINDING_STATUS":1,"MANUAL":0,"CREATED_AT":"2021-03-20 14:29:46","UPDATED_AT":"2021-03-23 11:14:32","LAST_ACTIVITY":"2021-03-20 13:29:46","NEXT_ACTIVITY":"2021-03-23 11:14:32"}';

        $id = '93';
        $binding = $this->object->getBinding($request, $id);
        $this->assertEquals($expected, json_encode($binding));
    }
}
