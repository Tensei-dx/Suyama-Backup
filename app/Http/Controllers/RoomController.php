<?php
/*
 * <System Name> iBMS
 * <Program Name> RoomController.php
 *
 * <Create> 2018.06.27 TP Yani
 * <Update> 2018.06.29 TP Bryan    Added 5.0
 *          2018.06.29 TP Bryan    Added 6.0
 *          2018.07.02 TP Bryan    Added 7.0
 *          2018.07.26 TP Bryan    Fixed code structure
 *          2018.08.07 TP Bryan    Finalized(?) functions as endpoints
 *          2019.07.09 TP Ivin     Checking of Hierarchy and Adding of return comments
 *          2020.05.21 TP Uddin    Modify URL and Methodname according to the URL list
 *          2021.09.13 TP Chris    Added getHotelRoom method
 *          2021.09.14 TP Chris    Added getRoomNetvoxDevices method
 */

namespace App\Http\Controllers;

use App\Http\Requests\Room\UpdateRequest;
use App\Models\Device;
use App\Models\Floor;
use App\Models\Room;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * <Class Name> RoomController
 *
 * <Overview> Class that manipulates the model in preparation for data
 *            presentation in the view
 * @package Controller
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class RoomController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // index                (1.0) Display a listing of the resource
    // show                 (2.0) Display the specified resource
    // update               (3.0) Update the specified resource in the storage
    // massUpdate           (4.0) Update all resources in the storage
    // getRoomAll           (5.0) Retrieve all rooms from database
    // getRoom              (6.0) Retrieve room from database
    // getRoomFloor         (7.0) Retrieve floor associated with the room
    // getRoomGateways      (8.0) Retrieve all gateways associated with the room
    // getRoomDevices       (9.0) Retrieve all devices associated with the room
    // createRoom           (10.0) Insert new room to database
    // updateRoom           (11.0) Update room details
    // deleteRoom           (12.0) Delete room from database
    // getRoomMeter         (13.0) Get E-Meter on Specific Room
    // getHotelRoom         (14.0) Get hotel room from database
    // getRoomNetvoxDevices (15.0) Get the room with netvox devices

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> index <br>
     * <Function> Display a listing of the resource <br>
     *          URI: /rooms/
     *          METHOD: GET
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Room::query();

        // get the 'children' parameter to call relationships
        $children = $request->input('children') ?? [];

        // include relations
        if (in_array('booking_today', $children))   $query->with('bookingToday');
        if (in_array('booking_now', $children))     $query->with('bookingNow.roomMessage');
        if (in_array('check_in_today', $children))  $query->with('checkInToday');
        if (in_array('check_out_today', $children)) $query->with('checkOutToday');

        return response($query->get());
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> show <br>
     * <Function> Display the specified resource <br>
     *          URI: /rooms/:room/
     *          METHOD: GET
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Room $room)
    {
        $children = $request->input('children') ?? [];

        // include relations
        if (in_array('booking_today', $children))   $room->load('bookingToday');
        if (in_array('booking_now', $children))     $room->load('bookingNow');
        if (in_array('check_in_today', $children))  $room->load('checkInToday');
        if (in_array('check_out_today', $children)) $room->load('checkOutToday');

        return response($room);
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> update <br>
     * <Function> Update the specified resource in the storage <br>
     *
     * @param  \App\Http\Requests\Room\UpdateRequest  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Room $room)
    {
        $room->update($request->validated());

        return response()->noContent();
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> massUpdate <br>
     * <Function> Update all resources in the storage <br>
     *
     * @param  \App\Http\Requests\Room\UpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(UpdateRequest $request)
    {
        Room::query()->update($request->validated());

        return response()->noContent();
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> getRoomAll<br>
     * <Function> Retrieve all rooms from database<br>
     *            URL: http://localhost/getRoomAll<br>
     *            METHOD: GET
     * @param Request $request
     * @return object $rooms
     * @throws Throwable When an exception occurs in this process
     */
    public function getRoomAll(Request $request)
    {
        try {
            $rooms = $this->createGetResponse($request, (new Room())->newQuery());
            return $rooms;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> getRoom<br>
     * <Function> Retrieve room from database<br>
     *            URL: http://localhost/getRoom/:id<br>
     *            METHOD: GET
     * @param Request $request
     * @param int $id
     * @return object $room
     * @throws Throwable When an exception occurs in this process
     */
    public function getRoom(Request $request, int $id)
    {
        try {
            $room = $this->createGetResponse($request, (new Room())->newQuery(), $id);
            return $room;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> getRoomFloor<br>
     * <Function> Retrieve floor associated with the room<br>
     *            URL: http://localhost/getRoomFloor/:id<br>
     *            METHOD: GET
     * @param Request $request
     * @param int $id
     * @return object $roomFloor
     * @throws Throwable When an exception occurs in this process
     */
    public function getRoomFloor(Request $request, int $id)
    {
        try {
            $roomFloor = $this->createGetResponse($request, Room::findOrFail($id)
                ->floor());
            return $roomFloor;
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
     * <Processing name> getRoomGateways<br>
     * <Function> Retrieve all gateways associated with the room<br>
     *            URL: http://localhost/getRoomGateways/:id<br>
     *            METHOD: GET
     * @param Request $request
     * @param int $id
     * @return object $roomGateway
     * @throws Throwable When an exception occurs in this process
     */
    public function getRoomGateways(Request $request, int $id)
    {
        try {
            $roomGateway = $this->createGetResponse($request, Room::findOrFail($id)
                ->gateways());
            return $roomGateway;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> getRoomDevices<br>
     * <Function> Retrieve all devices associated with the room<br>
     *            URL: http://localhost/getRoomDevices/:id<br>
     *            METHOD: GET
     * @param Request $request
     * @param int $id
     * @return object $roomDevices
     * @throws Throwable When an exception occurs in this process
     */
    public function getRoomDevices(Request $request, int $id)
    {
        try {
            $roomDevices = $this->createGetResponse($request, Room::find($id)
                ->devices()
                ->with('deviceBindings')
                ->where("REG_FLAG", 1));

            // Throw Error
            if (!$roomDevices) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("0:No Device in Room", 300030000);
            }
            return $roomDevices;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> createRoom<br>
     * <Function> Create new room entry in database<br>
     *            URL: http://localhost/createRoom<br>
     *            METHOD: POST
     * @param Request $request
     * @return object|string $newRoom|'duplicate'
     * @throws Throwable When an exception occurs in this process
     */
    public function createRoom(Request $request)
    {
        //for audit logs
        $ip = $request->ip();
        $username = auth()->user();
        $host = $username->USERNAME;
        $module = 'Floor management';
        $instruction = 'Added a Room: ' . $request->roomName;

        $floorid = $request->floorId;
        $roomPath = "/imgs/rooms";
        try {
            $floor = Floor::with('rooms')->findOrFail($floorid);
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }

        $floorRoomData = $floor['FLOOR_MAP_DATA'];
        $floorRooms = $floor['rooms'];
        $length = count($floorRooms) - 1;
        $validate = 0;

        // Checks if the Room Name is already used
        for ($i = 0; $i <= $length; $i++) {
            if ($floorRooms[$i]['ROOM_NAME'] == $request->roomName) {
                $validate++;
            }
        }

        if ($validate == 0) {
            $roomImageFile = $request->roomImageFile;
            // Upload Floor Image to specified location
            $roomImageFile->move(public_path() . $roomPath, $request->roomImageName);
            $roomMap = explode(".", $request->roomImageName)[0];
            $roomDetails = [];
            array_push($roomDetails, [
                "coor" => "",
                "status" => "hilight-green",
                "roomMap" => $roomMap,
                "roomImage" => $roomPath . "/" . $request->roomImageName,
                "deviceCoor" => []
            ]);
            array_push($floorRoomData['roomMap'], $roomDetails[0]);
            $floor['FLOOR_MAP_DATA'] = $floorRoomData;
            try {
                $floor->save();
            } catch (\Throwable $e) {
                //Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $uri = $request->route()->uri();
                $content = $uri . " : " . $e->getMessage();
                $username = auth()->user()->USER_ID;
                $this->storeLogs($type, $instructionType, $content, $ip, $username);
                return $e->getMessage();
            }

            $newRoom = new Room();
            $newRoom->FLOOR_ID = $floorid;
            $newRoom->ROOM_NAME = $request->roomName;
            $newRoom->ROOM_MAP_DATA = ['ROOM_MAP' => $roomMap];
            try {
                $newRoom->save();
                $this->auditLogs($ip, $host, $module, $instruction);
            } catch (\Throwable $e) {
                //Insert System Logs
                $type = '3';
                $instructionType = 'System Error';
                $uri = $request->route()->uri();
                $content = $uri . " : " . $e->getMessage();
                $username = auth()->user()->USER_ID;
                $this->storeLogs($type, $instructionType, $content, $ip, $username);
                return $e->getMessage();
            }
            return $newRoom;
        } else {
            return 'duplicate';
        }
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> updateRoom<br>
     * <Function> Update room details<br>
     *            URL: http://localhost/updateRoom<br>
     *            METHOD: POST
     *            DATA: ROOM_ID, ROOM_NAME
     * @param Request $request
     * @return object $room
     * @throws Throwable When an exception occurs in this process
     */
    public function updateRoom(Request $request)
    {
        //for audit logs
        $ip = $request->ip();
        $username = auth()->user()->USERNAME;
        $module = 'Floor Management';
        $instruction = 'Updated a Room';
        $this->auditLogs($ip, $username, $module, $instruction);
        try {
            $room = Room::findOrFail($request->ROOM_ID);
            $room->ROOM_ID = $request->ROOM_ID ?
                $request->ROOM_ID : $room->ROOM_ID;
            $room->ROOM_NAME = $request->ROOM_NAME ?
                $request->ROOM_NAME : $room->ROOM_NAME;
            $room->save();
            return $room;
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $username = auth()->user()->USER_ID;
            $content = $uri . " : " . $e->getMessage();
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (12.0)
     * <Processing name> deleteRoom<br>
     * <Function> Delete room from database<br>
     *            URL: http://localhost/deleteRoom<br>
     *            METHOD: POST
     *            DATA: ROOM_ID
     * @param Request $request
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function deleteRoom(Request $request)
    {
        //for audit logs
        $ip = $request->ip();
        $username = auth()->user()->USERNAME;
        $module = 'Floor Management';
        try {
            $room = Room::findOrFail($request->ROOM_ID);
            $floor = Room::find($request->ROOM_ID)->floor()->first();
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $username = auth()->user()->USER_ID;
            $content = $uri . " : " . $e->getMessage();
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
        $floorRoomData = $floor['FLOOR_MAP_DATA'];
        //remove room from floor json
        try {
            foreach ($floorRoomData['roomMap'] as $key => $floorRoom) {
                if ($floorRoom['roomMap'] == $room['ROOM_MAP_DATA']['ROOM_MAP']) {
                    unset($floorRoomData['roomMap'][$key]);
                    $floorRoomData['roomMap'] = array_merge($floorRoomData['roomMap']);
                    $mappedRoom = Room::find($room['ROOM_ID'])->floor()->first();
                    $mappedRoom->FLOOR_MAP_DATA = $floorRoomData;
                    $mappedRoom->save();
                    break;
                }
            }
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $username = auth()->user()->USER_ID;
            $content = $uri . " : " . $e->getMessage();
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
        //unregister gateways in room
        try {
            $roomDevices = $room->devices()->get();
            foreach ($room->gateways()->get() as $gates => $gate) {
                $gate->REG_FLAG = 0;
                $gate->ONLINE_FLAG = 0;
                $gate->FLOOR_ID = null;
                $gate->ROOM_ID = null;
                $gate->save();
            }
            //Delete device relation.
            app('App\Http\Controllers\DeviceController')->deleteAllDeviceRelation($roomDevices);
            $room->devices()->delete();
            $instruction = 'Deleted a Room: ' . $room->ROOM_NAME;
            $room->delete();
            $this->auditLogs($ip, $username, $module, $instruction);
        } catch (\Throwable $e) {
            //Insert System Logs
            $type = '3';
            $instructionType = 'System Error';
            $uri = $request->route()->uri();
            $username = auth()->user()->USER_ID;
            $content = $uri . " : " . $e->getMessage();
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
        return 'success';
    }


    /**
     * <Layer number> (14.0)
     *
     * <Processing name> getHotelRoom<br>
     * <Function> Get Rooms in for Hotel<br>
     *            URL: http://localhost/getHoteRoom/<br>
     *            METHOD: GET
     *            DATA:
     * @param Request $request
     * @param int $id
     * @return array|object room with devices
     * @throws Throwable When an exception occurs in this process
     */
    public function getCo2DevicePerRoom(Request $request)
    {
        $rooms = Room::whereHas('devices', function ($query) {
            $query->where('DEVICE_TYPE', 'co2_temp_humid');
        })
            ->with(['devices' => function ($device) {
                return $device->where('DEVICE_TYPE', 'co2_temp_humid');
            }])->get();
        return $rooms;
    }


    /**
     * <Layer number> (15.0)
     *
     * <Processing name> getRoomNetvoxDevices<br>
     * <Function> Get Devices based on Restaurant Room for Hotel<br>
     *            URL: http://localhost/getRoomNetvoxDevices/<br>
     *            METHOD: GET
     *            DATA: Room ID
     * @param Request $request
     * @param int $id
     * @return array|object $devices
     * @throws Throwable When an exception occurs in this process
     */
    public function getRoomNetvoxDevicesHotel(Request $request, $id)
    {
        $devices = Device::where('ROOM_ID', $id)->get();
        return $devices;
    }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> getRoomNetvoxDevices<br>
     * <Function> Update Status Id of the room once guest checked-in<br>
     *            URL: http://localhost/updateRoomStatus/<br>
     *            METHOD: POST
     *            DATA: Room ID
     * @param Request $request
     * @param int $id
     * @throws Throwable When an exception occurs in this process
     */
    public function updateRoomStatus(Request $request)
    {
        $room = Room::where('ROOM_ID', $request->ROOM_ID)->get();
        $room[0]->STATUS_ID = 201;
        $room[0]->save();
    }
}
