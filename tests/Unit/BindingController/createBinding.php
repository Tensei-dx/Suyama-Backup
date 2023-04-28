<?php

namespace Tests\Unit;

use App\Http\Controllers\BindingController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BindingController_createBinding extends TestCase
{
    use AuthenticatesUsers;
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
    public function test_createBinding()
    {
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);

        //new binding
        $request->TARGET_DEVICES;
        $addList = [];
        $removeList = [];
        $addStr = '[{"TARGET_DEVICE_ID":2237,"CUSTOM_CONDITION":{"command": "status", "operator":"TEMP_18"},"SOURCE_DEVICE_CONDITION":{"data": 16, "operator": "MAX"},"SOURCE_DEVICE_ID":2230,"BINDING_LIST_ID":0,"TIME_INTERVAL":600}]';

        $removeStr = '[{"TARGET_DEVICE_ID":2237,"SOURCE_DEVICE_ID":2241,"BINDING_LIST_ID":0}]';

        $removeList = json_decode($removeStr, true);

        $addList = json_decode($addStr, true);


        $response = $this->post(
            'createBinding',
            [
                'TARGET_DEVICES' => $addList,
                'REMOVE_LIST' => $removeList,
            ]
        );
        $response->assertStatus(200);

        //existing
        $request->TARGET_DEVICES;
        $addList = [];
        $removeList = [];
        $addStr = '[{"TARGET_DEVICE_ID":2237,"CUSTOM_CONDITION":{"command": "status", "operator":"TEMP_20"},"SOURCE_DEVICE_CONDITION":{"data": 17, "operator": "MAX"},"SOURCE_DEVICE_ID":2230,"BINDING_LIST_ID":0,"TIME_INTERVAL":600}]';

        $addList = json_decode($addStr, true);

        $response = $this->post(
            'createBinding',
            [
                'TARGET_DEVICES' => $addList,
                'REMOVE_LIST' => $removeList,
            ]
        );
        $response->assertStatus(200);
    }
}
