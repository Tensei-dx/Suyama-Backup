<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getPeopleCounterCameras_Test.php
 *
 * Create : 2021.02.26 TP Uddin
 * Update :
 */

namespace Tests\Unit\ManagementController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ManagementController_getPeopleCounterCameras
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ManagementController_getPeopleCounterCameras extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = 'getPeopleCounterCameras';

    /**
     *
     */
    public function test_getPeopleCounterCameras()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Expected result
        $expected = [
            [
                "ROOM_ID" => 1,
                "DEVICE_ID" => 213213229,
                "DATA" => [
                    "peopleIn" => 149,
                    "peopleOut" => 130,
                    "yesterdayIn" => 147,
                    "yesterdayOut" => 130
                ],
                "totalOccupancy" => 36
            ]
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
