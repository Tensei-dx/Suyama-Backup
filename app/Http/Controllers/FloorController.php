<?php
/*
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Models\AuthLocation;
use App\Models\Floor;
use App\Models\Room;
use App\Models\User;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * <Class Name> FloorController
 *
 * <Function Name> Floor Management and Processing<br>
 * Create : 2018.06.27 TP Yani<br>
 * Update : 2018.06.28 TP Bryan    Fixed comments
 *          2018.06.29 TP Bryan    Added 5.0, 6.0
 *          2018.07.02 TP Bryan    Added 7.0
 *          2018.07.25 TP Bryan    Fixed code structure
 *          2018.08.01 TP Bryan    Finalized(?) functions as endpoints
 *          2018.08.02 TP Bryan    Finalized(?) functions as endpoints
 *          2018.10.05 TP Robert   Added 11.0
 *          2019.06.06 TP Harvey   Applying Coding Standard
 *          2020.05.21 TP Uddin    Modify URL and Methodname according to the URL list<br>
 *
 * <Overview> This controller is responsible for managing floors and floor process.
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class FloorController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getFloorAll                      (1.0) Retrieve all floors from database
    // getFloor                         (2.0) Retrieve a floor from database
    // getFloorRooms                    (3.0) Retrieve all rooms associated with the floor
    // getFloorGateways                 (4.0) Retrieve all gateways associated with the floor
    // getFloorDevices                  (5.0) Retrieve all devices associated with the floor
    // createFloor                      (6.0) Insert new floor to database
    // updateFloor                      (7.0) Update floor details
    // deleteFloor                      (8.0) Delete floor from database
    // getAuthFloor                     (9.0) Retrieve floors with user's privileges
    // getFloorRoomRegisteredGateways   (10.0) Retrieve rooms with registered gateways associated with the floor

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Acquire all floors<br>
     * <Function> Retrieve all floors from database<br>
     *            URL: http://localhost/getFloorAll<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return object $this->createGetResponse($request, (new Floor())->newQuery())
     * @throws Throwable When an exception occurs in this process
     */
    public function getFloorAll(Request $request)
    {
        try {
            return $this->createGetResponse($request, (new Floor())->newQuery());
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (2.0)
     *
     * <Processing name> Acquire a specific floor<br>
     * <Function> Retrieve a floor from database<br>
     *            URL: http://localhost/getFloor/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $floor
     * @throws Throwable When an exception occurs in this process
     */
    public function getFloor(Request $request, int $id)
    {
        try {
            $floor = $this->createGetResponse($request, (new Floor())->newQuery(), $id);
            return $floor;
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (3.0)
     *
     * <Processing name> Get all rooms in a floor<br>
     * <Function> Retrieve all rooms associated with the floor<br>
     *            URL: http://localhost/getFloorRooms/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $floorRoom
     * @throws Throwable When an exception occurs in this process
     */
    public function getFloorRooms(Request $request, int $id)
    {
        try {
            $floorRoom = $this->createGetResponse($request, Floor::findOrFail($id)->rooms());
            return $floorRoom;
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (4.0)
     *
     * <Processing name> Get all gateways in a floor<br>
     * <Function> Retrieve all gateways associated with the floor<br>
     *            URL: http://localhost/getFloorGateways/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $floorGateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getFloorGateways(Request $request, int $id)
    {
        try {
            $floorGateway = $this->createGetResponse(
                $request,
                Floor::findOrFail($id)->gateways()
            );
            return $floorGateway;
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (5.0)
     *
     * <Processing name> Get all devices in a floor<br>
     * <Function> Retrieve all devices associated with the floor<br>
     *            URL: http://localhost/getFloorDevices/:id<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $floorDevices
     * @throws Throwable When an exception occurs in this process
     */
    public function getFloorDevices(Request $request, int $id)
    {
        try {
            $floorDevices = $this->createGetResponse(
                $request,
                Floor::findOrFail($id)->devices()
            );
            return $floorDevices;
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (6.0)
     *
     * <Processing name> Create new floor in the database<br>
     * <Function> Insert new floor to database<br>
     *            URL: http://localhost/createFloor<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return object|string $floor|'duplicate' or 'duplicate name'
     * @throws Throwable When an exception occurs in this process
     */
    public function createFloor(Request $request)
    {
        Log::info($request);
        //for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $request->USERNAME;
        $module = 'Floor management';
        $roomPath = "imgs/rooms";
        $floorPath = "imgs/floors";
        $floorImageName = "";
        $floorImageFile = "";
        $floorName = "";
        $roomArray = [];
        $roomImageName = [];
        $roomImageFile = [];
        $roomName = [];
        $floor = [];
        //Combine all files to specific array
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'roomImageName') !== FALSE) {
                array_push($roomImageName, $value);
            } elseif (strpos($key, 'roomImageFile') !== FALSE) {
                array_push($roomImageFile, $value);
            } elseif (strpos($key, 'roomName') !== FALSE) {
                array_push($roomName, $value);
            } elseif (strpos($key, 'floorImageName') !== FALSE) {
                $floorImageName = $value;
            } elseif (strpos($key, 'floorImageFile') !== FALSE) {
                $floorImageFile = $value;
            } elseif (strpos($key, 'floorName') !== FALSE) {
                $floorName = $value;
            }
        }
        //Check if Image Name has duplicates
        if (count($roomImageName) !== count(array_flip($roomImageName))) {
            return 'duplicate';
        }
        //Upload Floor Image to specified location
        $dup = Floor::where('FLOOR_NAME', $floorName)->get();
        if (count($dup) > 0) {
            return 'duplicate name';
        }
        $floorImageFile->move(public_path($floorPath), $floorImageName);
        foreach ($roomImageFile as $key => $file) {
            //Upload Room Image to specified location
            $file->move(public_path($roomPath), $roomImageName[$key]);
            $roomMap = explode(".", $roomImageName[$key])[0];
            //Collecting data to be uploaded to database
            array_push($roomArray, [
                "coor"      => "",
                "status"    => "hilight-green",
                "roomMap"   => $roomMap,
                "roomImage" => $roomPath . "/" . $roomImageName[$key],
                "deviceCoor" => []
            ]);
        }
        try {
            //Save floor to database
            $floorMapData = [];
            $floorMapData['roomMap'] = $roomArray;
            $floorMapData['floorImage'] = $floorPath . "/" . $floorImageName;
            $floor = new Floor();
            $floor->FLOOR_NAME = $floorName;
            $floor->FLOOR_MAP_DATA =  $floorMapData;
            $floor->save();
            //Save rooms to database
            foreach ($roomArray as $key => $room) {
                $roomMap = explode(".", $roomImageName[$key])[0];
                $room = new Room();
                $room->FLOOR_ID = $floor->FLOOR_ID;
                $room->ROOM_NAME = $roomName[$key];
                $room->ROOM_MAP_DATA = ['ROOM_MAP' => $roomMap];
                $room->save();
            }
            //Insert to Audit Logs
            $ip = $request->ip();
            $host = auth()->user()->USERNAME;
            $module = "Floor Management";
            $instruction = 'Added a Floor :' . $floorName;
            $this->auditLogs($ip, $host, $module, $instruction);
            return $floor; //return Object
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (7.0)
     *
     * <Processing name> Update a floor<br>
     * <Function> Update floor details<br>
     *            URL: http://localhost/updateFloor<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return object $floor
     * @throws Throwable When an exception occurs in this process
     */
    public function updateFloor(Request $request)
    {
        try {
            $floor = Floor::findOrFail($request->FLOOR_ID);
            $floor->FLOOR_NAME = $request->FLOOR_NAME ?
                $request->FLOOR_NAME : $floor->FLOOR_NAME;
            $floor->FLOOR_MAP_DATA = $request->FLOOR_MAP_DATA ?
                $request->FLOOR_MAP_DATA : $floor->FLOOR_MAP_DATA;
            $floor->save();
            //Insert Audit Logs
            $ip = $request->ip();
            $username = auth()->user()->USERNAME;
            $module = 'Floor Management';
            $instruction = 'Updated a Floor';
            $this->auditLogs($ip, $username, $module, $instruction);
            return $floor; //Must be object
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (8.0)
     *
     * <Processing name> Delete a floor<br>
     * <Function> Delete floor from database<br>
     *            URL: http://localhost/deleteFloor<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return string 'success'
     */
    public function deleteFloor(Request $request)
    {
        $floor = Floor::findOrFail($request->FLOOR_ID);
        $floorDevices = $floor->devices()->get();
        $floorGateways = $floor->gateways()->get();
        foreach ($floorDevices as $key => $device) {
            $this->deleteDevicePlot($device);
        }
        foreach ($floorGateways as $gates => $gate) {
            $gate->REG_FLAG = 0;
            $gate->ONLINE_FLAG = 0;
            $gate->FLOOR_ID = null;
            $gate->ROOM_ID = null;
            $gate->save();
        }
        //Delete device relation.
        app('App\Http\Controllers\DeviceController')->deleteAllDeviceRelation($floorDevices);
        $floor->rooms()->delete();
        $floor->devices()->delete();
        $floor->delete();
        //Insert Store Logs
        $ip = $request->ip();
        $username = '';
        $module = 'Floor Management';
        $instruction = 'Deleted a floor';
        $this->auditLogs($ip, $username, $module, $instruction);
        return 'success';
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Acquire user's auth floors<br>
     * <Function> Retrieve floors with user's privileges<br>
     *            URL: http://localhost/getAuthFloor<br>
     *            METHOD: POST
     *
     * @param Request $request
     * @return object|string $floor|'no assigned floors'
     * @throws Throwable When an exception occurs in this process
     */
    public function getAuthFloor(Request $request)
    {
        try {
            $userid = $request->USERID;
            $arr = [];
            $user = User::where('USER_ID', $userid)->first();
            $locations = AuthLocation::select('FLOOR_ID')->where('USER_ID', $userid)->get();
            if (empty($locations) || $locations == null || count($locations) == 0) {
                //Insert System Logs
                $type = '4';
                $instructionType = 'System Warning';
                $uri = $request->route()->uri();
                $content = 'User has no assigned floor/s';
                $ip = $request->ip();
                $username = auth()->user()->USERNAME;
                $this->storeLogs($type, $instructionType, $content, $ip, $username);
                return 'no assigned floors';
            }
            foreach ($locations as $location) {
                array_push($arr, $location->FLOOR_ID);
            }
            $floors = FLoor::whereIn('FLOOR_ID', $arr)->with('rooms')->get();
            return $floors;
        } catch (\Throwable $e) {
            //Insert System Logs
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
     * <Layer number> (10.0)
     *
     * <Processing name> Acquire rooms and gateways in a floor<br>
     * <Function> Retrieve rooms with registered gateways associated with the floor<br>
     *            URL: http://localhost/getFloorRoomRegisteredGateways<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @param int $id
     * @return object $rooms
     */
    public function getFloorRoomRegisteredGateways(Request $request, int $id)
    {
        $rooms = Floor::findOrFail($id)->rooms()
            ->with('floor')
            ->with('registeredGateways')
            ->get();
        return $rooms;
    }
}
