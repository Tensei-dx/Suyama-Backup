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

        // First Check-in Attempt
        $guest = new \stdClass();
        // guest's user id
        $guest->USER_ID = 4;
        // guest's user type
        $guest->USER_TYPE = 2;
        // guest's room status
        $guest->STATUS_ID = 205;
        // guest's first name
        $guest->FIRST_NAME = 'GUEST';
        // guest's last name
        $guest->LAST_NAME = 'TENSEI';
        // website preferred language
        session()->put('locale', 'en');

        $notif = $this->object->createCheckinNotification($guest);
        $this->assertEquals(json_decode($notif, true), null);
    }

    /**
     * Verification of the acquired data
     */
    public function test_createCheckinNotificationTest1()
    {
        Auth::attempt(['username' => 'guest', 'password' => '123123123']);

        // First Check-in Attempt
        $guest = new \stdClass();
        // guest's user id
        $guest->USER_ID = 3;
        // guest's user type
        $guest->USER_TYPE = 2;
        // guest's room status
        $guest->STATUS_ID = 205;
        // guest's first name
        $guest->FIRST_NAME = 'GUEST';
        // guest's last name
        $guest->LAST_NAME = 'TENSEI';
        // website preferred language
        session(['locale' => 'ja']);

        $notif = $this->object->createCheckinNotification($guest);
        $this->assertEquals(json_decode($notif, true), null);
    }

    /**
     * Verification of the acquired data
     */
    public function test_createCheckinNotificationTest2()
    {
        Auth::attempt(['username' => 'guest', 'password' => '123123123']);

        // ReCheck-in Attempt
        $guest = new \stdClass();
        // guest's user id
        $guest->USER_ID = 4;
        // guest's user type
        $guest->USER_TYPE = 1;
        // guest's room status
        $guest->STATUS_ID = 205;
        // guest's first name
        $guest->FIRST_NAME = 'GUEST';
        // guest's last name
        $guest->LAST_NAME = 'TENSEI';

        $notif = $this->object->createCheckinNotification($guest);
        $this->assertEquals(json_decode($notif, true), null);
    }
}
