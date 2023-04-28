<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> getAllRooms_Test.php
 *
 * Create  => 2021.02.26 TP Uddin<br>
 * Update :
 */

namespace Tests\Unit\ManagementController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ManagementController_getAllRooms
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ManagementController_getAllRooms extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = 'getAllRooms';

    /**
     *
     */
    public function test_getAllRooms()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // Expected JSON result is too big to test

        // Actual result
        $response = $this->json($this->method, $this->uri);
        // Test HTTP status of the web api
        $response->assertStatus(200);
        // Test number of items of the JSON array
        $response->assertJsonCount(20);
    }
}
