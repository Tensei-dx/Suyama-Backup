<?php

/**
 * <System Name> iBMS
 * <File Name> StayseeRoomController.php
 */

namespace App\Http\Controllers\Staysee;

use App\Events\RoomsSynced;
use App\Events\RoomsSyncing;
use App\Http\Controllers\Controller;
use App\Services\StayseeRoomService;
use App\Traits\CommonFunctions;

/**
 * <Class Name> StayseeRoomController
 *
 * <Function Name> Staysee Room Syncing<br>
 * Create : 2021.08.09 TP Uddin SPRINT_02 [TASK027]
 * Update : 2021.08.17 TP Uddin Update commentdoc of methods
 * Update : 2021.12.01 TP Uddin Move the logic to a service
 *
 * <Overview>
 * @package
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version 1.0
 * @copyright
 */
class StayseeRoomController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // sync             (1.0) Update the room data in iBMS from the Staysee Room API

    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> sync <br>
     * <Function> Update the room data in iBMS from the Staysee Room API <br>
     *          URL: /staysee_rooms/sync
     *          METHOD: POST
     * 
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        try {
            // broadcast when room starts syncing
            broadcast(new RoomsSyncing());

            // Initialize the StayseeRoomService class
            $service = new StayseeRoomService();

            // Get the latest room data from Staysee API
            $response = $service->fetchRoomData(env('STAYSEE_TOKEN'));

            // if response is successful, start syncing
            if ($response->successful()) {
                $service->deleteRemovedRooms($response->json());
                $service->storeNewRooms($response->json());
                $service->updateRooms($response->json());
            }

            // broadcast when room is done syncing
            broadcast(new RoomsSynced());

            return response('success');
        } catch (\Throwable $th) {
            // Insert System Logs
            $type = 3;
            $instructionType = 'System Error';
            $uri = '';
            $content = $uri . " : " . $th->getMessage();
            $ip = '';
            $username = "";
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return response($th->getMessage(), 400);
        }
    }
}
