<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> updateRoomMessage_Test.php
 *
 * Create : 2021.02.16 TP Uddin
 * Update : 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_updateRoomMessage
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_updateRoomMessage extends TestCase
{
    /** @var string $username */
    protected $username = "admin";

    /** @var string $password */
    protected $password = "123123123";

    /** @var string $method */
    protected $method = "POST";

    /** @var string $uri URI of the ClientController@updateRoomMessage */
    protected $uri = "/client/room/message/update";

    /**
     *
     */
    public function test_updateRoomMessage()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            "ROOM_ID" => 1,
            "ROOM_MESSAGE" => "DO NOT DISTURB"
        ];
        // Expected result
        $expected = 'success';
        // Actual result;
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertSeeText($expected);
    }

    /**
     * Error Case
     */
    public function test_updateRoomMessage_ErrorCase()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        $body = [
            "ROOM_ID" => 0,
            "ROOM_MESSAGE" => "DO NOT DISTURB"
        ];
        // Expected result
        $expected = 'No query results for model [App\Room]';
        // Actual result;
        $response = $this->json($this->method, $this->uri, $body);
        $response->assertStatus(200);
        $response->assertSeeText($expected);
    }
}
