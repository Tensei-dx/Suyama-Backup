<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

use App\Events\NewDeviceDataEvent;
use App\Events\RoomMessageEvent;
use App\Models\Device;
use App\Models\ProcessedData;
use App\Models\Room;
use App\Traits\CommonFunctions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


/**
 * <Class Name> ManagementController
 *
 * <Function Name> Management Dashboard <br>
 * Create : 2020.12.17 TP Russell<br>
 * Update : 2021.02.26 TP Uddin Commented out methods that were not used anymore:
 *                              1. updateRoomStatus
 *                              2. tempData
 *                              3. getDevice
 * Update : 2021.02.26 TP Uddin Update Process Hierarchy and method documentations<br>
 *          2021.07.21 TP Jermaine Edit getAllRooms function.
 *
 * <Overview> Retrieve all rooms, temp_hum devices and people counter cameras and display their data in the dashboard.
 *
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ManagementController extends Controller
{
    /**************************************************************************/
    /* Processing Hierachy                                                    */
    /**************************************************************************/
    // getAllRooms                      (1.0) Get all rooms from the database
    // getTempDevice                    (2.0) Get all temp_hum devices from the database
    // getPeopleCounterCameras          (3.0) Get all people counter cameras
    // updateRoomStatus                 (4.0) (Disabled) Update room status to available
    // peopleCounterData                (5.0) Sends people counter data every minute to the frontend
    // tempData                         (6.0) (Disabled) Sends temp_hum data every minute to the frontend
    // getDevice                        (7.0) (Disabled) Get all temp_hum devices from the database

    use AuthenticatesUsers;
    use CommonFunctions;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Get All Rooms <br>
     * <Function> Get all rooms from the database<br>
     *            URL: http://localhost/getAllRooms<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $rooms|$e->getMessage()
     */
    public function getAllRooms(Request $request)
    {
        try {
            // Get all rooms with the associated devices and today's booking
            $rooms = Room::with('devices', 'bookingToday.roomMessage')
                ->withCount('futureBookings')
                ->get();

            foreach ($rooms as $room) {
                if ($room->bookingToday === null) {
                    if (in_array($room->STATUS_ID, [201, 205])) {
                        $room->STATUS_ID = 203;
                    }
                }
            }
            return $rooms;
        } catch (\throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get All Temperature and Humidity Devices <br>
     * <Function> Get all temp_hum devices from the database<br>
     *            URL: http://localhost/getTempDevice<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array|string $response|$e->getMessage()
     */
    public function getDeviceType(Request $request)
    {
        try {
            $response = Device::where("DEVICE_TYPE", $request->DEVICE_TYPE)
                ->where("REG_FLAG", $request->REG_FLAG)
                ->with("room:ROOM_ID,ROOM_NAME")
                ->get();
            return $response;
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get All People Counter Cameras <br>
     * <Function> Get all camera with people counter from the database<br>
     *            URL: http://localhost/getPeopleCounterCameras<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return array $camArr
     */
    public function getPeopleCounterCameras(Request $request)
    {
        $cameras = Device::where('DEVICE_TYPE', 'camera')->get();
        $camArr = [];
        foreach ($cameras as $camera) {
            // Check camera's applications in the DATA Column
            foreach ($camera->DATA[0]['Applications'] as $app) {
                // Check if the camera has People Counter app running
                if ($app['name'] == 'tvpc' && $app['status'] == 'Running') {
                    $tempData = [];
                    $pcdata = ProcessedData::where('DEVICE_ID', $camera->DEVICE_ID)
                        ->latest()
                        ->first();
                    $tempData['ROOM_ID'] = $camera->ROOM_ID;
                    if (empty($pcdata) != 1) {
                        $tempData['DEVICE_ID'] = $pcdata->DEVICE_ID;
                        $tempData['DATA'] = $pcdata->DATA;
                        $tempData['totalOccupancy'] =
                            ($pcdata->DATA['peopleIn'] + $pcdata->DATA['yesterdayIn'])
                            - ($pcdata->DATA['peopleOut'] + $pcdata->DATA['yesterdayOut']);
                        $camArr[] = $tempData;
                    } else {
                        return $camArr;
                    }
                }
            }
        }
        return $camArr;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Update the Room Status <br>
     * <Function> Update the status of the room to available<br>
     *            URL: http://localhost/updateRoomStatus<br>
     *            METHOD: POST
     *
     * @param Request $request
     */
    public function updateRoomStatus(Request $request)
    {
        $roomId = $request->ROOM_ID;
        $room = Room::findOrFail($roomId);
        $room->STATUS_ID = $request->STATUS_ID;
        $room->save();

        if ($request->STATUS_ID == 201) {
            $event = 'Room Status (Check-In)';
        } elseif ($request->STATUS_ID == 202) {
            $event = 'Room Status (Check-Out)';
        } elseif ($request->STATUS_ID == 203) {
            $event = 'Room Status (Available)';
        } elseif ($request->STATUS_ID == 204) {
            $event = 'Room Status (Unavailable)';
        } elseif ($request->STATUS_ID == 205) {
            $event = 'Room Status (Booked)';
        }
        $user = auth()->user();
        $user_id = $user->USER_ID;
        // $this->appLog($user_id, $request->ROOM_NAME, $event);
        event(new RoomMessageEvent($room));
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Triggers Event for People Counter Data <br>
     * <Function> Send people counter data every minute to the frontend<br>
     *            URL: http://localhost/peopleCounterData<br>
     *            METHOD: POST
     *
     * @return void
     */
    public function peopleCounterData()
    {
        // Get camera information
        $cameras = Device::where('MANUFACTURER_ID', 4)
            ->where('DEVICE_TYPE', 'camera')
            ->select('DEVICE_ID', 'DATA')
            ->get();

        foreach ($cameras as $camera) {

            // Check camera's applications in the DATA Column
            foreach ($camera->DATA[0]['Applications'] as $app) {

                // Check if the camera has People Counter app running
                if ($app['name'] == 'tvpc' && $app['status'] == 'Running') {

                    // Get real time people counter data
                    $peopleData = $this->getPcRealTimeData($camera->DEVICE_ID);

                    // Get latest data
                    $pastData = ProcessedData::where('DEVICE_ID', $camera->DEVICE_ID)
                        ->latest()
                        ->first();

                    // If there is no previous data
                    if (!$pastData) {
                        $yesterdayIn = 0;
                        $yesterdayOut = 0;
                        $peopleIn = $peopleData['in'];
                        $peopleOut = $peopleData['out'];

                        // If previous data is from today,
                        // just copy 'yesterdayIn' and 'yesterdayOut'
                        // then add them to the PC data
                    } elseif ($pastData->CREATED_AT >= date('Y-m-d')) {
                        $yesterdayIn = $pastData->DATA['yesterdayIn'];
                        $yesterdayOut = $pastData->DATA['yesterdayOut'];
                        $peopleIn = $peopleData['in'] + $yesterdayIn;
                        $peopleOut = $peopleData['out'] + $yesterdayOut;

                        // If previous data is not from today
                        // set yesterday In and Out to the previous In and Out
                        // then add them to the PC data
                    } else {
                        $yesterdayIn = $pastData->DATA['peopleIn'];
                        $yesterdayOut = $pastData->DATA['peopleOut'];
                        $peopleIn = $peopleData['in'] + $yesterdayIn;
                        $peopleOut = $peopleData['out'] + $yesterdayOut;
                    }
                    // Make new entry to ProcessedData Table
                    $newData = new ProcessedData();
                    $newData->DEVICE_ID = $camera->DEVICE_ID;
                    $newData->DATA = [
                        'peopleIn' => $peopleIn,
                        'peopleOut' => $peopleOut,
                        'yesterdayIn' => $yesterdayIn,
                        'yesterdayOut' => $yesterdayOut,
                    ];
                    $newData->SEND_FLAG = 0;
                    $newData->save();

                    // Trigger frontend
                    event(new NewDeviceDataEvent($newData));
                }
            }
        }
    }

    // /**
    //  * <Layer number> (6.0)
    //  *
    //  * <Processing name> Triggers Event for Temperature and Humidity Data <br>
    //  * <Function> Send temp_hum data every minute to the frontend<br>
    //  *            URL: http://localhost/tempData<br>
    //  *            METHOD: POST
    //  */
    // public function tempData(){
    //     $temps = Device::where('MANUFACTURER_ID', 1)
    //         ->where('DEVICE_TYPE', 'temp_hum')
    //         ->select('DEVICE_ID', 'DATA')
    //         ->get();
    //     foreach ($temps as $temp) {
    //         $newData = new ProcessedData();
    //         $newData->DEVICE_ID = $temp->DEVICE_ID;
    //         $newData->DATA      = $temp->DATA;
    //         $newData->SEND_FLAG = 0;
    //         $newData->save();
    //         event(new NewTempDataEvent($newData));
    //     }
    // }


    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Get All Temperature and Humidity Devices <br>
     * <Function> Get all temp_hum devices from the database<br>
     *            URL: http://localhost/getTempDevice<br>
     *            METHOD: GET
     *
     * @param Request $request
     * @return Object $model
     */
    public function getDevice(Request $request)
    {
        $device = Device::where("DEVICE_TYPE", $request->DEVICE_TYPE)
            ->where("REG_FLAG", $request->REG_FLAG)
            ->where("DEVICE_ID", $request->DEVICE_ID)
            ->get();

        try {
            // Throw Error
            if (!$device) {
                throw new \Exception("0:Device Not Found", 210050001);
            }
        } catch (\Throwable $e) {
            // Insert to new logs
            $uri = $request->getUri();
            $this->processError($uri, $e);
        }
        return $device;
    }
}
