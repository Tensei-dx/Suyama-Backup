<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\ClientController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 *
 */
class ClientController_createCheckinNotificationTest extends TestCase
{
    use AuthenticatesUsers;

    /**
     * @var ClientController
     */
    protected $object;

    /**
     * setUp is executed before each test method is executed
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create an object to test
        $this->object = new ClientController();
    }

    /**
     * Verification of the acquired data
     */
    public function test_createCheckinNotificationTest()
    {
        Auth::attempt(['username' => 'guest', 'password' => '123123123']);
        // $request = $this->getMockBuilder('Illuminate\Http\Request')
        //     ->disableOriginalConstructor()
        //     ->getMock();
        // $request->DEVICE_TYPE = 'remote_lock';
        // $request->REG_FLAG = 1;
        // $expected = [];
        $sample = [
            'USER_ID' => 2,
            'STATUS_ID' => 205,
            'USER_TYPE' => 2,
            'FIRST_NAME' => 'Guest',
            'LAST_NAME' => 'Tensei',
        ];
        $notif = $this->object->createCheckinNotificationTest($request);
        $this->assertEquals(json_decode($notif, true), undefined);
    }
}
