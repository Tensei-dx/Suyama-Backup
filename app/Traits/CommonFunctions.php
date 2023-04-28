<?php
/*
 * <System Name> iBMS
 * <Program Name> CommonFunctions.php
 *
 * <Create> 2018.06.21 TP Bryan
 * <Update> 2018.06.29 TP Bryan    Edited 1.0 Fixed Timeout -> Variable Timeout
 *          2018.07.09 TP Bryan    Added 5.0
 *          2018.07.19 TP Bryan    Added 6.0
 *          2018.11.05 TP Robert   Modify createResponse Add Manufacturer ID
 *          2018.12.17 TP Robert   Create Function for Logs
 *          2018.12.18 OJT Jethro  Added line 932 to differentiate Automated or Manual command for Logs
 *          2019.05.30 TP Harvey   Applying Coding Standard
 *          2019.07.08 TP Ivin     Insert try and catch in notification functions
 *          2019.07.18 TP Yani     Changed audit logs in catch to store logs
 *          2020.01.15 TP Harvey   Added condition in before save in insertDevicetoNotification Function
 *          2020.01.15 TP Harvey   Added condition in before save in processNotification Function
 *          2020.01.15 TP Harvey   Added parameter in insertNotification Function
 *          2020.05.15 TP Uddin    Implement coding standards for PHP7
 *          2020.06.22 TP Harvey   Remove request argument in convertDeviceData
 *          2020.06.22 TP Harvey   Convert insertNotification to createNotification
 *          2020.06.22 TP Harvey   Remove Request Parameter in newInstructionRequest (not needed)
 *          2020.09.23 TP Uddin    Added new mode to processNotification for camera notifs
 *          2020.10.14 TP Uddin    Added sendRequestToPython method
 *          2020.10.14 TP Uddin    Added getAcsCameraList method
 *          2020.10.14 TP Uddin    Added getAcsServerConfiguration method
 *          2020.10.14 TP Uddin    Added getAcsSystem method<br>
 *          2021.05.18 TP Ivin     Added Access Token For Netvox Gateway
 *          2021.06.03 TP Harvey   Modify ConvertDeviceData for Netvox Device compatibility
 *          2021.06.03 TP Harvey   Added Netvox Device condition on insertDevicetoNotification and processNotification
 */

namespace App\Traits;

