<?php

/**
 * <System Name> iBMS
 */

namespace Tests\Unit;

use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * 
 */
class DashboardController_getProcessedData extends TestCase
{
    use AuthenticatesUsers;

    /**
     * @var DashboardController
     */
    protected $object;

    /**
     * setUp is executed before each test method is executed
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create an object to test
        $this->object = new DashboardController();
    }

    /**
     * Verification of the acquired data
     */
    public function test_getProcessedData()
    {
        Auth::attempt(['username' => 'admin', 'password' => '123123123']);

        /**********************************************************************/
        /* ROOM_ID is set and DEVICE_TYPE is people_counter                   */
        /**********************************************************************/
        $response = $this->json('POST', '/getProcessedData', [
            'FLOOR_ID' => 1,
            'ROOM_ID' => 1,
            'DEVICE_ID' => 2326,
            'DEVICE_TYPE' => 'people_counter'
        ]);
        $response->assertStatus(200);

        /**********************************************************************/
        /* ROOM_ID is set and DEVICE_TYPE is people_counter                   */
        /**********************************************************************/

        // Create a mock of Request class
        $response = $this->json('POST', '/getProcessedData', [
            'FLOOR_ID' => 1,
            'ROOM_ID' => '',
            'DEVICE_ID' => 2331,
            'DEVICE_TYPE' => 'temp_hum'
        ]);
        $response->assertStatus(200);
    }
}
