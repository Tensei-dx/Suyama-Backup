<?php

/**
 * <System Name> iBMS HoteRes
 * <Program Name> peopleCounterData_Test.php
 *
 * Create : 2021.02.26 TP Uddin
 * Update :
 */

namespace Tests\Unit\ManagementController;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * <Class Name> ManagementController_peopleCounterData
 *
 * <Overview> Class that is used to perform PHP Unit test
 * @package Test
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 GoTensei Inc.
 */
class ManagementController_peopleCounterData extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "peopleCounterData";

    /**
     * @author TP Uddin <u-almujeer@tenseiph.com>
     * @since 2021.03.01
     *
     * The method have 3 branches, which does not depend on the input parameters
     * but rely on internal process. So I created multiple dummy cameras with
     * the respected process data according to the branch statement to cover
     * all process.
     */
    public function test_peopleCounterData()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);
        // method has no return
        $response = $this->json($this->method, $this->uri);
        $response->assertStatus(200);
    }
}
