<?php

use App\Http\Controllers\DashboardController;
use Tests\TestCase;

class getGateway extends TestCase
{
    /**
     * @var DashboardController
     */
    protected $object;
    /**
     * setUpは各テストメソッドが実行される前に実行する
     */
    protected function setUp(): void
    {
        parent::setUp();
        // テストするオブジェクトを生成する
        $this->object = new DashboardController();
    }
    /**
     * 取得データの検証
     */
    public function test_getGateway()
    {
        // Requestクラスのモックを作成
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        // 期待値
        $expected = array(
            [
                'status' => 'Online Gateway/s',
                'count' => 2,
                'color' => '#28a745'
            ],
            [
                'status' => 'Offline Gateway/s',
                'count' => 0,
                'color' => '#6c757d'
            ]
        );
        $id = 1;
        $gateway = $this->object->getGateway($request);
        $this->assertEquals($gateway, $expected);
    }
}