use App\Events\DeviceCommandEvent;
use App\Events\LogsNotificationEvent;
use App\Mail\NotificationMail;
use App\Models\Api;
use App\Models\ApiToken;
use App\Models\Appliances;
use App\Models\AppLogs;
use App\Models\AuditLogs;
use App\Models\Code;
use App\Models\Device;
use App\Models\IrLearning;
use App\Models\Logs;
use App\Models\LogsNotification;
use App\Models\Room;
use App\Models\SaveLog;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * <Class Name> CommonFunctions
 *
 * <Overview> Utility functions
 *
 * @package Trait
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
trait CommonFunctions
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // sendToSocket                    (1.0) Send message to UDP socket
    // convertDeviceType               (2.0) Convert device type to human-readable format
    // convertInstruction              (3.0) Convert instruction to machine-readable format
    // convertDeviceData               (4.0) Convert device data to human-readable format
    // generatePassword                (5.0) Generate a unique alphanumeric password
    // encryptMessage                  (6.0) Encrypt a message
    // createGetResponse               (7.0) Create response to GET request
    // broadcastNewData                (8.0) Broadcast new data to frontend
    // insertDevicetoNotification      (9.0) Execute instructions according to registered binding
    // processNotification             (10.0) Process Notification command from insertDevicetoNotification
    // decryptMessage                  (11.0) Decrypts Message From AES-CBC-256
    // isJson                          (12.0) Check if string is in JSON Format
    // newInstructionRequest           (13.0) Set new Instruction to devices
    // deleteDevicePlot                (14.0) Device Plotting from Floor Table
    // storeDeviceData                 (15.0) Save Device data
    // storeLogs                       (16.0) Store System Logs
    // auditLogs                       (17.0) Store Audit Trail Logs
    // currentTime                     (18.0) Get Current Time
    // storeIR                         (19.0) store IR data
    // sendRequestToPython             (20.0) Send HTTP request to Python and return the parsed response
    // getAcsCameraList                (21.0) Call GetCameraList ACS API
    // getAcsServerConfiguration       (22.0) Call GetServerConfiguration ACS API
    // getAcsSystem                    (23.0) Call GetSystem ACS API
    // getAccessForNetvoxGateway       (27.0) Call Netvox Gateway Token
    // saveLogs                        (28.0) Store System Error Logs
    // processError                    (29.0) Process the thrown error

    /**
     * <Layer Number> (1.0)
     * <Processing Name> sendToSocket<br>
     * <Function> Send message to UDP socket<br>
     *
     * @param string $remote_ip
     * @param string $remote_port
     * @param string $message
     * @param array $timeout
     * @return string rtrim($buf)
     */
    public function sendToSocket(
        string $remote_ip,
        string $remote_port,
        string $message,
        array $timeout = ["sec" => 0, "usec" => 10000]
    ): string {
        try {
            $recvBool = false;  //Bool for the socketrecvfrom
            $try = 1;           //Number of tries
            $tryCounter = 0;    //Try counter
            $sock = socket_create(
                AF_INET,
                SOCK_DGRAM,
                SOL_UDP
            );
            // Maximum execution time of 10 secs
            socket_set_option(
                $sock,
                SOL_SOCKET,
                SO_RCVTIMEO,
                $timeout
            );
            socket_sendto(
                $sock,
                $message,
                strlen($message),
                0,
                $remote_ip,
                $remote_port
            );
            while ($recvBool == false) {
                $recvBool = socket_recvfrom(
                    $sock,
                    $buf,
                    1024,
                    0,
                    $remote_ip,
                    $remote_port
                );
                if ($tryCounter > $try) {
                    break;
                }
                $tryCounter++;
            }
            return rtrim($buf); // Remove trailing null-terminator/s
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = __FUNCTION__;
            $content = $uri . " : " . $e->getMessage();
            $ip = '-';
            $username = '-';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
    }

    /**
     * <Layer Number> (2.0)
     * <Processing Name> convertDeviceType<br>
     * <Function> Convert device type to human-readable format<br>
     *
     * @param string $devType
     * @return string $code->DEVICE_TYPE_VALUE
     */
    public function convertDeviceType(string $devType): string
    {
        try {
            $code = Code::where('DEVICE_TYPE_CODE', $devType)
                ->select('DEVICE_TYPE_VALUE')
                ->first();
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = __FUNCTION__;
            $content = $uri . " : " . $e->getMessage();
            $ip = '-';
            $username = '-';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
        return $code ? $code->DEVICE_TYPE_VALUE : "Code does not exist";
    }

    /**
     * <Layer Number> (3.0)
     * <Processing Name> convertInstruction<br>
     * <Function> Convert instruction to machine-readable format<br>
     *
     * @param string $devType
     * @param string $command
     * @param string $value
     * @param string $extVal
     * @return string $value
     * @throws Throwable When an exception occurs in this process
     */
    public function convertInstruction(
        string $devType,
        string $command,
        string $value,
        string $extVal
    ): string {
        try {
            $getVal = Code::where('DEVICE_TYPE_VALUE', $devType)
                ->where('STATUS_VALUE', $command)
                ->where('COMMAND_CODE', $value)
                ->first()->COMMAND_VALUE;
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $content = "Common Functions >" . __FUNCTION__ . ' : '
                . $e->getMessage();
            $ip = '-';
            $username = '-';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
        switch ($devType) {
                // Special conversion of command for Door Lock
            case 'door_lock':
                $value = $getVal . $extVal;
                break;
                // Special conversion of command for IR Remote
            case 'ir_remote':
                $newValue = str_pad(
                    $extVal,
                    3,
                    "0",
                    STR_PAD_LEFT
                );
                $value = $getVal . $newValue;
                break;
                // Device/Sensor command
            default:
                $value = $getVal;
                break;
        }
        return $value;
    }

    /**
     * <Layer Number> (4.0)
     * <Processing Name> convertDeviceData<br>
     * <Function> Convert device data to human-readable format<br>
     *
     * @param string $devType
     * @param string $data
     * @param string $mode
     * @return array|array[]|\int[][]|string $retArr
     * @throws Throwable When an exception occurs in this process
     */
    public function convertDeviceData(string $devType, string $data, string $mode)
    {
        $retArr = [];
        $tempArr = str_split($data, 2);


        //*********************** */
        //*  For Wulian devices   */
        /************************ */
        switch ($devType) {
            case 'light':
                /**
                 *  Format is as follows:
                 *  08rrggbbxxmm
                 *  rr means red parameters, 00~FF;
                 *  gg means green parameters, 00~FF;
                 *  bb means blue parameters, 00~FF;
                 *  xx means brightness parameters, 00~FF (RGB maximum);
                 *  mm indicates mode, 01 normal mode, 02 colorful mode.
                 */
                $retArr['gradation'] = (int)($tempArr[1] . $tempArr[2]
                    . $tempArr[3]);
                $retArr['dimming'] = hexdec($tempArr[4]);
                $retArr['mode'] = (int)$tempArr[5];
                break;
            case 'temp_light':
                /**
                 *  Format is as follows:
                 *  03ccccxx
                 *  cccc indicates the color temperature parameter;
                 *  xx indicates the luminance parameter, 0x00~0xFF
                 */
                $retArr['temp_color'] = hexdec($tempArr[1]
                    . $tempArr[2]);
                $retArr['brightness'] = hexdec($tempArr[3]);
                //check the brightness
                if ($retArr['brightness'] != 0) {
                    $retArr['status'] = 1;
                } else {
                    $retArr['status'] = 0;
                }
                break;
            case 'curtain_1':
                //check the range of the curtain
                if ($mode == 'responseDeviceData')
                    $retArr['status'] = hexdec($tempArr[1]);
                else
                    $retArr['status'] = hexdec($tempArr[0]);
                break;
            case 'temp_hum':
                list($temp, $hum) = explode(",", $data);
                $retArr['temp'] = (float)$temp;
                $retArr['hum'] = (float)$hum;
                break;
            case 'ir_remote':
                // check if initial data of the ir remote or not
                if ($data == "00") {
                    $retArr = [];
                } else {
                    $storeVal = hexdec($tempArr[1] . $tempArr[2]);
                    // check if this is instruction(2) or register learning
                    // value cmd
                    if (hexdec($tempArr[0]) == '2') {
                        try {
                            $learnedName = IrLearning::with('operation')
                                ->where('LEARNING_VALUE', $storeVal)
                                ->first()->operation->OPERATION_NAME;
                            $brand = IrLearning::with('appliances')
                                ->with('operation')
                                ->with('device')
                                ->where('LEARNING_VALUE', $storeVal)
                                ->first()->appliances->BRAND_NAME;
                            $appliance = IrLearning::where(
                                'LEARNING_VALUE',
                                $storeVal
                            )
                                ->first()->appliances->APPLIANCE_TYPE;
                        } catch (\Throwable $e) {
                            // Insert System Logs
                            $type = '3';
                            $instructionType = 'System Error';
                            $uri = $request->route()->uri();
                            $content = "Common Functions >" . __FUNCTION__
                                . ' : ' . $e->getMessage();
                            $ip = $request->ip();
                            $username = auth()->user()->USERNAME;
                            $this->storeLogs(
                                $type,
                                $instructionType,
                                $content,
                                $ip,
                                $username
                            );
                            return 'failed';
                        }
                        // check what command is used
                        if ($learnedName == 'AC_POWER_ON') {
                            $retArr = [
                                [
                                    'type' => $appliance,
                                    'brand' => $brand,
                                    'status' => '1'
                                ]
                            ];
                        } elseif ($learnedName == 'AC_POWER_OFF') {
                            $retArr = [
                                [
                                    'type' => $appliance,
                                    'brand' => $brand,
                                    'status' => '0'
                                ]
                            ];
                        } elseif ($learnedName == 'POWER') {
                            $retArr = [
                                [
                                    'type' => $appliance,
                                    'brand' => $brand,
                                    'status' => null
                                ]
                            ];
                        } else {
                            $learnedValue = substr($learnedName, 5);
                            $retArr = [
                                [
                                    'type' => $appliance,
                                    'brand' => $brand,
                                    'temp_value' => $learnedValue
                                ]
                            ];
                        }
                    } else {
                        $retArr = [];
                        $learnedName = IrLearning::with('device')
                            ->where('LEARNING_VALUE', $storeVal)
                            ->first()->device->DATA;
                        $retArr = $learnedName;
                    }
                }
                break;
            case 'gas_valve':
                // check if open (02) or close (03)
                if ($data == "02") {
                    $retArr['status'] = 1;
                } elseif ($data == "03") {
                    $retArr['status'] = 0;
                }
                break;
            case 'wall_switch_1':
                $retArr = [
                    [
                        "status" => $data[0]
                    ]
                ];
                break;
            case 'wall_switch_2':
                $retArr = [
                    [
                        "status" => $data[0]
                    ],
                    [
                        "status" => $data[1]
                    ]
                ];
                break;
            case 'wall_switch_3':
                $retArr = [
                    [
                        "status" => $data[0]
                    ],
                    [
                        "status" => $data[1]
                    ],
                    [
                        "status" => $data[2]
                    ]
                ];
                break;
            case 'embedded_switch_1':
                // check if this is an initial register of embedded switch (4174)
                if ($data == "4174") {
                    $retArr = [
                        [
                            "status" => (int)$tempArr[0]
                        ]
                    ];
                } else {
                    $retArr = [
                        [
                            "status" => (int)$tempArr[1]
                        ]
                    ];
                }
                break;
            case 'embedded_switch_2':
                // check if this is an initial register of embedded switch (4174)
                if ($data == "4174") {
                    $retArr = [
                        [
                            "status" => (int)$tempArr[0]
                        ],
                        [
                            "status" => (int)$tempArr[1]
                        ]
                    ];
                } else {
                    $retArr = [
                        [
                            "status" => (int)$tempArr[1]
                        ],
                        [
                            "status" => (int)$tempArr[2]
                        ]
                    ];
                }
                break;
            case 'embedded_switch_3':
                // check if this is an initial register of embedded switch (4174)
                if ($data == "4174") {
                    $retArr = [
                        [
                            "status" => (int)$tempArr[0]
                        ],
                        [
                            "status" => (int)$tempArr[1]
                        ],
                        [
                            "status" => (int)$tempArr[0]
                        ]
                    ];
                } else {
                    $retArr = [
                        [
                            "status" => (int)$tempArr[1]
                        ],
                        [
                            "status" => (int)$tempArr[2]
                        ],
                        [
                            "status" => (int)$tempArr[3]
                        ]
                    ];
                }
                break;
            case 'motion_detector':
            case 'h2o_detector':
            case 'gas_detector':
            case 'smoke_detector':
            case 'co2_detector':
            case 'panic_button':
            case 'water_valve':
            case 'light_detector':
            case 'dust_detector':
                $retArr['status'] = hexdec($data);
                break;
            case 'multi_detector':
                //check what detector is triggered, motion(06)
                if ($tempArr[0] == "06") {
                    $retArr["status_motion"] = $tempArr[3];
                } elseif ($tempArr[0] == "0A") {
                    // If return data from MC also includes humidity and light
                    if ($tempArr[6]) {
                        // Get Temperature
                        $temp = hexdec($tempArr[4] . $tempArr[5]);
                        // Get Humidity
                        $hum = hexdec($tempArr[10] . $tempArr[11]);
                        // Get Light
                        $light = hexdec($tempArr[16] . $tempArr[17]);
                        // Temperature
                        switch ($tempArr[3]) {
                            case 1:
                                // Positive number w/ 0 decimal
                                $retArr["temp"] = $temp;
                                break;
                            case 2:
                                // Positive number w/ 1 decimal
                                $retArr["temp"] = $temp / 10;
                                break;
                            case 3:
                                // Positive number w/ 2 decimals
                                $retArr["temp"] = $temp / 100;
                                break;
                            case 4:
                                // Negative number w/ 0 decimal
                                $retArr["temp"] = $temp / -1;
                                break;
                            case 5:
                                // Negative number w/ 1 decimal
                                $retArr["temp"] = $temp / -10;
                                break;
                            case 6:
                                // Negative number w/ 2 decimals
                                $retArr["temp"] = $temp / -100;
                                break;
                            default:
                                # code...
                                break;
                        }
                        // Humidity
                        switch ($tempArr[9]) {
                            case 1:
                                // Positive number w/ 0 decimal
                                $retArr["hum"] = $hum;
                                break;
                            case 2:
                                // Positive number w/ 1 decimal
                                $retArr["hum"] = $hum / 10;
                                break;
                            case 3:
                                // Positive number w/ 2 decimals
                                $retArr["hum"] = $hum / 100;
                                break;
                            default:
                                # code...
                                break;
                        }
                        // Save Light Intensity LUX
                        $retArr["status_light"] = $light;
                    } else {
                        switch ($tempArr[1]) {
                            case 'D1':
                                $val = hexdec($tempArr[3]);
                                $temp = hexdec($tempArr[4]
                                    . $tempArr[5]);
                                switch ($val) {
                                    case 1:
                                        // Positive number w/ 0 decimal
                                        $retArr["temp"] = $temp;
                                        break;
                                    case 2:
                                        // Positive number w/ 1 decimal
                                        $retArr["temp"] = $temp / 10;
                                        break;
                                    case 3:
                                        // Positive number w/ 2 decimals
                                        $retArr["temp"] = $temp / 100;
                                        break;
                                    case 4:
                                        // Negative number w/ 0 decimal
                                        $retArr["temp"] = $temp / -1;
                                        break;
                                    case 5:
                                        // Negative number w/ 1 decimal
                                        $retArr["temp"] = $temp / -10;
                                        break;
                                    case 6:
                                        // Negative number w/ 2 decimals
                                        $retArr["temp"] = $temp / -100;
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                break;
                            case 'D2':
                                $val = hexdec($tempArr[3]);
                                $hum = hexdec($tempArr[4]
                                    . $tempArr[5]);
                                switch ($val) {
                                    case 1:
                                        // Positive number w/ 0 decimal
                                        $retArr["hum"] = $hum;
                                        break;
                                    case 2:
                                        // Positive number w/ 1 decimal
                                        $retArr["hum"] = $hum / 10;
                                        break;
                                    case 3:
                                        // Positive number w/ 2 decimals
                                        $retArr["hum"] = $hum / 100;
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            case 'D3':
                                $retArr["status_light"] = hexdec(
                                    $tempArr[4] . $tempArr[5]
                                );
                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }
                break;
            case 'door_lock':
                if ($data == '022C') {
                    // door open
                    $retArr['status_exit'] = 1;
                    $retArr['status_lock'] = 1;
                } elseif ($data == '0102') {
                    // door close
                    $retArr['status_exit'] = 0;
                    $retArr['status_lock'] = 0;
                } elseif ($data == '0A10') {
                    // unlock password error/wrong password
                    $retArr['status_exit'] = 0;
                    $retArr['status_lock'] =
                        'unlock password error/wrong password';
                } elseif ($data == '021D') {
                    // door to wall damage
                    $retArr['status_exit'] = 0;
                    $retArr['status_lock'] = 'door to wall dmg';
                } elseif ($data == '021F') {
                    // system lock/wrong pass x3
                    $retArr['status_exit'] = 0;
                    $retArr['status_lock'] = 'system lock/wrong pass x3';
                } elseif ($data == '021E') {
                    // system lock released/system returned to normal
                    $retArr['status_exit'] = 0;
                    $retArr['status_lock'] =
                        'system lock released/system returned to normal';
                } elseif (strlen($data) == '12') {
                    $retArr['status_exit'] = 0;
                    $retArr['status_lock'] = '0808';
                    $retArr['user_Attribute'] = '00';
                    $retArr['user'] = '01';
                    $retArr['access_mode'] = '03';
                    $retArr['cName'] = '01';
                }
                break;

            default:
                break;
        }


        //*********************** */
        //*  For Netvox devices   */
        /************************ */
        if ($tempArr[0] == '82' || $tempArr[0] == '81') {
            return "Configuration";
            Log::info("Netvox Device Configuration: " . $data);
        }
        switch ($devType) {

            case 'occupancy_temp_light':

                $voltage = hexdec($tempArr[3]);                     //Voltage
                $voltage = substr_replace($voltage, '.', 1, 0);
                $retArr['battery'] = $voltage;

                $temp = hexdec($tempArr[4] . $tempArr[5]);          //Temperature
                $temp = substr_replace($temp, '.', 2, 0);
                $retArr['temperature'] = $temp;

                $lux = hexdec($tempArr[6] . $tempArr[7]);           //Luminocity
                $retArr['lux'] = $lux;

                $occupancy = hexdec($tempArr[8]);                   //Occupancy
                $retArr['occupancy'] = $occupancy;

                break;
            case 'co2_temp_humid':

                if ($tempArr[2] == '0c') {

                    // Temperature
                    [$notation, $offset] = [1e-2, 0x8000];
                    // concat the 4th and 5th byte then convert from hex to dec
                    $dec = hexdec($tempArr[4] . $tempArr[5]);
                    // if the value exceeds the offset,
                    // the value is negative and should perform complement
                    $temp = ($dec > $offset) ? ($dec - (2 * $offset)) : $dec;
                    // adjust the decimal place
                    $retArr['temperature'] = $temp;

                    // Humidity
                    [$notation, $disabled] = [1e-2, 0xFFFF];
                    // concat the 6th and 7th byte then convert from hex to dec
                    $humid = hexdec($tempArr[6] . $tempArr[7]);
                    // if the value is 0xFFFF, then the sensor is disabled
                    if ($humid !== $disabled) {
                        // adjust the decimal place
                        $humid *= $notation;
                        $retArr['humidity'] = $humid;
                    }
                } else if ($tempArr[2] == '07') {
                    // Carbon Dioxide
                    [$notation, $disabled] = [1e-1, 0xFFFF];
                    // concat the 4th and 5th byte then convert from hex to dec
                    $co2 = hexdec($tempArr[4] . $tempArr[5]);

                    if ($co2 !== $disabled) {
                        // adjust the decimal place
                        $co2 *= $notation;
                        $retArr['co2'] = $co2;
                    }
                }

                break;

            case 'window_door_sensor':
                $voltage = hexdec($tempArr[3]);                       //Voltage
                $voltage = substr_replace($voltage, '.', 1, 0);
                $retArr['battery'] = $voltage;

                $status = hexdec($tempArr[4]);                        //Status
                $retArr['status'] = $status;

                break;
            case 'emergency_button':
                $voltage = hexdec($tempArr[3]);                       //Voltage
                $voltage = substr_replace($voltage, '.', 1, 0);
                $retArr['battery'] = $voltage;

                $status = hexdec($tempArr[4]);                        //Status
                $retArr['status'] = $status;
                break;
            default:
                break;
        }

        return $retArr;
    }

    /**
     * <Layer Number> (5.0)
     * <Processing Name> generatePassword<br>
     * <Function> Generate a unique 8-character alphanumeric password<br>
     *
     * @param int $length
     * @return string $password
     */
    public function generatePassword(int $length): string
    {
        $password = str_random($length);
        return $password;
    }

    /**
     * <Layer Number> (6.0)
     * <Processing Name> encryptMessage<br>
     * <Function> Encrypts a message<br>
     *
     * @param string $message
     * @return string base64_encode($encryptedMessage)
     */
    public function encryptMessage($message): string
    {
        // Do not use encrypt() because it will serialize $message
        $encrypted = Crypt::encryptString($message);
        $data = json_decode(base64_decode($encrypted), true);
        $iv = $data['iv'];
        $value = $data['value'];
        $length = strlen(base64_decode($value));
        $encryptedMessage = '{"message":"' . $value . '","iv_key":"' . $iv
            . '","length":"' . $length . '"}' . "\n";
        return base64_encode($encryptedMessage);
    }

    /**
     * <Layer Number> (7.0)
     * <Processing Name> createGetResponse<br>
     * <Function> Create response to GET request<br>
     *
     * @param $request
     * @param $model
     * @param int $id
     * @return mixed $model
     * @throws Throwable When an exception occurs in the process
     */
    public function createGetResponse($request, $model, int $id = null)
    {
        // Check include parameters
        if ($request->include) {
            $data = explode('>', $request->include);
            try {
                $model->with($data);
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return $e->getMessage();
            }
        }
        // Check search parameters
        if ($request->advSearch == 'true') {
            if ($request->floorID) {
                $model = $model->where(function ($query) use ($request) {
                    // List of relations that will be used for searching
                    // (e.g. searching FLOOR_NAME from a "belongs to floor"
                    // relation)
                    $relations = explode('>', $request->include);
                    $query = $query->orWhereHas($relations[0], function ($query2)
                    use ($request) {
                        if ($request->floorID && $request->roomID) {
                            try {
                                $query2->where('FLOOR_ID', $request->floorID)
                                    ->where('ROOM_ID', $request->roomID);
                                $query2->where(function ($query3)
                                use ($request) {
                                    if ($request->search) {
                                        // Search columns within the table
                                        $dev = new Device();
                                        $columns = $dev->getSearchableColumns();
                                        foreach ($columns as $column) {
                                            $query3 = $query3->orWhere(
                                                $column,
                                                'like',
                                                '%' . $request->search
                                                    . '%'
                                            );
                                        }
                                    }
                                });
                            } catch (\Exception $e) {
                                // Insert System Logs
                                $type = '3';
                                $instructionType = 'System Error';
                                $content = "Common Functions >" . __FUNCTION__
                                    . ' : ' . $e->getMessage();
                                $ip = '-';
                                $username = '-';
                                $this->storeLogs(
                                    $type,
                                    $instructionType,
                                    $content,
                                    $ip,
                                    $username
                                );
                                return $e->getMessage();
                            }
                        } elseif ($request->floorID) {
                            try {
                                $query2->where('FLOOR_ID', $request->floorID);
                            } catch (\Exception $e) {
                                // Insert System Logs
                                $type = '3';
                                $instructionType = 'System Error';
                                $content = "Common Functions >" . __FUNCTION__
                                    . ' : ' . $e->getMessage();
                                $ip = '-';
                                $username = '-';
                                $this->storeLogs(
                                    $type,
                                    $instructionType,
                                    $content,
                                    $ip,
                                    $username
                                );
                                return $e->getMessage();
                            }
                        }
                    });
                });
            }
        } elseif ($request->advSearch != 'true') {
            if ($request->search) {
                try {
                    $model = $model->where(function ($query) use ($request) {
                        // List of searchable columns for a specific model
                        // (i.e. primary/foreign keys will not be included
                        // in the search)
                        $columns = $query->getModel()->getSearchableColumns();
                        // List of relations that will be used for searching
                        // (e.g. searching FLOOR_NAME from a "belongs to floor"
                        // relation)
                        $relations = explode('>', $request->include);
                        // Search columns within the table
                        foreach ($columns as $column) {
                            $query = $query->orWhere($column, 'like', '%'
                                . $request->search . '%');
                        }
                        // Search for relations
                        foreach ($relations as $relation) {
                            $query = $query->orWhereHas($relation, function ($q)
                            use ($request) {
                                $columns = $q->getModel()
                                    ->getSearchableColumns();
                                // Search columns from another table (i.e. from
                                // the relation)
                                foreach ($columns as $column) {
                                    $q->where($column, 'like', '%'
                                        . $request->search . '%');
                                }
                            });
                        }
                    });
                } catch (\Exception $e) {
                    //Insert System Logs
                    $type = '3';
                    $instructionType = 'System Error';
                    $content = "Common Functions >" . __FUNCTION__ . ' : '
                        . $e->getMessage();
                    $ip = '-';
                    $username = '-';
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return $e->getMessage();
                }
            }
        }
        // Check sorting parameters for Table
        if ($request->sortBy && $request->sortVal) {
            // List of searchable columns for a specific model (i.e.
            // primary/foreign keys will not be included in the search)
            $columns = $model->getModel()->getSearchableColumns();
            // List of relations that will be used for searching (e.g. searching
            // FLOOR_NAME from a "belongs to floor" relation)
            $relations = explode('>', $request->include);
            try {
                if (in_array($request->sortBy, $columns)) {
                    $model = $model->orderBy(
                        $request->sortBy,
                        $request->sortVal
                    );
                } else {
                    // Search sort parameter through each relation
                    foreach ($relations as $relation) {
                        if (!$relation) {
                            $columns = ($model->getModel()->first())->$relation
                                ->getSearchableColumns();
                        }
                        if (in_array($request->sortBy, $columns)) {
                            $model = $model->orderByJoin($relation . '.'
                                . $request->sortBy, $request->sortVal);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return $e->getMessage();
            }
        }
        // Input Limit af the data needed
        if ($request->LIMIT) {
            try {
                $model = $model->take($request->LIMIT);
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return $e->getMessage();
            }
        }
        // Check REG_FLAG
        if ($request->REG_FLAG) {
            try {
                $model = $model->where('REG_FLAG', $request->REG_FLAG);
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return $e->getMessage();
            }
        }
        // Check the Manufacturer
        if ($request->manufacturerID) {
            try {
                $model = $model->where(
                    'MANUFACTURER_ID',
                    $request->manufacturerID
                );
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return $e->getMessage();
            }
        }
        // Create a simple query for the to show specific data
        if ($request->filter) {
            $urlData = explode("|", $request->filter);
            foreach ($urlData as $value) {
                $value = explode(":", $value);
                foreach ($value as $mValue) {
                    $mValue = explode(",", $mValue);
                }
                $model = $model->whereIn($value[0], $mValue);
            }
        }
        // Check pagination parameters
        if ($request->pageLength) {
            try {
                $model = $model->paginate($request->pageLength);
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs($type, $instructionType, $content, $ip, $username);
                return $e->getMessage();
            }
        } else {
            // Check if single document or collection
            try {
                $model = $id ? $model->findOrFail($id) : $model = $model->get();
            } catch (\Exception $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $content = "Common Functions >" . __FUNCTION__ . ' : '
                    . $e->getMessage();
                $ip = '-';
                $username = '-';
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return $e->getMessage();
            }
        }
        return $model;
    }

    /**
     * <Layer Number> (8.0)
     * <Processing Name> broadcastNewData<br>
     * <Function> Broadcast new data to frontend<br>
     *
     * @param object $device
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function broadcastNewData(object $device)
    {
        $ip = '-';
        $host = '-';
        $module = 'Common Functions ' . __FUNCTION__;
        try {
            $eventData = Device::with('deviceBindings')
                ->findOrFail($device->DEVICE_ID);
            event(new DeviceCommandEvent($eventData));
        } catch (\Exception $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $content = "Common Functions >" . __FUNCTION__ . ' : '
                . $e->getMessage();
            $ip = '-';
            $username = '-';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (9.0)
     * <Processing Name> insertDevicetoNotification<br>
     * <Function> Insert Device Command to Notification<br>
     *
     * @param object $device
     * @param bool $isBroadcast
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function insertDevicetoNotification(
        object $device,
        bool $isBroadcast = false
    ) {
        // Notification for Sensor
        // Check for Sensor with Status Data
        // Notification for Device Command
        try {
            $onlineFlag = $device->ONLINE_FLAG;
            if ($onlineFlag == 1) {
                $deviceType = $device->DEVICE_TYPE;
                switch ($deviceType) {
                    case 'h2o_detector':
                    case 'gas_detector':
                    case 'panic_button':
                    case 'smoke_detector':
                        if (isset($device->DATA['status'])) {
                            if ($device->DATA['status'] == 1) {
                                Self::processNotification(
                                    $device,
                                    'detected',
                                    $isBroadcast
                                );
                            }
                        }
                        break;
                    case 'multi_detector':
                        if (isset($device->DATA['status_motion'])) {
                            if ($device->DATA['status_motion'] == 1) {
                                Self::processNotification(
                                    $device,
                                    'detected',
                                    $isBroadcast
                                );
                            }
                        }
                        break;
                    case 'motion_detector':
                        if (isset($device->DATA['status'])) {
                            if ($device->DATA['status'] == 1) {
                                Self::processNotification(
                                    $device,
                                    'motion',
                                    $isBroadcast
                                );
                            }
                        }
                        break;
                    case 'dust_detector':
                        if (isset($device->DATA['status'])) {
                            Self::processNotification(
                                $device,
                                'detected',
                                $isBroadcast
                            );
                        }
                        break;
                    case 'wall_switch_1':
                    case 'wall_switch_2':
                    case 'wall_switch_3':
                    case 'ir_remote':
                    case 'door_lock':
                        Self::processNotification(
                            $device,
                            'instruct',
                            $isBroadcast
                        );
                        break;
                    case 'door_lock':
                        Self::processNotification(
                            $device,
                            'lowBattery',
                            $isBroadcast
                        );
                        break;
                }

                //Netvox Devices
                switch ($deviceType) {

                    case 'occupancy_temp_light':

                        break;
                    case 'co2_temp_humid':
                        if (isset($device->DATA['co2'])) {
                            if ($device->DATA['co2'] > 300) {
                                Self::processNotification($device, 'co2', $isBroadcast);
                            }
                        }
                        break;
                    case 'window_door_sensor':

                        break;
                    case 'emergency_button':

                        break;
                    default:
                        break;
                }
            } elseif ($onlineFlag == 0) {
                Self::processNotification($device, 'offline', $isBroadcast);
            }
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = __FUNCTION__;
            $content = $uri . " : " . $e->getMessage();
            $ip = '-';
            $username = "DEVICE_ID" . ":" . $device->DEVICE_ID;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (10.0)
     * <Processing Name> processNotification<br>
     * <Function> Process Notification command from processNotification<br>
     *
     * @param object $device
     * @param string $mode
     * @param bool $isBroadcast
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function processNotification(
        object $device,
        string $mode,
        bool $isBroadcast = false
    ) {
        $objectName = "";
        $subject = "";
        $content = "";
        $errorFlag = 1;
        $url = request()->getSchemeAndHttpHost() . '/floor-view' . '?floor=' .
            $device->FLOOR_ID . '&room=' . $device->ROOM_ID . '&device=' .
            $device->DEVICE_ID;
        $onlineFlag = $device->ONLINE_FLAG;
        $newRequest = new Request();
        try {
            $room = Room::where('ROOM_ID', $device->ROOM_ID)
                ->with('floor')
                ->first();
            if ($mode == 'detected') {
                $objectName = "Sensor Detected";
                $subject = $device->DEVICE_NAME . " was triggered.";
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 3;
            } elseif ($mode == 'instruct') {
                $objectName = "Device Detected";
                $subject = $device->DEVICE_NAME . " was triggered.";
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 1;
            } elseif ($mode == 'offline') {
                $objectName = "Device/Sensor Detected";
                $subject = $device->DEVICE_NAME . " was offline.";
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 4;
            } elseif ($mode == 'motion') {
                $objectName = "Device Detected";
                $subject = $device->DEVICE_NAME . " was triggered.";
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 1;
            } elseif ($mode == 'lowBattery') {
                $objectName = "Sensor/Device Detected";
                $subject = $device->DEVICE_NAME . " need to change the battery.";
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 2;
                /* <!--Edited: TP Uddin 2020.09.22> */
                // Video Motion Detection
            } elseif ($mode == 'videoMotionDetection') {
                $objectName = 'Camera Detected';
                $subject = $device->DEVICE_NAME . ' detected motion.';
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 1;
                // Line Crossing Detection
            } elseif ($mode == 'crossLineDetection') {
                $objectName = 'Camera Detected';
                $subject = $device->DEVICE_NAME . ' detected line crossing.';
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                $errorFlag = 1;
                // Camera Tampering Alert
            } elseif ($mode == 'cameraTamperingDetection') {
                $objectName = 'Camera Detected';
                $subject = $device->DEVICE_NAME . ' detected camera tampering.';
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
                // Lost Camera Connection Alert
            } elseif ($mode == 'lostConnection') {
                $objectName = 'Camera Detected';
                $subject = $device->DEVICE_NAME . ' lost connection.';
                $content = 'in ' . $room->ROOM_NAME . ' on the '
                    . $room->floor->FLOOR_NAME;
            }
            /* <Edited --!> */ elseif ($mode == 'co2') {
                $objectName = 'CO2 Detected';
                $subject = $device->DEVICE_NAME . ' level is rising.';
                $content = 'in ' . $room->ROOM_NAME . ' on the ' . $room->floor->FLOOR_NAME;
                $errorFlag = 3;
            }
            // Collect Data to send in Notification
            $newRequest->replace([
                "OBJECT_NAME" => $objectName,
                "ROOM_ID" => $device->ROOM_ID,
                "SUBJECT" => $subject,
                "CONTENT" => $content,
                "ERROR_FLAG" => $errorFlag,
                "NOTIFICATION_LINK" => $url,
                "GATEWAY_ID" => $device->GATEWAY_ID,
                "ROOM_MAP_DATA" => $room->ROOM_MAP_DATA
            ]);
            app('App\Http\Controllers\NotificationController')
                ->createNotification($newRequest, $isBroadcast);
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = __FUNCTION__;
            $content = $uri . " : " . $e->getMessage();
            $ip = '-';
            $username = "DEVICE_ID" . ":" . $device->DEVICE_ID;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (11.0)
     * <Processing Name> decryptMessage
     * <Function> Decrypts a message
     *
     * @param string $message
     * @return false|string $decrypted|'failed'
     */
    public function decryptMessage(array $message)
    {
        $decrypted = \openssl_decrypt(
            $message['content'],
            'AES-256-CBC',
            base64_decode(substr(env('APP_KEY'), 7)),
            0,
            base64_decode($message['iv'])
        );
        if ($decrypted === false) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = __FUNCTION__;
            $content = $uri . " : ";
            $ip = '-';
            $username = 'System';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
        return $decrypted;
    }

    /**
     * <Layer Number> (12.0)
     * <Processing Name> isJson<br>
     * <Function> Check if Valid JSON<br>
     *
     * @param string $str
     * @return bool $json && $str != $json
     */
    public function isJson(string $str)
    {
        $json = json_decode($str);
        return $json && $str != $json;
    }

    /**
     * <Layer Number> (13.0)
     * <Processing Name> newInstructionRequest<br>
     * <Function> set new Instruction to devices<br>
     *
     * @param int $gateway_id
     * @param int $device_id
     * @param string $command
     * @param string $value
     * @param string $addVal
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function newInstructionRequest(
        int $gateway_id,
        int $device_id,
        string $command,
        string $value,
        string $addVal
    ) {
        $newRequest = new Request();
        $newRequest->replace([
            'GATEWAY_ID' => $gateway_id,
            'DEVICE_ID' => $device_id,
            'COMMAND' => $command,
            'VALUE' => $value,
            'TYPE' => "Automatic",
            'addlValue' => $addVal
        ]);
        try {
            app('App\Http\Controllers\InstructionController')
                ->sendInstruction($newRequest);
        } catch (\Exception $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = "newInstructionRequest";
            $content = $uri . " : " . $e->getMessage();
            $ip = "-";
            $username = "-";
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (14.0)
     * <Processing Name> deleteDevicePlot<br>
     * <Function> Delete Device Plotting from Floor Table Data<br>
     *
     * @param object $device
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteDevicePlot(object $device)
    {
        // Get Rooms and Floors based on Device
        try {
            $query = Device::find($device['DEVICE_ID'])
                ->room()
                ->with('floor')
                ->first();
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = __FUNCTION__;
            $content = $uri . " : " . $e->getMessage();
            $ip = '-';
            $username = "DEVICE_ID" . ":" . $device->DEVICE_ID;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return 'failed';
        }
        $floorMapData = $query['floor']['FLOOR_MAP_DATA'];
        $rooms = '';
        // Delete Floor Device Coordinates on Floor Table
        foreach ($query['floor']['FLOOR_MAP_DATA']['roomMap'] as
            $key => $floorRooms) {
            if ($query['ROOM_MAP_DATA']['ROOM_MAP'] == $floorRooms['roomMap']) {
                foreach ($floorRooms['deviceCoor'] as
                    $deviceCoorKey => $deviceCoordinates) {
                    if ($deviceCoordinates['name'] == $device['DEVICE_MAP_NAME']) {
                        unset($floorMapData['roomMap'][$key]['deviceCoor'][$deviceCoorKey]);
                        $floorMapData['roomMap'][$key]['deviceCoor'] =
                            array_merge($floorMapData['roomMap'][$key]['deviceCoor']);
                        try {
                            $floor = Device::find($device['DEVICE_ID'])
                                ->floor()->first();
                            $floor->FLOOR_MAP_DATA = $floorMapData;
                            $floor->save();
                        } catch (\Throwable $e) {
                            // Insert System Logs
                            $type = '3';
                            $instructionType = 'System Error';
                            $uri = __FUNCTION__;
                            $content = $uri . " : " . $e->getMessage();
                            $ip = '-';
                            $username = "DEVICE_ID" . ":" . $device->DEVICE_ID;
                            $this->storeLogs(
                                $type,
                                $instructionType,
                                $content,
                                $ip,
                                $username
                            );
                            return 'failed';
                        }
                        break;
                    }
                }
            }
        }
    }

    /**
     * <Layer Number> (15.0)
     * <Processing Name> storeDeviceData<br>
     * <Function> Store Device Data<br>
     *
     * @param int $devId
     * @param string $type
     * @param array $deviceData
     * @param array $data
     * @return object $deviceData
     * @throws Throwable When an exception occurs in this process
     */
    public function storeDeviceData(
        int $devId,
        string $type,
        array $deviceData,
        array $data
    ) {
        $retArr = [];
        if ($type == 'ir_remote') {
            try {
                if (!empty($deviceData)) {
                    $retArr = $deviceData;
                    foreach ($deviceData as $key => $devData) {
                        if ($devData['brand'] == $data[0]['brand']) {
                            $temp = array_replace_recursive(
                                $retArr[$key],
                                $data[0]
                            );
                            $deviceData[$key] = $temp;
                        }
                    }
                    return $deviceData;
                }
            } catch (\Throwable $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $uri = __FUNCTION__;
                $content = $uri . " : " . $e->getMessage();
                $ip = '-';
                $username = "DEVICE_ID" . ":" . $devId;
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return 'failed';
            }
        } else {
            return array_replace_recursive($deviceData, $data);
        }
    }

    /**
     * <Layer Number> (16.0)
     * <Processing Name> storeLogs<br>
     * <Function> Store System Logs<br>
     *
     * @param int $type
     * @param string $instruction_type
     * @param string $content
     * @param string $ip
     * @param string $host
     * @return string
     * @throws Throwable When an exception occurs in this process
     */
    public function storeLogs(
        int $type,
        string $instruction_type,
        string $content,
        string $ip,
        string $host
    ) {
        /**
         *                    "Instruction Type"
         * 0       Emergency - System Failure
         * 1       Alert     - Hardware Contact Failures
         * 2       Critical  - Emergency Device Alert
         * 3       Error     - System Error
         * 4       Warning   - Manual, Automatic
         * 5       Notice    - Normal
         */
        try {
            $logs = new Logs();
            $logs->TYPE = $type;
            $logs->INSTRUCTION_TYPE = $instruction_type;
            $logs->CONTENT = $content;
            $logs->IP = $ip;
            $logs->HOST = $host;
            $logs->save();


            if (env("ERROR_NOTIFICATION_ALERT") == 1) {
                //Send SMS and Email to Tensei Office
                //Send only logs that has Instruction type of 0, 1, 2 and 3
                if (in_array((int)$instruction_type, [0, 1, 2, 3, 4])) {
                    //send email instruction
                    $sEmailAddSender    = env('MAIL_FROM_ADDRESS');
                    $sEmailAdd          = env('MAIL_TO_ADDRESS');
                    $sEmailSubj         = "❗❗ iBMS System Error ❗❗";
                    $sType              = $instruction_type;
                    $sInstructionType   = $instruction_type;
                    $sContent           = $content;              //Body
                    $sIP                = $ip;
                    $sHost              = $host;

                    $data = [
                        'emailAdd' => $sEmailAddSender,
                        'subject'   => $sEmailSubj,
                        'type'      => $sInstructionType,
                        'content'   => $sContent,
                        'ip'        => $sIP,
                        'host'      => $sHost
                    ];

                    Mail::to($sEmailAdd)->send(new NotificationMail($data));

                    //send sms instruction
                    $sUsername  = "-";
                    $iContactNo = env("SMS_RECEIVER");
                    $sMessage   =  " \n ❗❗ iBMS System Logs Notification ❗❗ \n\n" .
                        "Type: " . $sType . "\n" .
                        "Instruction Type: " . $sInstructionType . "\n" .
                        "Content: " . $sContent . "\n" .
                        "IP: " . $sIP . "\n" .
                        "Host: " . $sHost . "\n";

                    //send sms instruction
                    app('App\Http\Controllers\InstructionController')->sendAlertSMS($sUsername, $iContactNo, $sMessage);
                }
            }
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (17.0)
     * <Processing Name> auditLogs<br>
     * <Function> Audit Trail Logs<br>
     *
     * @param string $ip
     * @param string $host
     * @param string $module
     * @param string $instruction
     * @return
     * @throws Throwable When an exception occurs in this process
     */
    public function auditLogs(
        string $ip,
        string $host,
        string $module,
        string $instruction
    ) {
        try {
            $auditLogs = new AuditLogs();
            $auditLogs->IP = $ip;
            $auditLogs->HOST = $host;
            $auditLogs->MODULE = $module;
            $auditLogs->INSTRUCTION = $instruction;
            $auditLogs->save();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (18.0)
     * <Processing Name> currentTime<br>
     * <Function> Set Current Time<br>
     *
     * @return false|int strtotime(date("Y-m-d H:i:s"))
     */
    public function currentTime()
    {
        return strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * <Layer Number> (19.0)
     * <Processing Name> storeIR<br>
     * <Function> store IR data<br>
     *
     * @param int $devId
     * @param string $type
     * @param array $deviceData
     * @param array $data
     * @return array|string $retArr|$deviceData
     * @throws Throwable When an exception occurs in this process
     */
    public function storeIR(
        int $devId,
        string $type,
        array $deviceData,
        array $data
    ) {
        $retArr = [];
        if ($type == 'ir_remote') {
            $applianceType = Appliances::where(
                'APPLIANCE_ID',
                $data['APPLIANCE_ID']
            )
                ->select('APPLIANCE_TYPE')
                ->first();
            $brand = Appliances::where('APPLIANCE_ID', $data['APPLIANCE_ID'])
                ->select('BRAND_NAME')
                ->first();
            try {
                if (empty($deviceData)) {
                    $nData = [
                        'type' => $applianceType['APPLIANCE_TYPE'],
                        'brand' => $brand['BRAND_NAME'],
                        'status' => '0',
                        'temp_value' => '25',
                        'aircon_power' => true
                    ];
                    array_push($retArr, $nData);
                    return $retArr;
                } else {
                    foreach ($deviceData as $devData) {
                        if (
                            $devData['type'] == $applianceType['APPLIANCE_TYPE']
                            && $devData['brand'] == $brand['BRAND_NAME']
                        ) {
                            return $deviceData;
                        }
                    }
                    $nData = [
                        'type' => $applianceType['APPLIANCE_TYPE'],
                        'brand' => $brand['BRAND_NAME'],
                        'status' => '0',
                        'temp_value' => '25',
                        'aircon_power' => true
                    ];
                    array_push($deviceData, $nData);
                    return $deviceData;
                }
            } catch (\Throwable $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $uri = __FUNCTION__;
                $content = $uri . " : " . $e->getMessage();
                $ip = '-';
                $username = "DEVICE_ID" . ":" . $devId;
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return 'failed';
            }
        } else {
            return array_replace_recursive($deviceData, $data);
        }
    }

    /**
     * <Layer Number> (20.0)
     * <Processing Name> sendRequestToPython<br>
     * <Function> Send HTTP request to Python and return the parsed response<br>
     *
     * @param string $url
     * @param array $data
     * @return array json_decode($output, true)
     * @throws Throwable When an exception occurs in this process
     */
    private function sendRequestToPython(string $acsIp, string $url, array $data = [])
    {
        $urlComplete = 'https://' . $acsIp . ':' . env('ACS_API_PORT') .
            '/Acs/Api/' . $url;
        $acsCredentials = base64_encode(json_encode([
            'username' => env('ACS_USERNAME'),
            'password' => env('ACS_PASSWORD')
        ]));
        $urlCompleteEncoded = base64_encode($urlComplete);
        if ($data == []) {
            $dataJsonEncoded = 'noBody';
        } else {
            $dataJsonEncoded = base64_encode(json_encode($data));
        }
        $command = escapeshellcmd(env('EXECUTE_PYTHON_REQUEST') . ' ' .
            $acsCredentials . ' ' . $urlCompleteEncoded . ' ' . $dataJsonEncoded);
        $output = shell_exec($command);
        return json_decode($output, true);
    }

    /**
     * <Layer Number> (21.0)
     * <Processing Name> getAcsCameraList<br>
     * <Function> Call GetCameraList ACS API<br>
     *
     * @param string $acsIp
     * @return object $response
     */
    public function getAcsCameraList(string $acsIp)
    {
        $numberOfElements = 0;
        do {
            $numberOfElements += 100;
            $url = 'CameraListFacade/GetCameraList';
            $body = [
                'range' => [
                    'StartIndex' => 0,
                    'NumberOfElements' => $numberOfElements
                ]
            ];
            $response = $this->sendRequestToPython($acsIp, $url, $body);
        } while (!$response['ContainsLastCamera']);
        return $response;
    }

    /**
     * <Layer Number> (22.0)
     * <Processing Name> getAcsServerConfiguration<br>
     * <Function> Call GetServerConfiguration ACS API<br>
     *
     * @param string $acsIp
     * @return $response
     */
    public function getAcsServerConfiguration(string $acsIp)
    {
        $url = 'ServerConfigurationFacade/GetServerConfiguration';
        $response = $this->sendRequestToPython($acsIp, $url);
        return $response;
    }

    /**
     * <Layer Number> (23.0)
     * <Processing Name> getAcsSystem<br>
     * <Function> Call GetSystem ACS API<br>
     *
     * @param string $acsIp
     * @return object $response
     */
    public function getAcsSystem(string $acsIp)
    {
        $url = 'SystemFacade/GetSystem';
        $response = $this->sendRequestToPython($acsIp, $url);
        return $response;
    }

    public function listCameraApplications(string $cameraIp)
    {
        // AXIS Camera API: List installed applications
        // Documentation: https://www.axis.com/vapix-library/subjects/t10102231/section/t10036126/display?section=t10036126-t10010644
        $url = "http://" . $cameraIp . "/axis-cgi/applications/list.cgi";

        // Request API using cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_USERPWD => env("CAMERA_USERNAME") . ':' . env("CAMERA_PASSWORD")
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        // Convert XML to JSON format
        $p = xml_parser_create();
        xml_parse_into_struct($p, $response, $vals, $index);
        xml_parser_free($p);

        // List all applications with the name, niceName and status attributes
        $arr = [];
        foreach ($vals as $key) {
            if ($key['tag'] == 'APPLICATION') {
                array_push($arr, [
                    "name" => $key['attributes']['NAME'],
                    "niceName" => $key['attributes']['NICENAME'],
                    "status" => $key['attributes']['STATUS']
                ]);
            }
        }
        return $arr;
    }

    /**
     * <Layer number> (24.0)
     *
     * <Processing name> Get AXIS People Counter real-time data<br>
     * <Function name> Trigger AXIS API to retrieve real-time data of PC<br>
     *
     * @param int $id
     * @return array|string json_decode($response, true)
     */
    public function getPcRealTimeData(int $id)
    {
        // Get Device
        $camera = Device::find($id);

        /**
         * @api AXIS People Counter: Request real-time data
         * @see https://www.axis.com/vapix-library/subjects/t10102231/section/t10128199/display?section=t10128199-t10102308
         * @uses CameraController@postPcData
         */
        $url = "http://" . $camera->DATA[0]['Address'] . "/local/people-counter/.api?live-sum.json";

        // Request API using cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_USERPWD => env("CAMERA_USERNAME") . ':' . env("CAMERA_PASSWORD")
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        // Convert string to JSON format then return
        return json_decode($response, true);
    }

    /**
     * <Layer number> (25.0)
     *
     * <Processing name> Clear People Counter data for today<br>
     * <Function name> Trigger AXIS API to reset PC data<br>
     *
     * @param int $id
     * @return string|object $response
     */
    public function clearPcData(int $id)
    {
        // Get Device
        $camera = Device::find($id);

        /**
         * @api AXIS People Counter: Clear counting data
         * @see https://www.axis.com/vapix-library/subjects/t10102231/section/t10128199/display?section=t10128199-t10102302
         * @uses CameraController@resetCountData
         */
        $url = "http://" . $camera->DATA[0]['Address'] . "/local/people-counter/.apioperator?clear-data";

        // Request API using cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
            CURLOPT_USERPWD => env("CAMERA_USERNAME") . ':' . env("CAMERA_PASSWORD")
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * <Layer number> (26.0)
     *
     * <Processing Name> getArchibusAccessToken<br>
     * <Function> Get Access Token for Archibus API<br>
     *
     * @param int $api_id
     * @param string $auth_code Optional
     * @return string json_decode($response)->access_token
     */
    public function getAccessToken(int $api_id, string $auth_code = null)
    {
        // API Info
        $apiInfo = Api::find($api_id);
        // Token Info
        $tokenInfo = ApiToken::where('API_ID', $apiInfo->API_ID)->first();
        switch ($apiInfo->GRANT_TYPE) {
                // Client Credentials process
            case 'client_credentials':
                // If there is no token yet, request one
                if (!$tokenInfo) {
                    $content = "grant_type=client_credentials";
                    $authorization = base64_encode("$apiInfo->CLIENT_ID:$apiInfo->CLIENT_SECRET");
                    $header = [
                        "Authorization: Basic {$authorization}",
                        "Content-Type: $apiInfo->CONTENT_TYPE"
                    ];
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $apiInfo->TOKEN_URL,
                        CURLOPT_HTTPHEADER => $header,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $content
                    ]);
                    $response = json_decode(curl_exec($curl));
                    curl_close($curl);

                    // Calculate time of expiration
                    $expiresIn = $response->expires_in - 10;
                    // compensate for processing time
                    $expiredAt = date('Y-m-d H:i:s', strtotime("$expiresIn seconds"));

                    // Insert new Token
                    $newTokenInfo = new ApiToken();
                    $newTokenInfo->API_ID = $apiInfo->API_ID;
                    $newTokenInfo->TOKEN_NAME = $apiInfo->API_NAME;
                    $newTokenInfo->ACCESS_TOKEN = $response->access_token;
                    $newTokenInfo->REFRESH_TOKEN = null;  // Confirm later what to put
                    $newTokenInfo->EXPIRED_AT = $expiredAt;
                    $newTokenInfo->save();

                    // If token is expired
                } elseif (($tokenInfo->EXPIRED_AT < date("Y-m-d H:i:s")) ? true : false) {
                    $content = "grant_type=client_credentials";
                    $authorization = base64_encode("$apiInfo->CLIENT_ID:$apiInfo->CLIENT_SECRET");
                    $header = [
                        "Authorization: Basic {$authorization}",
                        "Content-Type: $apiInfo->CONTENT_TYPE"
                    ];
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $apiInfo->TOKEN_URL,
                        CURLOPT_HTTPHEADER => $header,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $content
                    ]);
                    $response = json_decode(curl_exec($curl));
                    curl_close($curl);

                    // Calculate time of expiration
                    $expiresIn = $response->expires_in;
                    $expiredAt = date('Y-m-d H:i:s', strtotime("$expiresIn seconds"));

                    // Update token info
                    $tokenInfo->ACCESS_TOKEN = $response->access_token;
                    $tokenInfo->REFRESH_TOKEN = null;
                    $tokenInfo->EXPIRED_AT = $expiredAt;
                    $tokenInfo->save();
                }
                return ApiToken::where('API_ID', $apiInfo->API_ID)
                    ->first()
                    ->ACCESS_TOKEN;
                break;

                // Authorization Code process
            case 'authorization_code':
                // insert process for authorization code
                $content = [
                    "grant_type" => "authorization_code",
                    "client_id" => $apiInfo->CLIENT_ID,
                    "client_secret" => $apiInfo->CLIENT_SECRET,
                    "redirect_uri" => $apiInfo->REDIRECT_URL,
                    "code" => $auth_code,
                ];
                $authorization = base64_encode("$apiInfo->CLIENT_ID:$apiInfo->CLIENT_SECRET");
                $header = [
                    "Authorization: Basic {$authorization}",
                    "Content-Type: $apiInfo->CONTENT_TYPE"
                ];

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $apiInfo->TOKEN_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => http_build_query($content),
                    CURLOPT_HTTPHEADER => $header,
                ]);

                $response = json_decode(curl_exec($curl), true);

                curl_close($curl);

                return $response;
                break;

                // Error case
            default:
                Log::info('Error case');
                return '';
                break;
        }
    }

    /**
     * <Layer number> (27.0)
     *
     * <Processing Name> getArchibusAccessToken<br>
     * <Function> Get Access Token for Archibus API<br>
     *
     * @param string $remote_id
     * @return string $tokenInfo->ACCESS_TOKEN
     */

    public function getAccessTokenForNetvoxGateway(string $remote_ip)
    {
        // Get Access Token
        $tokenInfo = ApiToken::where('TOKEN_NAME', $remote_ip)->first();
        // No token information
        if (!$tokenInfo) {
            // Generate new token
            try {
                $newTokenInfo = Http::withHeaders([
                    'Accept' => 'application/vnd.kerlink.iot-v1.json',
                    'Content-Type' => 'application/vnd.kerlink.iot-v1.json'
                ])->timeout(1)
                    ->post("http://$remote_ip/application/administration/login", [
                        'login' => env('NETVOX_USERNAME'),
                        'password' => env('NETVOX_PASSWORD')
                    ])
                    ->json();

                $netVoxToken = new ApiToken();
                $netVoxToken->API_ID = 5;
                $netVoxToken->TOKEN_NAME = $remote_ip;
                $netVoxToken->ACCESS_TOKEN = $newTokenInfo['token'];
                //change epoch time to standard time
                $epoch = $newTokenInfo['expiration_date'];
                $dt = new DateTime("@$epoch");
                $netVoxToken->EXPIRED_AT = $dt->format('Y-m-d H:i:s');
                $netVoxToken->save();
                return $newTokenInfo['token'];
            } catch (\Throwable $e) {
                return 'failed';
            }

            // Token is expired
        } elseif ($tokenInfo->EXPIRED_AT < date('Y-m-d H:i:s')) {
            $newTokenInfo = Http::withHeaders([
                'Accept' => 'application/vnd.kerlink.iot-v1.json',
                'Content-Type' => 'application/vnd.kerlink.iot-v1.json'
            ])->timeout(1)
                ->post("http://$remote_ip/application/administration/login", [
                    'login' => env('NETVOX_USERNAME'),
                    'password' => env('NETVOX_PASSWORD')
                ])
                ->json();
            $tokenInfo->ACCESS_TOKEN = $newTokenInfo['token'];
            $epoch = $newTokenInfo['expiration_date'];
            $dt = new DateTime("@$epoch");
            $tokenInfo->EXPIRED_AT = $dt->format('Y-m-d H:i:s');
            $tokenInfo->save();
            // Token is valid
        } else {
            // Use access token
            return $tokenInfo->ACCESS_TOKEN;
        }
        return $tokenInfo->ACCESS_TOKEN;
    }

    public function appLog(int $user_id, string $content, string $event)
    {
        try {
            $appLog = new AppLogs();
            $appLog->USER_ID = $user_id;
            $appLog->CONTENT = $content;
            $appLog->EVENT = $event;
            $appLog->save();
        } catch (\Throwable $e) {
            Log::info('APPLOG');
            Log::info($e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer Number> (28.0)
     * <Processing Name> saveLogs<br>
     * <Function> Store System Logs<br>
     * @param string $errorType
     * @param string $logLevel
     * @param string $events
     * @param string $uri
     * @param string $host
     * @param string $processObject
     * @param string $processDetails
     * @param int $errorCode
     * @throws Throwable When an exception occurs in this process
     */
    public function saveLogs(
        string $errorType,
        string $logLevel,
        string $events,
        string $uri,
        string $host,
        string $processObject,
        string $processDetails,
        int $errorCode
    ) {
        try {
            $logs = new SaveLog();
            $logs->ERROR_TYPE = $errorType;
            $logs->LOG_LEVEL = $logLevel;
            $logs->EVENTS = $events;
            $logs->REQUEST_TARGET = $uri;
            $logs->USER_ID = $host;
            $logs->PROCESSING_OBJECT = $processObject;
            $logs->PROCESSING_DETAILS = $processDetails;
            $logs->ERROR_CODE = $errorCode;
            // Save information to table
            $logs->save();
        } catch (\Throwable $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * <Layer number> (29.0)
     *
     * <Processing Name> processError<br>
     * <Function> Process the Error<br>
     *
     * @param string $th
     */

    public function processError(string $uri, Throwable $th)
    {
        // If error message is 0 (known)
        if (substr($th->getMessage(), 0, 1) === '0') {
            // convert error code to string
            $errorCode = (string) $th->getCode();

            // switch case for log level
            switch (substr($errorCode, 0, 1)) {
                case '1':
                    $logLevel = 'FATAL';
                    break;
                case '2':
                    $logLevel = 'ERROR';
                    break;
                case '3':
                    $logLevel = 'WARN';
                    break;
                case '4':
                    $logLevel = 'INFO';
                    break;
                case '5':
                    $logLevel = 'DEBUG';
                    break;
            }

            // switch case for error type
            switch (substr($errorCode, 1, 1)) {
                case '0':
                    $errorType = 'None';
                    break;
                case '1':
                    $errorType = 'System Error';
                    break;
                case '2':
                    $errorType = 'Business Error';
                    break;
                case '3':
                    $errorType = 'Security Error';
                    break;
                case '4':
                    $errorType = 'Unknown Error';
                    break;
            }

            // switch case for error code
            switch (substr($errorCode, 2, 3)) {
                case '001':
                    info('enter C001');
                    $events = 'Check-in';
                    break;
                case '002':
                    $events = 'Check-out';
                    break;
                case '003':
                    $events = 'Room Operation';
                    break;
                case '004':
                    $events = 'Room Service';
                    break;
                case '005':
                    $events = 'Hardware Connection';
                    break;
                case '006':
                    $events = 'User Management';
                    break;
            }

            // Error Content
            $processDetails = substr($th->getMessage(), 2);
        } else {
            // If error is still unknown
            $errorCode = '000000000';
            $errorType = 'Unknown Error';
            $logLevel = '--';
            $events = '--';
            $processDetails = $th->getMessage();
        }

        // User ID
        $host = auth()->user()->USER_ID;
        // Program name
        $processFile = basename($th->getFile());
        // Program name with code line
        $processObject = $processFile . ' line:' . $th->getLine();
        // Save to saveLogs Function
        $this->saveLogs(
            $errorType,
            $logLevel,
            $events,
            $uri,
            $host,
            $processObject,
            $processDetails,
            $errorCode
        );
    }

    /**
     * <Layer number> (29.0)
     *
     * <Processing Name> logsNotification<br>
     * <Function> Create a log for notification<br>
     *
     * @param  string  $message_id
     * @param  int|null  $room_id
     * @param  int  $event_status
     * @param  int|null  $reservation_id
     * @return string|void
     */
    public function logsNotification(
        string $message_id,
        ?int $room_id,
        int $event_status,
        ?int $reservation_id = null
    ) {
        try {
            $errorsToBeReportedOnce = ['E002', 'E003', 'E004', 'E005', 'E006', 'E007'];

            if (in_array($message_id, $errorsToBeReportedOnce)) {
                $log = LogsNotification::updateOrCreate([
                    'MESSAGE_ID' => $message_id,
                    'ROOM_ID' => $room_id,
                    'RESERVATION_ID' => $reservation_id,
                    'EVENT_STATUS' => $event_status
                ]);
            } else {
                $log = LogsNotification::create([
                    'MESSAGE_ID' => $message_id,
                    'ROOM_ID' => $room_id,
                    'RESERVATION_ID' => $reservation_id,
                    'EVENT_STATUS' => $event_status
                ]);
            }

            $infoToBeNotified = ['I007', 'I008', 'I009', 'I010'];
            if (
                in_array($event_status, [0, 2]) ||
                ($event_status == 4 && in_array($message_id, $infoToBeNotified))
            ) {
                event(new LogsNotificationEvent($log));
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
