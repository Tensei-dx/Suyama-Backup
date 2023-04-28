<?php

/**
 * <System Name> iBMS
 */

namespace App\Http\Controllers;

/**
 * <Class Name> CleaningController
 *
 * <Function Name> Cleaning Controller <br>
 * Create : 2021.2.17 TP Russell<br>
 *
 * <Overview> Retrieve all data user for Cleaning Management in PMS
 *
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */

class CleaningController extends Controller
{
    /**************************************************************************/
    /* Processing Hierachy                                                    */
    /**************************************************************************/
    // getAllCleaningLogs               (1.0) Get all cleaning logs
    // getRoomInformation               (2.0) Get room information for cleaning
    // getCleaningTask                  (3.0) Get the list of task for cleaning
    // updateCleaningLog                (4.0) Update the cleaning logs
    // updateCleaningStatus             (5.0) Update cleaning status

    //【TASK015】
    // /**
    //  * <Layer number> (1.0)
    //  *
    //  * <Processing name> Get All Cleaning Logs <br>
    //  * <Function> Get all cleaning logs from the database<br>
    //  *            URL: http://localhost/getAllCleaningLogs<br>
    //  *            METHOD: GET
    //  *
    //  * @param
    //  * @return Object $rooms
    //  */
    // public function getAllCleaningLogs()
    // {
    //     // Getting Room with Status Code Currently Cleaning and To Clean
    //     $rooms = Room::whereHas('cleaningLog', function ($query) {
    //                     $query->whereIn('STATUS_ID', [101,102]);
    //                     })
    //                     ->with('statusCode','cleaningLog.statusCode')
    //                     ->select('ROOM_NAME','ROOM_ID','STATUS_ID')
    //                     ->get();
    //     return $rooms;
    // }

    //  /**
    //  * <Layer number> (2.0)
    //  *
    //  * <Processing name> Get Room Information <br>
    //  * <Function> Get the room information from the database<br>
    //  *            URL: http://localhost/getRoomInformation<br>
    //  *            METHOD: GET
    //  *
    //  * @param Request $request
    //  * @return Object $rooms
    //  */
    // public function getRoomInformation(Request $request)
    // {
    //     $room = Room::where('ROOM_ID', $request->ROOM_ID)
    //                     ->with('statusCode','cleaningLog.statusCode','book','roomType.roomItem')
    //                     ->select('ROOM_ID','ROOM_NAME','ROOM_TOTAL_PEOPLE','STATUS_ID','ROOM_TYPE_ID')
    //                     ->first();
    //     return $room;
    // }

    // /**
    //  * <Layer number> (3.0)
    //  *
    //  * <Processing name> Get Cleaning Task <br>
    //  * <Function> Get the list of task in the room from the database<br>
    //  *            URL: http://localhost/getCleaningTask<br>
    //  *            METHOD: GET
    //  *
    //  * @param Request $request
    //  * @return Object $rooms
    //  */
    // public function getCleaningTask(Request $request)
    // {
    //     $room = Room::where('ROOM_ID', $request->ROOM_ID)
    //                     ->with('cleaningLog','taskList')
    //                     ->select('ROOM_ID','ROOM_NAME')
    //                     ->first();
    //     return $room;
    // }

    // /**
    //  * <Layer number> (4.0)
    //  *
    //  * <Processing name> Update Cleaning Logs <br>
    //  * <Function> Update the cleaning logs in the database<br>
    //  *            URL: http://localhost/updateCleaningLog<br>
    //  *            METHOD: GET
    //  *
    //  * @param Request $request
    //  * @return Object $rooms
    //  */
    // public function updateCleaningLog(Request $request)
    // {
    //     $cleaningLog = CleaningLog::findOrFail($request->CLEANING_LOG_ID);
    //     $cleaningLog->STATUS_ID = 103;
    //     $cleaningLog->START_TIME = $request->START_TIME;
    //     $cleaningLog->END_TIME = $request->END_TIME;
    //     $cleaningLog->save();
    // }

    // /**
    //  * <Layer number> (5.0)
    //  *
    //  * <Processing name> Update Cleaning Status <br>
    //  * <Function> Update the cleaning status in the cleaning logs<br>
    //  *            URL: http://localhost/updateCleaningStatus<br>
    //  *            METHOD: GET
    //  *
    //  * @param Request $request
    //  * @return Object $rooms
    //  */
    // public function updateCleaningStatus(Request $request)
    // {
    //     $cleaningLog = CleaningLog::findOrFail($request->CLEANING_LOG_ID);
    //     $cleaningLog->STATUS_ID = 101;
    //     $cleaningLog->save();
    // }
    //【TASK015】
}
