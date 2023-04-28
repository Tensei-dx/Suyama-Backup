<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getLatestData_Test.php
 *
 * Create : 2021.02.16 TP Uddin
 * Update : 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_getLatestData
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_getLatestData extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "POST";

    /** @var string $uri URI of the ClientController@getLatestData */
    protected $uri = "/client/data/latest";

    /**
     *
     */
    public function test_getLatestData()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            "DEVICE_ID" => 213213229
        ];
        // Expected result
        $expected = [
            "PROCESSED_DATA_ID" => 794531,
            "DEVICE_ID" => 213213229,
            "DATA" => [
                "peopleIn" => 74,
                "peopleOut" => 83,
                "yesterdayIn" => 67,
                "yesterdayOut" => 78
            ],
            "SEND_FLAG" => 0,
            "CREATED_AT" => "2021-02-22 16:24:32",
            "UPDATED_AT" => "2021-02-22 16:24:32"
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
