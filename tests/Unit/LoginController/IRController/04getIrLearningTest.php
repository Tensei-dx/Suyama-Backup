<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class getIrLearningTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getIrLearning";

    /**
     * 取得データの検証
     */
    public function test_getIrLearning()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        //----------------------------------------------------------------------		
        // CASE1：In case of branching of if statement / if分岐の場合		
        //----------------------------------------------------------------------		
        // 期待値
        // $expected = '[{"IR_LEARNING_LIST_ID":142,"DEVICE_ID":213213237,"APPLIANCE_ID":32,"OPERATION_ID":7,"LEARNING_VALUE":25,"CREATED_AT":"2021-03-27 15:25:34","UPDATED_AT":"2021-03-27 15:25:34","appliances":{"APPLIANCE_ID":32,"APPLIANCE_NAME":"Aircon-01","APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","CREATED_AT":"2019-07-31 10:07:57","UPDATED_AT":"2019-07-31 10:07:57"},"operation":{"OPERATION_ID":7,"OPERATION_NAME":"TEMP_18","CREATED_AT":"2019-02-27 08:46:51","UPDATED_AT":"2019-02-27 08:46:51"},"device":{"DEVICE_ID":213213237,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":78,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"BF6A6A0F004B1200","DEVICE_TYPE":"ir_remote","DEVICE_CATEGORY":0,"DATA":[{"type":"AC","brand":"Panasonic","status":"0","temp_value":"25","aircon_power":true},{"type":"AC2","brand":"Samsung","status":"0","temp_value":"25","aircon_power":true},{"type":"AC","brand":"BIONAIRE","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Devant","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Samsung","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Phillips","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Relx","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Lenovo","status":"0","temp_value":"25","aircon_power":true},{"type":"NOPQRSTUVWXYZ","brand":"SAMSUNGLENOVO","status":"0","temp_value":"25","aircon_power":true}],"DEVICE_NAME":"IR Remote","DEVICE_MAP_NAME":"ejz841qtfh","EMERGENCY_DEVICE":0,"REG_FLAG":1,"ONLINE_FLAG":0,"CREATED_AT":"2021-03-27 11:42:35","UPDATED_AT":"2021-04-04 02:50:04"}}]';

        $expected = '[{"OPERATION_NAME":"TEMP_16","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":1,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":22},{"OPERATION_NAME":"TEMP_27","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":16,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":53},{"OPERATION_NAME":"TEMP_22","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":11,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":19},{"OPERATION_NAME":"AC_POWER_ON","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Aircon-01","APPLIANCE_ID":32,"OPERATION_ID":4,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":20},{"OPERATION_NAME":"AC_POWER_OFF","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Aircon-01","APPLIANCE_ID":32,"OPERATION_ID":5,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":17},{"OPERATION_NAME":"TEMP_25","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":14,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":14},{"OPERATION_NAME":"TEMP_26","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":15,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":10},{"OPERATION_NAME":"TEMP_24","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":13,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":21},{"OPERATION_NAME":"TEMP_23","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":12,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":28},{"OPERATION_NAME":"TEMP_17","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":6,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":18},{"OPERATION_NAME":"TEMP_18","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":7,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":23},{"OPERATION_NAME":"TEMP_19","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":8,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":26},{"OPERATION_NAME":"TEMP_20","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":9,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":24},{"OPERATION_NAME":"TEMP_21","DEVICE_ID":10,"DEVICE_NAME":"Dummy Panic Button","APPLIANCE_NAME":"Panasonic","APPLIANCE_ID":57,"OPERATION_ID":10,"APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","LEARNING_VALUE":30}]';

        //        $request->FLOOR_ID = '1';      // dynamic propertyの設定
        //       $request->ROOM_ID = '1';      // dynamic propertyの設定

        $body = [
            'FLOOR_ID' => '1',
            'ROOM_ID' => '1'
        ];
        // Actual response
        $actual = $this->json($this->method, $this->uri, $body);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);

        //----------------------------------------------------------------------       
        // CASE2：In case of branching of else-if statement / else-if分岐の場合     
        //----------------------------------------------------------------------        
        // 期待値
        /*      $expected = '[{"IR_LEARNING_LIST_ID":142,"DEVICE_ID":213213237,"APPLIANCE_ID":32,"OPERATION_ID":7,"LEARNING_VALUE":25,"CREATED_AT":"2021-03-27 15:25:34","UPDATED_AT":"2021-03-27 15:25:34","appliances":{"APPLIANCE_ID":32,"APPLIANCE_NAME":"Aircon-01","APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","CREATED_AT":"2019-07-31 10:07:57","UPDATED_AT":"2019-07-31 10:07:57"},"operation":{"OPERATION_ID":7,"OPERATION_NAME":"TEMP_18","CREATED_AT":"2019-02-27 08:46:51","UPDATED_AT":"2019-02-27 08:46:51"},"device":{"DEVICE_ID":213213237,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":78,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"BF6A6A0F004B1200","DEVICE_TYPE":"ir_remote","DEVICE_CATEGORY":0,"DATA":[{"type":"AC","brand":"Panasonic","status":"0","temp_value":"25","aircon_power":true},{"type":"AC2","brand":"Samsung","status":"0","temp_value":"25","aircon_power":true},{"type":"AC","brand":"BIONAIRE","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Devant","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Samsung","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Phillips","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Relx","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Lenovo","status":"0","temp_value":"25","aircon_power":true},{"type":"NOPQRSTUVWXYZ","brand":"SAMSUNGLENOVO","status":"0","temp_value":"25","aircon_power":true}],"DEVICE_NAME":"IR Remote","DEVICE_MAP_NAME":"ejz841qtfh","EMERGENCY_DEVICE":0,"REG_FLAG":1,"ONLINE_FLAG":0,"CREATED_AT":"2021-03-27 11:42:35","UPDATED_AT":"2021-04-04 02:50:04"}}]';

        $request->FLOOR_ID = '1';      // dynamic propertyの設定
        $request->ROOM_ID = null;      // dynamic propertyの設定
        
                                        // getUserメソッドからの返り値を格納     
        $irLearning = $this->object->getIrLearning($request);
        
                                        // getUserからの返り値が正しいか検証する       
        $this->assertEquals($expected, $irLearning);

        //----------------------------------------------------------------------        
        // CASE3：In case of branching of else statement / else分岐の場合     
        //----------------------------------------------------------------------        
                                        // 期待値
        $expected = '[{"IR_LEARNING_LIST_ID":142,"DEVICE_ID":213213237,"APPLIANCE_ID":32,"OPERATION_ID":7,"LEARNING_VALUE":25,"CREATED_AT":"2021-03-27 15:25:34","UPDATED_AT":"2021-03-27 15:25:34","appliances":{"APPLIANCE_ID":32,"APPLIANCE_NAME":"Aircon-01","APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","CREATED_AT":"2019-07-31 10:07:57","UPDATED_AT":"2019-07-31 10:07:57"},"operation":{"OPERATION_ID":7,"OPERATION_NAME":"TEMP_18","CREATED_AT":"2019-02-27 08:46:51","UPDATED_AT":"2019-02-27 08:46:51"},"device":{"DEVICE_ID":213213237,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":78,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"BF6A6A0F004B1200","DEVICE_TYPE":"ir_remote","DEVICE_CATEGORY":0,"DATA":[{"type":"AC","brand":"Panasonic","status":"0","temp_value":"25","aircon_power":true},{"type":"AC2","brand":"Samsung","status":"0","temp_value":"25","aircon_power":true},{"type":"AC","brand":"BIONAIRE","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Devant","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Samsung","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Phillips","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Relx","status":"0","temp_value":"25","aircon_power":true},{"type":"TV","brand":"Lenovo","status":"0","temp_value":"25","aircon_power":true},{"type":"NOPQRSTUVWXYZ","brand":"SAMSUNGLENOVO","status":"0","temp_value":"25","aircon_power":true}],"DEVICE_NAME":"IR Remote","DEVICE_MAP_NAME":"ejz841qtfh","EMERGENCY_DEVICE":0,"REG_FLAG":1,"ONLINE_FLAG":0,"CREATED_AT":"2021-03-27 11:42:35","UPDATED_AT":"2021-04-04 02:50:04"}}]';

        $request->FLOOR_ID = null;      // dynamic propertyの設定
        $request->ROOM_ID = null;      // dynamic propertyの設定
        
                                        // getUserメソッドからの返り値を格納     
        $irLearning = $this->object->getIrLearning($request);
        
                                        // getUserからの返り値が正しいか検証する       
        $this->assertEquals($expected, $irLearning);*/
    }
}
