<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function getAllCleaningLogs()
    {
        $rooms = Room::with('statusCode', 'cleaningLog.statusCode')
            ->select('ROOM_NAME', 'ROOM_ID', 'STATUS_ID')
            ->get();
        return $rooms;
    }

    public function getRoomInformation(Request $request)
    {
        $room = Room::where('ROOM_ID', $request->ROOM_ID)
            ->with('statusCode', 'cleaningLog.statusCode', 'book', 'roomType.roomItem')
            ->select('ROOM_ID', 'ROOM_NAME', 'ROOM_TOTAL_PEOPLE', 'STATUS_ID', 'ROOM_TYPE_ID')
            ->get();
        return $room;
    }

    public function getCleaningTask(Request $request)
    {
        $room = Room::where('ROOM_ID', $request->ROOM_ID)
            ->with('cleaningLog', 'taskList')
            ->select('ROOM_ID', 'ROOM_NAME')
            ->get();
        return $room;
    }
}
