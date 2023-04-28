<?php

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ApplianceOperationControllerTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "GET";

    /** @var string $uri */
    private $uri = "getAllOperations";

    /**      
     * 取得データの検証     
     */
    public function test_getAllOperations()
    {
        Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ]);

        //----------------------------------------------------------------------        
        // CASE1：In case of branching of if statement / if分岐の場合     
        //----------------------------------------------------------------------        
        // 期待値      
        $expected = [
            [
                "OPERATION_ID" => 1,
                "OPERATION_NAME" => "TEMP_16",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 2,
                "OPERATION_NAME" => "TEMP_30",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 3,
                "OPERATION_NAME" => "POWER",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 4,
                "OPERATION_NAME" => "AC_POWER_ON",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 5,
                "OPERATION_NAME" => "AC_POWER_OFF",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 6,
                "OPERATION_NAME" => "TEMP_17",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 7,
                "OPERATION_NAME" => "TEMP_18",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 8,
                "OPERATION_NAME" => "TEMP_19",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 9,
                "OPERATION_NAME" => "TEMP_20",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 10,
                "OPERATION_NAME" => "TEMP_21",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 11,
                "OPERATION_NAME" => "TEMP_22",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 12,
                "OPERATION_NAME" => "TEMP_23",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 13,
                "OPERATION_NAME" => "TEMP_24",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 14,
                "OPERATION_NAME" => "TEMP_25",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 15,
                "OPERATION_NAME" => "TEMP_26",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 16,
                "OPERATION_NAME" => "TEMP_27",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 17,
                "OPERATION_NAME" => "TEMP_28",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ],
            [
                "OPERATION_ID" => 18,
                "OPERATION_NAME" => "TEMP_29",
                "CREATED_AT" => "2019-02-27 08:46:51",
                "UPDATED_AT" => "2019-02-27 08:46:51"
            ]
        ];

        // Actual response
        $actual = $this->json($this->method, $this->uri);
        // Test HTTP status code of the web api
        $actual->assertStatus(200);
        $actual->assertExactJson($expected);
    }
}
