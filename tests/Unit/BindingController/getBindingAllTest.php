<?php

namespace Tests\Unit;

use App\Http\Controllers\BindingController;
use Tests\TestCase;

class BindingController_getBindingAll extends TestCase
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
    public function test_getBindingAll()
    {

        // Requestクラスのモックを作成        
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $expected = '{"213213225":[{"BINDING_ID":93,"SOURCE_DEVICE_ID":213213225,"TARGET_DEVICE_ID":213213219,"BINDING_LIST_ID":116,"SOURCE_DEVICE_CONDITION":[],"CUSTOM_CONDITION":{"command":"status","operator":"TEMP_24"},"TIME_INTERVAL":60,"BINDING_STATUS":1,"MANUAL":0,"CREATED_AT":"2021-03-20 14:29:46","UPDATED_AT":"2021-03-23 11:14:32","LAST_ACTIVITY":"2021-03-20 13:29:46","NEXT_ACTIVITY":"2021-03-23 11:14:32","source_device":{"DEVICE_ID":213213225,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":3,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"CA1FB805004B1200","DEVICE_TYPE":"temp_hum","DEVICE_CATEGORY":1,"DATA":{"hum":60.5,"temp":29.1},"DEVICE_NAME":"temp hum","DEVICE_MAP_NAME":null,"EMERGENCY_DEVICE":0,"REG_FLAG":1,"ONLINE_FLAG":1,"CREATED_AT":"2021-02-09 10:18:09","UPDATED_AT":"2021-03-20 08:02:51"}}],"":[{"BINDING_ID":95,"SOURCE_DEVICE_ID":2230,"TARGET_DEVICE_ID":2237,"BINDING_LIST_ID":0,"SOURCE_DEVICE_CONDITION":{"data":17,"operator":"MAX"},"CUSTOM_CONDITION":{"command":"status","operator":"TEMP_20"},"TIME_INTERVAL":36000,"BINDING_STATUS":1,"MANUAL":0,"CREATED_AT":"2021-03-23 09:52:11","UPDATED_AT":"2021-03-23 09:53:54","LAST_ACTIVITY":"2021-03-23 08:52:11","NEXT_ACTIVITY":"2021-03-23 08:52:11","source_device":null}]}';

        $binding = $this->object->getBindingAll($request);
        $this->assertEquals($expected, json_encode($binding));
    }
}
