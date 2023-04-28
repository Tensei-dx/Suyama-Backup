<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> sendInstruction_Test.php
 *
 * Create : 2021.02.16 TP Uddin
 * Update : 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_sendInstruction
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_sendInstruction extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "POST";

    /** @var string $uri URI of the ClientController@sendInstruction */
    protected $uri = "/client/instruct";

    /**
     *
     */
    public function test_sendInstruction()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            "GATEWAY_ID" => "3",
            "DEVICE_ID" => "2",
            "GATEWAY_IP" => "192.168.40.160",
            "addlValue" => "",
            "COMMAND" => "status_1",
            "VALUE" => "1",
            "event" => "event",
            "TYPE" => "Manual"
        ];
        // Expected result
        $expected = [
            "function" => "instruct"
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }

    /**
     * Error Case
     */
    public function test_sendInstruction_ErrorCase()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            "GATEWAY_ID" => "999",
            "DEVICE_ID" => "2",
            "GATEWAY_IP" => "192.168.40.160",
            "addlValue" => "",
            "COMMAND" => "status_1",
            "VALUE" => "1",
            "event" => "event",
            "TYPE" => "Manual"
        ];
        // Expected result
        $expected = "No query results for model [App\Gateway].";
        // Actual result
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertSeeText($expected);
    }
}
