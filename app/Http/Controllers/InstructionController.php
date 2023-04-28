<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\Device;
use App\Models\Floor;
use App\Models\Gateway;
use App\Models\Room;
use App\Traits\CommonFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

/**
 * <Class Name> InstructionController
 *
 * <Function Name> Instruction Processing<br>
 * Create : 2018.06.20 TP Bryan<br>
 * Update : 2018.06.21 TP Bryan    Edited variable name for 1.0
 *          2018.06.26 TP Bryan    Edited variable name for 1.0
 *          2018.06.27 TP Bryan    Edited variable name for 1.0
 *          2018.06.28 TP Bryan    Edited send message for 1.0, fixed comments
 *          2018.06.29 TP Bryan    Edited send message for 1.0 added socket timeout
 *          2018.07.19 TP Bryan    Added sample encryption
 *          2018.07.20 TP Bryan    Renamed "Instructions" to "Instruction"
 *          2018.12.18 OJT Jethro  Added code for Logs
 *          2019.12.12 TP Ivin
 *          2020.05.13 TP Uddin    Implement coding standard for PHP7
 *          2020.05.20 TP Uddin    Modify URL and Method names according to URL list
 *          2020.06.22 TP Harvey   Add validation in $request->addlValue<br>
 *
 * <Overview> This controller is responsible for what instruction will be use
 *             to a gateway.
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class InstructionController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // sendInstruction                  (1.0) Send instruction to gateway

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Send Instruction<br>
     * <Function> Send instruction to gateway<br>
     *            URL: http://localhost/sendInstruction<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string $sRet
     * @throws Throwable When an exception occurs in this process
     */
    public function sendInstruction(Request $request)
    {
        $Test_Flag = env('TEST_FLAG');
        try {
            $gateway = Gateway::where('GATEWAY_ID', $request->GATEWAY_ID)
                ->select('GATEWAY_IP')
                ->firstOrFail();
            $device = Device::where('DEVICE_ID', $request->DEVICE_ID)
                ->select(
                    'DEVICE_ID',
                    'DEVICE_TYPE',
                    'DEVICE_SERIAL_NO',
                    'DEVICE_NAME',
                    'ROOM_ID',
                    'FLOOR_ID',
                    'DEVICE_CATEGORY'
                )
                ->firstOrFail();
            $room = Room::where('ROOM_ID', $device->ROOM_ID)
                ->select('ROOM_NAME')
                ->firstOrFail();
            $floor = Floor::where('FLOOR_ID', $device->FLOOR_ID)
                ->select('FLOOR_NAME')
                ->firstOrFail();
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = 'Send Instruction';
            $content = $uri . " : " . $e->getMessage();
            $username = auth()->user()->USER_ID;
            $ip = $request->GATEWAY_IP;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
        // MC network details
        $remote_ip = $gateway->GATEWAY_IP;
        $remote_port = env('PORT_GATEWAY');
        // For Emergency/Normal Device
        // if($device->DEVICE_CATEGORY == 1){
        //     $remote_port = env('PORT_GATEWAY_EMERGENCY_DEVICE');
        // }else{
        //     $remote_port = env('PORT_GATEWAY_NORMAL_DEVICE');
        // }
        // Device data

        $deviceId = $device->DEVICE_SERIAL_NO;
        $deviceType = $device->DEVICE_TYPE;
        $addValue = $request->addlValue ? $request->addlValue : "";
        $deviceName = $device->DEVICE_NAME;
        $deviceInstruction = $this->convertInstruction(
            $deviceType,
            $request->COMMAND,
            $request->VALUE,
            $addValue
        );
        $data = '{"mode":"sendInstruction","device_id":"' . $deviceId .
            '","command":"' . $deviceInstruction . '"}';
        $message = $this->encryptMessage($data);
        $sRet = $this->sendToSocket($remote_ip, $remote_port, $message);
        //Event
        $event = $request->event;
        //Automated or Manual
        $instructionType = $request->TYPE;
        //Log Content
        $logMessage = 'Device: ' . $deviceName . ' Room: ' . $room->ROOM_NAME .
            ' Floor: ' . $floor->FLOOR_NAME . ' Event: ' . $event;
        //Fetch user data from session
        $username = auth()->user();
        //Fetch IP if has
        $ip = $request->ip() ? $request->ip() : '-';
        //Store Type, Instruction Type, Content, IP and Username
        if ($request->TYPE == 'Manual') {
            $type = '4';
            $this->storeLogs(
                $type,
                $instructionType,
                $logMessage,
                $ip,
                $username->USERNAME
            );
        }
        return $sRet;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Send Alert SMS<br>
     * <Function> Send SMS to user<br>
     *            URL: http://localhost/sendAlertSMS<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string $sRet
     * @throws Throwable When an exception occurs in this process
     */
    public function sendAlertSMS(string $sUsername, int $iContactNo, string $sMessage)
    {
        // Your Account SID and Auth Token from twilio.com/console
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');
        $client = new Client($sid, $token);

        $number = "+" . $iContactNo;
        $client->messages->create(
            $number,
            [
                'from' => env('TWILIO_FROM'),
                'body' => $sMessage,
            ]
        );
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Send Alert Email<br>
     * <Function> Send Email to user<br>
     *            URL: http://localhost/sendAlertEmail<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string $sRet
     * @throws Throwable When an exception occurs in this process
     */
    // public function sendAlertEmail()
    public function sendAlertEmail(string $sUsername, string $sEmailAdd, string $sEmailSubj, string $sMessage)
    {

        $data = [
            'username' => $sUsername,
            'emailAdd' => $sEmailAdd,
            'subject' => $sEmailSubj,
            'message' => $sMessage
        ];

        Mail::to($sEmailAdd)->send(new Email($data));
    }
}
