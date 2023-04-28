<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\RemoteLockController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 *
 */
class RemoteLockController_getApiInfoTest extends TestCase
{
    use AuthenticatesUsers;

    /**
     * @var RemoteLockController
     */
    protected $object;

    /**
     * setUp is executed before each test method is executed
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create an object to test
        $this->object = new RemoteLockController();
    }

    /**
     * Verification of the acquired data
     */
    public function test_getApiInfo()
    {
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $request->API_NAME = 'remote_lock';
        $expected = '[{"API_ID":2,"API_NAME":"remote_lock","TOKEN_URL":"https:\/\/connect.remotelock.jp\/oauth\/token","API_URL":"https:\/\/connect.remotelock.jp\/oauth\/authorize","CLIENT_ID":"60c0409c557cddd90e74732f8ded3235a36897d4aebf2832e06a0a8009162446","CLIENT_SECRET":"513d449244811276b378a7e48d9d5a1fd69b69d3c5ab90da705d18be89c5d8ec","REDIRECT_URL":"https:\/\/192.168.40.220\/remotelock","GRANT_TYPE":"authorization_code","CONTENT_TYPE":"application\/x-www-form-urlencoded","AUTH_CODE":"1024428813c33fd8ed4c569df6b5bccc43a7205bd10471a568b387e2a9ab5fbf","CREATED_AT":"2021-01-11 15:14:39","UPDATED_AT":"2021-01-12 05:15:43"}]';
        $device = $this->object->getApiInfo($request);
        $this->assertEquals($device, $expected);
    }
}
