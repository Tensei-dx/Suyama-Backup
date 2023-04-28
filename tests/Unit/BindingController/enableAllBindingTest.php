<?php

namespace Tests\Unit;

use App\Http\Controllers\BindingController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BindingController_enableAllBinding extends TestCase
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
    public function test_enableAllBinding()
    {
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);
        $request->TYPE;

        $response = $this->post(
            'enableAllBinding',
            [

                'BINDING' => '1',

            ]
        );
        $expected = 'success';
        $this->assertEquals($expected, $response->original);
    }
}
