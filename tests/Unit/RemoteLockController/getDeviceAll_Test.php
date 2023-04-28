<?php

namespace Tests\Unit;

use App\Http\Controllers\DeviceController;
use Tests\TestCase;

class DeviceController_getDeviceAll extends TestCase
{
    /**
     *@var DeviceController
     */
    protected $object;

    /**
     * setUpは各テストメソッドが実行される前に実行する
     */
    protected function setUp(): void
    {
        parent::setUp();
        // テストするオブジェクトを生成する
        $this->object = new DeviceController();
    }
    public function test_getDeviceAll()
    {

        // Requestクラスのモックを作成
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $expected = '[{"DEVICE_ID":2207,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":30,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"5BD0C312004B1200","DEVICE_TYPE":"panic_button","DEVICE_CATEGORY":1,"DATA":{"status":1},"DEVICE_NAME":"Test Panic","DEVICE_MAP_NAME":null,"REG_FLAG":1,"ONLINE_FLAG":0,"CREATED_AT":"2020-01-21 08:25:17","UPDATED_AT":"2020-05-05 09:38:53"},{"DEVICE_ID":2215,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":30,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"49385316004B1200","DEVICE_TYPE":"door_lock","DEVICE_CATEGORY":1,"DATA":null,"DEVICE_NAME":"panic_button","DEVICE_MAP_NAME":null,"REG_FLAG":1,"ONLINE_FLAG":1,"CREATED_AT":"2020-01-23 07:46:55","UPDATED_AT":"2020-06-03 12:06:00"},{"DEVICE_ID":2220,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":30,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"4A09C212004B1200","DEVICE_TYPE":"ir_remote","DEVICE_CATEGORY":0,"DATA":[],"DEVICE_NAME":"new test_device","DEVICE_MAP_NAME":null,"REG_FLAG":1,"ONLINE_FLAG":1,"CREATED_AT":"2020-01-23 08:43:26","UPDATED_AT":"2020-06-03 12:18:07"},{"DEVICE_ID":2221,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":30,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"8853C112004B1200","DEVICE_TYPE":"wall_switch_2","DEVICE_CATEGORY":0,"DATA":[{"status":"0","device_name":"SE"},{"status":"0","device_name":"SR"}],"DEVICE_NAME":"Wall Switch Test 2","DEVICE_MAP_NAME":null,"REG_FLAG":0,"ONLINE_FLAG":1,"CREATED_AT":"2020-01-24 10:40:55","UPDATED_AT":"2020-06-03 12:45:46"},{"DEVICE_ID":2224,"FLOOR_ID":1,"ROOM_ID":1,"GATEWAY_ID":13051,"MANUFACTURER_ID":1,"DEVICE_SERIAL_NO":"612DD814004B1200","DEVICE_TYPE":"h2o_detector","DEVICE_CATEGORY":0,"DATA":{"status":0},"DEVICE_NAME":"test2","DEVICE_MAP_NAME":null,"REG_FLAG":1,"ONLINE_FLAG":1,"CREATED_AT":"2020-04-04 02:13:18","UPDATED_AT":"2020-06-01 14:55:04"}]';
        $device = $this->object->getDeviceAll($request);
        $device = json_encode($device);
        $this->assertEquals($expected, $device);
    }
}
