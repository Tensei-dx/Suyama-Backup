<?php

use Tests\TestCase;

class getAllAppliancesTest extends TestCase
{
    /** @var string $username */
    private $username = "admin";

    /** @var string $password */
    private $password = "123123123";

    /** @var string $method */
    private $method = "POST";

    /** @var string $uri */
    private $uri = "ApplianceController";

    /**
     * 取得データの検証
     */
    public function test_getAllAppliances()
    {
        $request = $this->getMockBuilder('Illuminate\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        //----------------------------------------------------------------------		
        // CASE1：In case of branching of if statement / if分岐の場合		
        //----------------------------------------------------------------------		
        // 期待値
        $expected = '[{"APPLIANCE_ID":32,"APPLIANCE_NAME":"Aircon-01","APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","CREATED_AT":"2019-07-31 10:07:57","UPDATED_AT":"2019-07-31 10:07:57","ir_learning":[]},{"APPLIANCE_ID":35,"APPLIANCE_NAME":"AC-room1","APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","CREATED_AT":"2019-09-13 07:41:30","UPDATED_AT":"2019-09-13 07:41:30","ir_learning":[{"IR_LEARNING_LIST_ID":46,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":14,"LEARNING_VALUE":0,"CREATED_AT":"2019-10-01 07:54:46","UPDATED_AT":"2019-10-01 07:54:46"},{"IR_LEARNING_LIST_ID":49,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":9,"LEARNING_VALUE":7,"CREATED_AT":"2019-10-01 09:22:48","UPDATED_AT":"2019-10-01 09:22:48"},{"IR_LEARNING_LIST_ID":51,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":13,"LEARNING_VALUE":3,"CREATED_AT":"2019-10-01 09:25:54","UPDATED_AT":"2019-10-01 09:25:54"},{"IR_LEARNING_LIST_ID":60,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":3,"LEARNING_VALUE":1,"CREATED_AT":"2019-10-01 09:47:03","UPDATED_AT":"2019-10-01 09:47:03"},{"IR_LEARNING_LIST_ID":61,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":5,"LEARNING_VALUE":5,"CREATED_AT":"2019-10-01 09:48:26","UPDATED_AT":"2019-10-01 09:48:26"},{"IR_LEARNING_LIST_ID":63,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":10,"LEARNING_VALUE":4,"CREATED_AT":"2019-10-01 09:51:00","UPDATED_AT":"2019-10-01 09:51:00"},{"IR_LEARNING_LIST_ID":64,"DEVICE_ID":99,"APPLIANCE_ID":35,"OPERATION_ID":11,"LEARNING_VALUE":6,"CREATED_AT":"2019-10-01 09:53:57","UPDATED_AT":"2019-10-01 09:53:57"}]},{"APPLIANCE_ID":36,"APPLIANCE_NAME":"Bedroom TV","APPLIANCE_TYPE":"TV","BRAND_NAME":"Devant","CREATED_AT":"2019-09-13 10:55:56","UPDATED_AT":"2019-09-13 10:55:56","ir_learning":[]},{"APPLIANCE_ID":53,"APPLIANCE_NAME":"Air con","APPLIANCE_TYPE":"AC2","BRAND_NAME":"Samsung","CREATED_AT":"2019-12-04 12:06:11","UPDATED_AT":"2019-12-04 12:06:11","ir_learning":[]},{"APPLIANCE_ID":55,"APPLIANCE_NAME":"asdasd","APPLIANCE_TYPE":"asdasdasd","BRAND_NAME":"asdasdasdas","CREATED_AT":"2019-12-06 09:24:04","UPDATED_AT":"2019-12-06 09:24:04","ir_learning":[]},{"APPLIANCE_ID":57,"APPLIANCE_NAME":"Panasonic","APPLIANCE_TYPE":"AC","BRAND_NAME":"Panasonic","CREATED_AT":"2020-01-20 14:50:26","UPDATED_AT":"2020-01-20 14:50:26","ir_learning":[]},{"APPLIANCE_ID":59,"APPLIANCE_NAME":"New try","APPLIANCE_TYPE":"Samsung","BRAND_NAME":"TV","CREATED_AT":"2020-04-30 16:40:56","UPDATED_AT":"2020-04-30 16:40:56","ir_learning":[]},{"APPLIANCE_ID":66,"APPLIANCE_NAME":"APP TEST","APPLIANCE_TYPE":"AC TEST","BRAND_NAME":"BRAND TEST","CREATED_AT":"2020-05-26 13:03:11","UPDATED_AT":"2020-05-26 13:03:11","ir_learning":[]},{"APPLIANCE_ID":71,"APPLIANCE_NAME":"Television","APPLIANCE_TYPE":"TV","BRAND_NAME":"LG","CREATED_AT":"2020-05-27 15:19:39","UPDATED_AT":"2020-05-27 15:19:39","ir_learning":[]}]';

        // getAllAppliancesメソッドからの返り値を格納		
        $appliances = $this->object->getAllAppliances($request);

        // getAllAppliancesからの返り値が正しいか検証する		
        $this->assertEquals($expected, $appliances);
    }
}
