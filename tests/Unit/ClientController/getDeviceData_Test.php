<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getDeviceData_Test.php
 *
 * Create : 2021.02.16 TP Uddin
 * Update : 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_getDeviceData
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_getDeviceData extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "GET";

    /** @var string $uri */
    protected $uri;

    /**
     *
     */
    public function test_getDeviceData()
    {
        // Query
        $id = 1;
        $this->uri = "/client/device/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Expected result
        $expected = [
            [
                "status" => "1",
                "device_name" => "Entrance"
            ],
            [
                "status" => "1",
                "device_name" => "Storage Room"
            ],
            [
                "status" => "1",
                "device_name" => "GA"
            ]
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
