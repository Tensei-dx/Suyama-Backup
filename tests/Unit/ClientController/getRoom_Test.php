<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getRoom_Test.php
 *
 * Create : 2021.02.16 TP Uddin
 * Update : 2021.02.22 TP Uddin replace mock builder with PHPUnit json request
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ClientController_getRoom
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ClientController_getRoom extends TestCase
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
    public function test_getRoom()
    {
        // Query
        $id = 1;
        $this->uri = "/client/room/$id";
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Expected result
        $expected = [
            "ROOM_ID" => 1,
            "FLOOR_ID" => 1,
            "ROOM_NAME" => "ROOM 101",
            "ROOM_STATUS" => 2,
            "ROOM_MESSAGE" => "DON'T DISTURB",
            "ROOM_TOTAL_PEOPLE" => 6,
            "ROOM_MAP_DATA" => [
                "ROOM_MAP" => "stp_room"
            ],
            "ROOM_TYPE_ID" => 1,
            "STATUS_ID" => 201,
            "book" => [
                "BOOK_ID" => 1,
                "ROOM_ID" => 1,
                "NO_OF_PEOPLE" => 15,
                "BOOK_NO" => "123",
                "BOOK_PHONE_NO" => "123",
                "BOOK_EMAIL" => "12323@hh.com",
                "BOOK_AC_LASTNAME" => "Reyes",
                "BOOK_AC_NAME" => "Russell",
                "BOOK_NAME" => "WengWeng",
                "CHECK_IN_TIME" => "2021-01-01 08:00:00",
                "CHECK_OUT_TIME" => "2021-01-22 17:00:00",
                "CREATED_AT" => "2021-01-05 01:38:23",
                "UPDATED_AT" => "2021-01-05 01:38:23"
            ]
        ];
        // Actual result
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
        $response->assertExactJson($expected);
    }
}
