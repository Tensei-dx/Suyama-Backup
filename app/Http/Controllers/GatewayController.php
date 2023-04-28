<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\OnlineStatusEvent;
use App\Models\Device;
use App\Models\Gateway;
use App\Models\Manufacturer;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * <Class Name> GatewayController
 *
 * <Function Name> Gateway management and processing<br>
 * Create : 2018.06.26 TP Bryan<br>
 * Update : 2018.06.27 TP Bryan    Added new functions
 *          2018.06.28 TP Bryan    Fixed comments
 *          2018.06.29 TP Bryan    Edited 1.0, Added 6.0, 7.0, 8.0
 *          2018.07.02 TP Bryan    Added 9.0, 10.0
 *          2018.07.20 TP Bryan    Rearranged function sequence
 *          2018.07.23 TP Bryan    Inserted new functions, Fixed code structure
 *          2018.07.24 TP Bryan    Added "Eager load" functions for get methods
 *          2018.08.07 TP Bryan    Finalized(?) functions as endpoints
 *          2018.08.08 TP Bryan    Added relation to MANUFACTURER to functions
 *          2018.10.10 TP Harvey   Fix Encryption Sending
 *          2018.11.05 TP Robert   Add Register function for MODBUS
 *          2018.11.06 TP Robert   Update the update function
 *          2018.11.09 TP Harvey   Update the updateGateway function
 *          2018.12.20 TP Raymond  Add onlineGateway function
 *          2019.01.16 TP Harvey   Add Enable Join Function
 *          2019.07.02 TP Mark     Applying PG Implementation Matrix (Frontend)
 *          2019.07.09 TP Ivin     Checking of Hierarchy and Adding of return comments
 *          2019.07.10 TP Mark     Applying PG Horizontal Expansion
 *          2019.09.09 TP Jethro   Modified functions getRegisteredGateways, getBlockedGateways and getUnregisteredGateways for table key sorting
 *          2019.12.18 TP Harvey   Modify the bug about the functions getRegisteredGateways
 *          2020.05.14 TP Uddin    Implement coding standard for PHP7
 *          2020.05.20 TP Uddin    Modify URL and Method names according to the URL list<br>
 *
 * <Overview> This controller is responsible for managing gateways in the system.
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class GatewayController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // scanGatewayAll                   (1.0) Scan network for available Netvox and Wulian gateways
    // getGatewayAll                    (2.0) Retrieve all gateways from database
    // getGateway                       (3.0) Retrieve gateway from database
    // deleteGatewayForce               (4.0) Resetting gateway without Encryption
    // getUnregisteredGateways          (5.0) Retrieve all gateways with "unregistered" flag
    // getRegisteredGateways            (6.0) Retrieve all gateways with "registered" flag
    // getBlockedGateways               (7.0) Retrieve all gateways with "blocked" flag
    // getDeletedGateways               (8.0) Retrieve all gateways with "delete" flag
    // getGatewayDevices                (9.0) Retrieve devices associated with the gateway
    // getGatewayFloor                  (10.0) Retrieve floor associated with the gateway
    // getGatewayRoom                   (11.0) Retrieve room associated with the gateway
    // registerGateway                  (12.0) Update gateway flag to "registered"
    // updateGateway                    (13.0) Update gateway details
    // deleteGateway                    (14.0) Update gateway flag to "deleted"
    // deleteGatewayManual              (15.0) Delete gateway manually
    // undeleteGateway                  (16.0) Update gateway flag to "registered"
    // blockGateway                     (17.0) Update gateway flag to "blocked"
    // unblockGateway                   (18.0) Update gateway flag to "unregistered"
    // onlineGateway                    (19.0) Check online gateways and log offline gateway
    // createGatewayModbus              (20.0) Update gateway flag to "registered"
    // enableJoinMC                     (21.0) Enable Join Mode of MC

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Scan gateways in the network<br>
     * <Function> Scan network for available and unregistered gateways<br>
     *            URL: http://localhost/scanGatewayAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $res_arr
     * @throws Throwable When an exception occurs in this process
     */
    public function scanGatewayAll(Request $request)
    {
        // for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Gateway Management';
        $instruction = 'Scanned All the Gateway';

        $scan_arr = [];
        $res_arr = [];
        //Scan All IP Address for (Netvox Gateway)
        $arpa = shell_exec('/usr/sbin/arp -a');
        $arpExplode = explode("(", $arpa);
        foreach ($arpExplode as $loopExplode) {
            try {
                $ipExplode = explode(")", $loopExplode);
                //Filter All the IP address
                if (count(explode(".", $ipExplode[0])) == 4) {
                    $remote_ip = $ipExplode[0];
                    //Temporary IP to access 41.120
                    if ($remote_ip == '192.168.40.200') {
                        $remote_ip = env('IP_GATEWAY');
                    }

                    //get or generate new Token
                    $getAccessToken = $this->getAccessTokenForNetvoxGateway($remote_ip);
                    if ($getAccessToken != null) {
                        //Access the API information
                        $netvoxGatewayAccess = Http::withToken($getAccessToken)
                            ->timeout(1)
                            ->get("http://$remote_ip/application/administration/version")
                            ->json();
                        //Check if not failed
                        if ($netvoxGatewayAccess != 'failed') {
                            $manufacturer_id = Manufacturer::where('MANUFACTURER_NAME', "Netvox")
                                ->first()
                                ->MANUFACTURER_ID;
                            $temp_arr = [
                                'GATEWAY_IP'        => $remote_ip,
                                'GATEWAY_SERIAL_NO' => $netvoxGatewayAccess['hardware_serial_number'],
                                'MANUFACTURER_ID'   => $manufacturer_id
                            ];

                            array_push($scan_arr, $temp_arr);
                        }
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        // "Scan" network for (Wulian Gateway)
        $local_ip = env('IP_LOCAL');
        // Convert ip to array
        $ip_arr = explode(".", $local_ip);
        // "Scan" network for gateways
        for ($i = 1; $i <= 254; $i++) {
            $ip_arr[3] = $i;            // Set last octet of ip

            // MC network details
            // Convert ip array back to string
            $remote_ip = implode(".", $ip_arr);
            $remote_port = env('PORT_SCAN_CLIENT');

            //Collect JSON Data
            $message = '{"message":{
                             "mode"     : "registerGateway",
                             "local_ip" : "' . $local_ip . '",
                             "app_key"  : "' . substr(env('APP_KEY'), 7) . '"
                             },
                        "iv_key":"",
                        "length":""
                        }';
            $message = base64_encode($message);

            // sRet is assumed to be in mac address format
            // sRet is then converted to a standard mac address format
            // (e.g. Aa:Bb:Cc:11:22:33 => AA:BB:CC:11:22:33)
            //
            // For future reference, there might be a need to change the processing
            // of sRet in cases where sRet is not in mac address format
            // (i.e. hardware is not from Wulian)
            $sRet = $this->sendToSocket($remote_ip, $remote_port, $message);
            //Remove New Line and White Spaces
            $sRet = preg_replace('/\s+/', ' ', trim($sRet));




            //check if not null and a valid JSON
            if ($sRet == null || $this->isJson($sRet) == false) {
                continue;
            }
            //Parse JSON
            $sRet = json_decode($sRet, true);
            //Check if not null
            if ($sRet["mac_address"] != null && $sRet["company_name"] != null) {
                $serial_number = substr($sRet["mac_address"], 0, 17);
                $serial_number = strtoupper($serial_number);
                $manufacturer_id = Manufacturer::where(
                    'MANUFACTURER_NAME',
                    $sRet["company_name"]
                )
                    ->first()
                    ->MANUFACTURER_ID;
                $temp_arr = [
                    'GATEWAY_IP' => $remote_ip,
                    'GATEWAY_SERIAL_NO' => $serial_number,
                    'MANUFACTURER_ID' => $manufacturer_id
                ];
                array_push($scan_arr, $temp_arr);
            }
        }
        // Insert all scanned gateways to database
        try {
            foreach ($scan_arr as $arr) {
                $gateway = Gateway::where('GATEWAY_IP', $arr['GATEWAY_IP'])
                    ->where('GATEWAY_SERIAL_NO', $arr['GATEWAY_SERIAL_NO'])
                    ->first();
                if (!$gateway) {
                    try {
                        $gateway = new Gateway();
                        $gateway->MANUFACTURER_ID = $arr['MANUFACTURER_ID'];
                        $gateway->GATEWAY_SERIAL_NO = $arr['GATEWAY_SERIAL_NO'];
                        $gateway->GATEWAY_IP = $arr['GATEWAY_IP'];
                        $gateway->REG_FLAG = 0;
                        $gateway->ONLINE_FLAG = 0;
                        $gateway->save();
                    } catch (\Throwable $e) {
                        // Insert to new logs
                        $uri = $request->getUri();
                        $this->processError($uri, $e);
                        return $e->getMessage();
                    }
                    // Response to request
                    array_push($res_arr, $gateway);
                } else {
                    try {
                        $gateway->GATEWAY_SERIAL_NO = $arr['GATEWAY_SERIAL_NO'];
                        $gateway->save();
                    } catch (\Throwable $e) {
                        // Insert to new logs
                        $uri = $request->getUri();
                        $this->processError($uri, $e);
                        return $e->getMessage();
                    }
                }
            }
            $this->auditLogs($ip, $host, $module, $instruction);
            return $res_arr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get all gateways<br>
     * <Function> Retrieve all gateways from database<br>
     *            URL: http://localhost/getGatewayAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $gateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getGatewayAll(Request $request)
    {
        try {
            $gateway = $this->createGetResponse($request, (new Gateway())
                ->newQuery());
            return $gateway;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get specific gateway<br>
     * <Function> Retrieve a specific gateway from database<br>
     *            URL: http://localhost/getGateway/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $gateway
     */
    public function getGateway(Request $request, int $id)
    {
        try {
            $gateway = $this->createGetResponse($request, (new Gateway())
                ->newQuery(), $id);
            return $gateway;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Force deleting a gateway<br>
     * <Function> Resetting gateway without Encryption<br>
     *            URL: http://localhost/deleteGatewayForce<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return mixed|string $retArr
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteGatewayForce(Request $request)
    {
        try {
            $remote_port = env("PORT_GATEWAY");
            // Unregister Gateway to OPS
            // Collect JSON Data
            $message = '{"message":{
                             "mode":"resetGatewayForce"
                                },
                        "iv_key":"",
                        "length":""
                        }';
            $message = base64_encode($message);
            $sRet = $this->sendToSocket($request->GATEWAY_IP, $remote_port, $message);
            $retArr = json_decode($sRet, true);
            return $retArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Get all unregistered gateways<br>
     * <Function> Retrieve all gateways with "unregistered" flag in the DB<br>
     *            URL: http://localhost/getUnregisteredGateways<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $gatewayArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getUnregisteredGateways(Request $request)
    {
        try {
            if ($request->manufacturerID == 1) {
                $gateways = Gateway::where('REG_FLAG', 0)
                    ->whereIn('MANUFACTURER_ID', [1, 6])
                    ->get();
            } elseif ($request->manufacturerID == 2) {
                $gateways = Gateway::where('REG_FLAG', 0)
                    ->where('MANUFACTURER_ID', 2)
                    ->get();
            } elseif ($request->manufacturerID == 4) {
                $gateways = Gateway::where('REG_FLAG', 0)
                    ->where('MANUFACTURER_ID', 4)
                    ->get();
            } else {
                abort(400, 'No Manufacturer ID passed');
            }
            $gatewayArr = [];
            foreach ($gateways as $gateway) {
                array_push($gatewayArr, [
                    "REG_FLAG"  => $gateway->REG_FLAG,
                    "GATEWAY_ID" => $gateway->GATEWAY_ID,
                    "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                    "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                    "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                    "ONLINE_FLAG" => $gateway->ONLINE_FLAG,
                    "FLOOR_ID" => null,
                    "ROOM_ID" => null,
                    "FLOOR_NAME" => null,
                    "ROOM_NAME" => null,
                    "GATEWAY_IP" => $gateway->GATEWAY_IP
                ]);
            }
            return $gatewayArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Get all registered gateways<br>
     * <Function> Retrieve all gateways with "registered" flag<br>
     *            URL: http://localhost/getRegisteredGateways<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $gatewayArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getRegisteredGateways(Request $request)
    {
        try {
            $gatewayArr = [];
            if ($request->manufacturerID == 1) {
                $gateways = Gateway::where('REG_FLAG', 1)
                    ->whereIn('MANUFACTURER_ID', [1, 6])
                    ->with('floor:FLOOR_NAME,FLOOR_ID')
                    ->with('room:ROOM_ID,ROOM_NAME')
                    ->get();
                foreach ($gateways as $gateway) {
                    array_push($gatewayArr, [
                        "REG_FLAG"  => $gateway->REG_FLAG,
                        "GATEWAY_ID" => $gateway->GATEWAY_ID,
                        "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                        "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                        "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                        "FLOOR_NAME" => $gateway->floor->FLOOR_NAME,
                        "ROOM_NAME" => $gateway->room->ROOM_NAME,
                        "GATEWAY_IP" => $gateway->GATEWAY_IP,
                        "ONLINE_FLAG" => $gateway->ONLINE_FLAG
                    ]);
                }
            } elseif ($request->manufacturerID == 2) {
                $gateways = Gateway::where('REG_FLAG', 1)
                    ->where('MANUFACTURER_ID', 2)
                    ->with('floor:FLOOR_NAME,FLOOR_ID')
                    ->get();
                foreach ($gateways as $gateway) {
                    array_push($gatewayArr, [
                        "REG_FLAG"  => $gateway->REG_FLAG,
                        "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                        "GATEWAY_ID" => $gateway->GATEWAY_ID,
                        "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                        "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                        "FLOOR_NAME" => $gateway->floor->FLOOR_NAME,
                        "FLOOR_ID" => $gateway->FLOOR_ID,
                        "GATEWAY_IP" => $gateway->GATEWAY_IP,
                        "ONLINE_FLAG" => $gateway->ONLINE_FLAG
                    ]);
                }
            } elseif ($request->manufacturerID == 4) {
                $gateways = Gateway::where('REG_FLAG', 1)
                    ->where('MANUFACTURER_ID', 4)
                    ->get();
                foreach ($gateways as $gateway) {
                    array_push($gatewayArr, [
                        "REG_FLAG"  => $gateway->REG_FLAG,
                        "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                        "MANUFACTURER_NAME" => Manufacturer::find($gateway->MANUFACTURER_ID)->MANUFACTURER_NAME,
                        "GATEWAY_ID" => $gateway->GATEWAY_ID,
                        "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                        "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                        "GATEWAY_IP" => $gateway->GATEWAY_IP,
                        "ONLINE_FLAG" => $gateway->ONLINE_FLAG
                    ]);
                }
            } else {
                abort(400, 'No Manufacturer ID passed');
            }
            return $gatewayArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Get all block gateways<br>
     * <Function> Retrieve all gateways with "blocked" flag<br>
     *            URL: http://localhost/getBlockedGateways<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $gatewayArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getBlockedGateways(Request $request)
    {
        try {
            if ($request->manufacturerID == 1) {
                $gateways = Gateway::where('REG_FLAG', 4)
                    ->whereIn('MANUFACTURER_ID', [1, 6])
                    ->get();
            } elseif ($request->manufacturerID == 2) {
                $gateways = Gateway::where('REG_FLAG', 4)
                    ->where('MANUFACTURER_ID', 2)
                    ->get();
            } elseif ($request->manufacturerID == 4) {
                $gateways = Gateway::where('REG_FLAG', 4)
                    ->where('MANUFACTURER_ID', 4)
                    ->get();
            } else {
                abort(400, 'No Manufacturer ID passed');
            }
            $gatewayArr = [];
            foreach ($gateways as $gateway) {
                array_push($gatewayArr, [
                    "REG_FLAG"  => $gateway->REG_FLAG,
                    "GATEWAY_ID" => $gateway->GATEWAY_ID,
                    "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                    "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                    "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                    "ONLINE_FLAG" => $gateway->ONLINE_FLAG,
                    "FLOOR_ID" => null,
                    "ROOM_ID" => null,
                    "FLOOR_NAME" => null,
                    "ROOM_NAME" => null,
                    "GATEWAY_IP" => $gateway->GATEWAY_IP
                ]);
            }
            return $gatewayArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> Get all delete gateways<br>
     * <Function> Retrieve all gateways with "delete" flag<br>
     *            URL: http://localhost/getDeletedGateways<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $gatewayArr
     * @throws Throwable When an exception occurs in this process
     */
    public function getDeletedGateways(Request $request)
    {
        try {
            $gatewayArr = [];
            if ($request->manufacturerID == 1) {
                $gateways = Gateway::where('REG_FLAG', 9)
                    ->whereIn('MANUFACTURER_ID', [1, 6])
                    ->with('floor:FLOOR_NAME,FLOOR_ID')
                    ->with('room:ROOM_ID,ROOM_NAME')
                    ->get();
                foreach ($gateways as $gateway) {
                    array_push($gatewayArr, [
                        "REG_FLAG"  => $gateway->REG_FLAG,
                        "GATEWAY_ID" => $gateway->GATEWAY_ID,
                        "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                        "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                        "FLOOR_NAME" => $gateway->floor->FLOOR_NAME,
                        "ROOM_NAME" => $gateway->room->ROOM_NAME,
                        "GATEWAY_IP" => $gateway->GATEWAY_IP
                    ]);
                }
            } elseif ($request->manufacturerID == 2) {
                $gateways = Gateway::where('REG_FLAG', 9)
                    ->where('MANUFACTURER_ID', 2)
                    ->with('floor:FLOOR_NAME,FLOOR_ID')
                    ->get();
                foreach ($gateways as $gateway) {
                    array_push($gatewayArr, [
                        "REG_FLAG"  => $gateway->REG_FLAG,
                        "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                        "GATEWAY_ID" => $gateway->GATEWAY_ID,
                        "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                        "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                        "FLOOR_NAME" => $gateway->floor->FLOOR_NAME,
                        "FLOOR_ID" => $gateway->FLOOR_ID,
                        "GATEWAY_IP" => $gateway->GATEWAY_IP
                    ]);
                }
            } elseif ($request->manufacturerID == 4) {
                $gateways = Gateway::where('REG_FLAG', 9)
                    ->where('MANUFACTURER_ID', 4)
                    ->with('floor:FLOOR_NAME,FLOOR_ID')
                    ->get();
                foreach ($gateways as $gateway) {
                    array_push($gatewayArr, [
                        "REG_FLAG"  => $gateway->REG_FLAG,
                        "MANUFACTURER_ID" => $gateway->MANUFACTURER_ID,
                        "GATEWAY_ID" => $gateway->GATEWAY_ID,
                        "GATEWAY_NAME" => $gateway->GATEWAY_NAME,
                        "GATEWAY_SERIAL_NO" => $gateway->GATEWAY_SERIAL_NO,
                        "FLOOR_NAME" => $gateway->floor->FLOOR_NAME,
                        "FLOOR_ID" => $gateway->FLOOR_ID,
                        "GATEWAY_IP" => $gateway->GATEWAY_IP
                    ]);
                }
            } else {
                abort(400, 'No Manufacturer ID passed');
            }
            return $gatewayArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Get gateway's devices<br>
     * <Function> Retrieve devices associated with the specified gateway<br>
     *            URL: http://localhost/getGatewayDevices/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $gateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getGatewayDevices(Request $request, int $id)
    {
        try {
            $gateway = $this->createGetResponse(
                $request,
                Gateway::findOrFail($id)->devices()
            );
            return $gateway;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> Get gateway's floor<br>
     * <Function> Retrieve floor associated with the specified gateway<br>
     *            URL: http://localhost/getGatewayFloor/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $gateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getGatewayFloor(Request $request, int $id)
    {
        try {
            $gateway = $this->createGetResponse(
                $request,
                Gateway::findOrFail($id)->floor()
            );
            return $gateway;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> Get gateway's room<br>
     * <Function> Retrieve room associated with the specified gateway<br>
     *            URL: http://localhost/getGatewayRoom/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $gateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getGatewayRoom(Request $request, int $id)
    {
        try {
            $gateway =  $this->createGetResponse(
                $request,
                Gateway::findOrFail($id)->room()
            );
            return $gateway;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> Register a gateway<br>
     * <Function> Update gateway's register flag to 1<br>
     *            URL: http://localhost/registerGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "failed : malformed syntax", "failed : existing entity",
     *                "exists", or "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function registerGateway(Request $request)
    {
        try {
            if (
                !isset(
                    $request->GATEWAY_ID,
                    $request->FLOOR_ID,
                    $request->ROOM_ID,
                    $request->GATEWAY_NAME
                ) ||
                $request->GATEWAY_NAME == "-"
            ) {
                // Insert Audit Logs
                $ip = $request->ip();
                $username = auth()->user();
                $host = $username->USERNAME;
                $module = 'Gateway Management';
                $instruction = 'Gateway Registration:Malformed Syntax';
                $this->auditLogs($ip, $host, $module, $instruction);
                return "failed : malformed syntax";
            }
            $gateway = Gateway::findOrFail($request->GATEWAY_ID);
            if ($gateway->REG_FLAG == 1) {
                // Insert System Logs
                $type = '5';
                $instructionType = 'System Error';
                $content = 'Error 409: Entity has already been registered.';
                $ip = $request->ip();
                $username = auth()->user()->USERNAME;
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return "failed : existing entity";
            } else {
                // Check Gateway Names
                $gatewayName = Gateway::where(
                    'GATEWAY_NAME',
                    $request->GATEWAY_NAME
                )->get();
                if (count($gatewayName) > 0) {
                    // Insert System Logs
                    $type = '5';
                    $instructionType = 'System Error';
                    $content = 'Error 409: Entity has already been registered.';
                    $ip = $request->ip();
                    $username = auth()->user()->USERNAME;
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return "exists";
                } else {
                    $gateway->FLOOR_ID = $request->FLOOR_ID;
                    $gateway->ROOM_ID = $request->ROOM_ID;
                    $gateway->GATEWAY_NAME = $request->GATEWAY_NAME;
                    $gateway->REG_FLAG = 1;
                    $gateway->save();

                    // Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Registered a Gateway';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                }
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> Update gateway information<br>
     * <Function> Update details of a specified gateway<br>
     *            URL: http://localhost/updateGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "exists", "success", or "failed"
     * @throws Throwable When an exception occurs in this process
     */
    public function updateGateway(Request $request)
    {
        // Check type of gateway
        if ($request->KEY == 'gateway') {
            // If Wulian Gateway
            try {
                // Check Gateway Names
                $gatewayName = Gateway::where(
                    'GATEWAY_NAME',
                    $request->GATEWAY_NAME
                )->get();
                if (count($gatewayName) > 0) {
                    // Insert System Logs
                    $type = '5';
                    $instructionType = 'System Error';
                    $content = 'Error 409: Entered gateway name is already registered.';
                    $ip = $request->ip();
                    $username = auth()->user()->USERNAME;
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return "exists";
                } else {
                    $gateway = Gateway::findOrFail($request->GATEWAY_ID);
                    $gateway->FLOOR_ID = $request->FLOOR_ID ?
                        $request->FLOOR_ID : $gateway->FLOOR_ID;
                    $gateway->ROOM_ID = $request->ROOM_ID ?
                        $request->ROOM_ID : $gateway->ROOM_ID;
                    $gateway->REG_FLAG = $request->REG_FLAG ?
                        $request->REG_FLAG : $gateway->REG_FLAG;
                    $gateway->ONLINE_FLAG = $request->ONLINE_FLAG ?
                        $request->ONLINE_FLAG : $gateway->ONLINE_FLAG;
                    $gateway->GATEWAY_NAME = $request->GATEWAY_NAME ?
                        $request->GATEWAY_NAME : $gateway->GATEWAY_NAME;
                    $gateway->save();

                    // Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Updated Gateway Details';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                }
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
                return "failed";
            }
        } elseif ($request->KEY == 'modBusGateway') {
            // If Modbus Gateway
            try {
                // Check Gateway Names
                $gatewayName = Gateway::where(
                    'GATEWAY_NAME',
                    $request->GATEWAY_NAME
                )->get();
                if (count($gatewayName) > 0) {
                    //Insert System Logs
                    $type = '5';
                    $instructionType = 'System Error';
                    $content = 'Error 409: Entered modbus gateway name is already registered.';
                    $ip = $request->ip();
                    $username = auth()->user()->USERNAME;
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return "exists";
                } else {
                    $gateway = Gateway::findOrFail($request->GATEWAY_ID);
                    $gateway->GATEWAY_NAME = $request->GATEWAY_NAME ?
                        $request->GATEWAY_NAME : $gateway->GATEWAY_NAME;
                    $gateway->save();

                    // Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Updated Gateway Details';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                }
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
                return $e->getMessage();
            }
        } elseif ($request->KEY == 'cameraGateway') {
            // If Camera Gateway
            try {
                // Check Gateway Names
                $gatewayName = Gateway::where(
                    'GATEWAY_NAME',
                    $request->GATEWAY_NAME
                )->get();
                if (count($gatewayName) > 0) {
                    //Insert System Logs
                    $type = '5';
                    $instructionType = 'System Error';
                    $content = 'Error 409: Entered modbus gateway name is already registered.';
                    $ip = $request->ip();
                    $username = auth()->user()->USERNAME;
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return "exists";
                } else {
                    $gateway = Gateway::findOrFail($request->GATEWAY_ID);
                    $gateway->FLOOR_ID = $request->FLOOR_ID ?
                        $request->FLOOR_ID : $gateway->FLOOR_ID;
                    $gateway->GATEWAY_SERIAL_NO = $request->GATEWAY_SERIAL_NO ?
                        $request->GATEWAY_SERIAL_NO : $gateway->GATEWAY_SERIAL_NO;
                    $gateway->GATEWAY_NAME = $request->GATEWAY_NAME ?
                        $request->GATEWAY_NAME : $gateway->GATEWAY_NAME;
                    $gateway->GATEWAY_IP = $request->GATEWAY_IP ?
                        $request->GATEWAY_IP : $gateway->GATEWAY_IP;
                    $gateway->save();

                    // Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Updated Gateway Details';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                }
            } catch (\Throwable $e) {
                // Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $uri = $request->route()->uri();
                $content = $uri . " : " . $e->getMessage();
                $ip = $request->ip();
                $username = auth()->user()->USERNAME;
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

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> Delete a gateway<br>
     * <Function> Delete the gateway on the DB<br>
     *            URL: http://localhost/deleteGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success" or "gateway"
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteGateway(Request $request)
    {
        try {
            // Find Gateway from DB
            $gateway = Gateway::findOrFail($request->GATEWAY_ID);
            Log::info($request->KEY);
            Log::info("Check here");
            if ($request->KEY == 'gateway') {
                try {
                    if ($gateway) {
                        $remote_ip = $gateway->GATEWAY_IP;
                        $remote_port = env("PORT_GATEWAY");
                        $gatewayId = $gateway->GATEWAY_ID;
                        // Get Devices based on Gateway
                        $devices = $gateway->devices()->get();
                        if (count($devices) >= 1) {
                            // Delete Plotted Device on Floor Map
                            foreach ($devices as $key => $device) {
                                $this->deleteDevicePlot($device);
                            }
                        }
                        if ($gateway->MANUFACTURER_ID == 6) {
                            $gateway->devices()->delete();
                            $gateway->delete();
                            $ip = $request->ip() ? $request->ip() : '-';
                            $username = auth()->user();
                            $host = $username->USERNAME;
                            $module = 'Gateway Management';
                            $instruction = 'Deleted a Gateway';
                            $this->auditLogs($ip, $host, $module, $instruction);
                            return "success";
                        }

                        // if request has "FORCE" parameter, directly delete gateway from db
                        if ($request->FORCE == true) {
                            //Delete device Relations
                            app('App\Http\Controllers\DeviceController')->deleteAllDeviceRelation($devices);
                            $gateway->devices()->delete();
                            $gateway->delete();
                            // Insert Audit Logs
                            $ip = $request->ip() ? $request->ip() : '-';
                            $username = auth()->user();
                            $host = $username->USERNAME;
                            $module = 'Gateway Management';
                            $instruction = 'Deleted a Gateway';
                            $this->auditLogs($ip, $host, $module, $instruction);
                            return "success";
                        }
                        // Unregister Gateway to OPS
                        $data = '{"mode":"deleteGateway"}';
                        $message = $this->encryptMessage($data);
                        $sRet = $this->sendToSocket($remote_ip, $remote_port, $message);
                        $retArr = json_decode($sRet, true);
                        $isAccessible = false;
                        //Check if Gateway is accessible
                        if (isset($retArr["function"]) && $retArr["function"] == 'gatewayDeleted') {
                            //Delete Device Relations
                            app('App\Http\Controllers\DeviceController')->deleteAllDeviceRelation($devices);
                            // Delete Devices based on Gateway
                            $gateway->devices()->delete();
                            $gateway->delete();
                            // Insert Audit Logs
                            $ip = $request->ip() ? $request->ip() : '-';
                            $username = auth()->user();
                            $host = $username->USERNAME;
                            $module = 'Gateway Management';
                            $instruction = 'Deleted a Gateway';
                            $this->auditLogs($ip, $host, $module, $instruction);
                        } else {
                            // Insert Audit Logs
                            $ip = $request->ip() ? $request->ip() : '-';
                            $username = auth()->user();
                            $host = $username->USERNAME;
                            $module = 'Gateway Management';
                            $instruction = 'Cannot contact Gateway to be deleted:'
                                . $gateway->GATEWAY_NAME;
                            $this->auditLogs($ip, $host, $module, $instruction);
                            return 'gateway';
                        }
                        return "success";
                    }
                } catch (\Throwable $e) {
                    // Insert to new logs
                    $uri = $request->getUri();
                    $this->processError($uri, $e);
                    return $e;
                }
            } elseif ($request->KEY == 'modBusGateway') {
                try {
                    // Change modbus gateway status to "deleted"
                    $gateway->REG_FLAG = 9;
                    $gateway->save();
                    // Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Deleted a Modbus Gateway';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                } catch (\Throwable $e) {
                    // Insert to new logs
                    $uri = $request->getUri();
                    $this->processError($uri, $e);
                    return $e->getMessage();
                }
            } elseif ($request->KEY == 'cameraGateway') {
                try {
                    // Delete all cameras connected to the camera gateway
                    $devices = Device::where('GATEWAY_ID', $request->GATEWAY_ID)->get();
                    foreach ($devices as $device) {
                        $device->delete();
                    }
                    // Delete camera gateway in the Gateway Table
                    $gateway->delete();
                    // Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Deleted a Camera Gateway';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                } catch (\Throwable $e) {
                    // Insert to new logs
                    $uri = $request->getUri();
                    $this->processError($uri, $e);
                    return $e->getMessage();
                }
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (15.0)
     *
     * <Processing name> Delete Gateway Manual<br>
     * <Function> Delete gateway manually<br>
     *            URL: http://localhost/deleteGatewayManual<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return mixed|string $retArr
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteGatewayManual(Request $request)
    {
        try {
            $remote_port = env("PORT_GATEWAY");
            // Unregister Gateway to OPS
            $data = '{"mode":"deleteGateway"}';
            $message = $this->encryptMessage($data);
            $sRet = $this->sendToSocket(
                $request->GATEWAY_IP,
                $remote_port,
                $message
            );
            $retArr = json_decode($sRet, true);
            //Insert Audit Logs
            $ip = $request->ip() ? $request->ip() : "-";
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Gateway Management';
            $instruction = 'Deleted Gateway Manually';
            $this->auditLogs($ip, $host, $module, $instruction);
            return $retArr;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> Undelete a gateway<br>
     * <Function> Update gateway's register flag to 1<br>
     *            URL: http://localhost/undeleteGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function undeleteGateway(Request $request)
    {
        try {
            $gateway = Gateway::findOrFail($request->GATEWAY_ID);
            $gateway->REG_FLAG = 1;
            $gateway->save();
            // Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Gateway Management';
            $instruction = 'Updated Gateway to be Registered';
            $this->auditLogs($ip, $host, $module, $instruction);
            return "success";
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (17.0)
     *
     * <Processing name> Block a gateway<br>
     * <Function> Update gateway's register flag to 4<br>
     *            URL: http://localhost/blockGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function blockGateway(Request $request)
    {
        try {
            $gateway = Gateway::findOrFail($request->GATEWAY_ID);
            $gateway->REG_FLAG = 4;
            $gateway->save();
            // Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Gateway Management';
            $instruction = 'Updated Gateway to be Blocked';
            $this->auditLogs($ip, $host, $module, $instruction);
            return "success";
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (18.0)
     *
     * <Processing name> Unblock a gateway<br>
     * <Function> Update gateway's register flag to 0<br>
     *            URL: http://localhost/unblockGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function unblockGateway(Request $request)
    {
        try {
            $gateway = Gateway::findOrFail($request->GATEWAY_ID);
            $gateway->REG_FLAG = 0;
            $gateway->save();
            // Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user();
            $host = $username->USERNAME;
            $module = 'Gateway Management';
            $instruction = 'Updated Gateway to be Unregistered';
            $this->auditLogs($ip, $host, $module, $instruction);
            return "success";
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (19.0)
     *
     * <Processing name> Update gateway status<br>
     * <Function> Check gateway's status through the MC<br>
     *            URL: http://localhost/onlineGateway<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return array|string $list
     * @throws Throwable When an exception occurs in this process
     */
    public function onlineGateway(Request $request)
    {
        $waitTimeout = 2;
        try {
            $gatewayIPs = Gateway::where('REG_FLAG', 1)->get();
            // Loop addresses and create a socket for each
            $socks = [];
            $list = [];
            foreach ($gatewayIPs as $address) {
                $address["ONLINE_FLAG"] = 0;
                $address->save();
                $testport = $address["MANUFACTURER_ID"] == 2 ?
                    $testport = 502 : $testport = 80;
                //$fp = @fsockopen ($address->GATEWAY_IP, $testport, $errno, $errstr,5);
                exec("ping -c 1 " . $address->GATEWAY_IP, $outcome, $status);
                if ($status == 0) {
                    $changeStatus = Gateway::where(
                        'GATEWAY_IP',
                        $address->GATEWAY_IP
                    )->first();
                    $changeStatus->ONLINE_FLAG = 1;
                    $changeStatus->save();
                    //fclose($fp);
                } else {
                    // Catch Insert System Logs
                    $type = '3';
                    $instructionType = 'System Error';
                    $content = $address->GATEWAY_IP . " cannot be accessed.";
                    $ip = $address->GATEWAY_IP;
                    $username = 'null';
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                }
            }

            $gateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2])
                ->where('REG_FLAG', 1)
                ->get()
                ->count();
            $onlineGateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2])
                ->where([['ONLINE_FLAG', 1], ['REG_FLAG', 1]])
                ->get()
                ->count();
            $offlineGateways = Gateway::whereIn('MANUFACTURER_ID', [1, 2])
                ->where([['ONLINE_FLAG', 0], ['REG_FLAG', 1]])
                ->get()
                ->count();
            $devices = Device::where([['MANUFACTURER_ID', 1], ['REG_FLAG', 1]])
                ->get()
                ->count();
            $onlineDevices = Device::where([
                ['MANUFACTURER_ID', 1], ['ONLINE_FLAG', 1], ['REG_FLAG', 1]
            ])
                ->get()
                ->count();
            $offlineDevices = Device::where([
                ['MANUFACTURER_ID', 1], ['ONLINE_FLAG', 0], ['REG_FLAG', 1]
            ])
                ->get()
                ->count();

            $json = [
                'totalGateway' => $gateways,
                'onlineGateway' => $onlineGateways,
                'offlineGateway' => $offlineGateways,
                'totalDevices' => $devices,
                'onlineDevices' => $onlineDevices,
                'offlineDevices' => $offlineDevices,
            ];
            array_push($list, $json);
            event(new OnlineStatusEvent($list));
            return $list;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (20.0)
     *
     * <Processing name> Create new gateway ModBus<br>
     * <Function> Add new gateway modbus entry in the DB<br>
     *            URL: http://localhost/createGatewayModbus<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string "malformedSyntax", "exists" or "success"
     * @throws Throwable When an exception occurs in this process
     */
    public function createGatewayModbus(Request $request)
    {
        try {
            if (!isset(
                $request->GATEWAY_IP,
                $request->FLOOR_ID,
                $request->GATEWAY_NAME,
                $request->GATEWAY_SERIAL_NO
            )) {
                // Insert System Logs
                $type = '5';
                $instructionType = 'System Error';
                $content = 'Error 400: Malformed Syntax';
                $ip = $request->ip();
                $username = auth()->user()->USERNAME;
                $this->storeLogs(
                    $type,
                    $instructionType,
                    $content,
                    $ip,
                    $username
                );
                return "malformedSyntax";
            } else {
                // Check Gateway Names
                $gatewayName = Gateway::where(
                    'GATEWAY_NAME',
                    $request->GATEWAY_NAME
                )
                    ->get();
                if (count($gatewayName) > 0) {
                    // Insert System Logs
                    $type = '5';
                    $instructionType = 'System Error';
                    $content = 'Error 409: Entity has already been registered.';
                    $ip = $request->ip();
                    $username = auth()->user()->USERNAME;
                    $this->storeLogs(
                        $type,
                        $instructionType,
                        $content,
                        $ip,
                        $username
                    );
                    return "exists";
                } else {
                    $newGateway = new Gateway();
                    $newGateway->FLOOR_ID = $request->FLOOR_ID;
                    $newGateway->ROOM_ID = $request->ROOM_ID;
                    $newGateway->MANUFACTURER_ID = $request->MANUFACTURER_ID;
                    $newGateway->GATEWAY_SERIAL_NO = $request->GATEWAY_SERIAL_NO;
                    $newGateway->GATEWAY_NAME = $request->GATEWAY_NAME;
                    $newGateway->GATEWAY_IP = $request->GATEWAY_IP;
                    $newGateway->REG_FLAG = 1;
                    $newGateway->ONLINE_FLAG = 1;
                    $newGateway->save();
                    //Insert Audit Logs
                    $ip = $request->ip();
                    $username = auth()->user();
                    $host = $username->USERNAME;
                    $module = 'Gateway Management';
                    $instruction = 'Added a New Gateway';
                    $this->auditLogs($ip, $host, $module, $instruction);
                    return "success";
                }
            }
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (21.0)
     *
     * <Processing name> Enable Join MC<br>
     * <Function> Enable Join Mode of MC<br>
     *            URL: http://localhost/enableJoinMC<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string $sRet
     * @throws Throwable When an exception occurs in this process
     */
    public function enableJoinMC(Request $request)
    {
        try {
            $gateway = Gateway::where('GATEWAY_ID', $request->GATEWAY_ID)
                ->select('GATEWAY_IP')
                ->firstOrFail();
            $remote_ip = $gateway->GATEWAY_IP;
            $remote_port = env('PORT_GATEWAY');
            $data = '{"mode":"enableJoin","device_id":"","command":""}';
            $message = $this->encryptMessage($data);
            $sRet = $this->sendToSocket($remote_ip, $remote_port, $message);
            return $sRet;
        } catch (\Throwable $e) {
            // Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $content = $uri . " : " . $e->getMessage();
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     *
     */
    public function registerCameraGateway(Request $request): string
    {
        // Check if the gateway is already registered
        $gatewaySerialCheck = Gateway::where('GATEWAY_SERIAL_NO', $request->GATEWAY_SERIAL_NO)->first();
        // Check if the gateway name already exists in the Gateway Table
        $gatewayNameCheck = Gateway::where('GATEWAY_NAME', $request->GATEWAY_NAME)->first();
        if ($gatewaySerialCheck) {
            return 'already registered';
        } else if ($gatewayNameCheck) {
            return 'name exists';
        } else {
            try {
                $gateway = new Gateway();
                $gateway->FLOOR_ID = 0;
                $gateway->ROOM_ID = 0;
                $gateway->MANUFACTURER_ID = $request->MANUFACTURER_ID;
                $gateway->GATEWAY_SERIAL_NO = $request->GATEWAY_SERIAL_NO;
                $gateway->GATEWAY_IP = $request->GATEWAY_IP;
                $gateway->GATEWAY_NAME = $request->GATEWAY_NAME;
                $gateway->REG_FLAG = 1;
                $gateway->ONLINE_FLAG = 1;
                $gateway->save();
                return 'success';
            } catch (\Throwable $e) {
                // Insert to new logs
                $uri = $request->getUri();
                $this->processError($uri, $e);
                return $e->getMessage();
            }
        }
    }

    public function checkCameraGatewayInfo(Request $request)
    {
        try {
            $info = $this->getAcsSystem($request->GATEWAY_IP);
            if ($info) {
                return $info;
            } else {
                return "error";
            }
        } catch (\Throwable $e) {
            return "error";
        }
    }
}
