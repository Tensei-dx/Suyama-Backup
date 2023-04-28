<?php

namespace App\Http\Controllers;

use App\Models\Book_Room;
use App\Models\BookPMS;
use App\Models\LogsNotification;
use App\Models\ProcessedData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * <Class Name> NotificationController
 *
 * <Function Name> Notification Management and Processing<br>
 * Create : 2022.01.12 TP Russell<br>
 *
 * <Overview> This controller is responsible for creating dummy data for testing.
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 */

class GenerateTestingDataController extends Controller
{
    public function createLogsNotifsData(Request $request)
    {
        ini_set('max_execution_time', 0);
        foreach ($request->logs as $log) {
            switch ($log['TYPE']) {
                case 'ERROR':
                    for ($i = 0; $i < $log['TOTAL_DATA']; $i++) {
                        $logs = new LogsNotification();
                        $logs->MESSAGE_ID = $log['MESSAGE_ID'];
                        $logs->ROOM_ID = $log['ROOM_ID'];
                        $logs->EVENT_STATUS = $log['EVENT_STATUS'];
                        $logs->save();
                    }
                    break;
                case 'WARNING':
                    for ($i = 0; $i < $log['TOTAL_DATA']; $i++) {
                        $logs = new LogsNotification();
                        $logs->MESSAGE_ID = $log['MESSAGE_ID'];
                        $logs->ROOM_ID = $log['ROOM_ID'];
                        $logs->EVENT_STATUS = $log['EVENT_STATUS'];
                        $logs->save();
                    }
                    break;
                case 'INFORMATION':
                    for ($i = 0; $i < $log['TOTAL_DATA']; $i++) {
                        $logs = new LogsNotification();
                        $logs->MESSAGE_ID = $log['MESSAGE_ID'];
                        $logs->ROOM_ID = $log['ROOM_ID'];
                        $logs->EVENT_STATUS = $log['EVENT_STATUS'];
                        $logs->save();
                    }
                    break;
            }
        }
    }

    public function createProcessData(Request $request)
    {
        ini_set('max_execution_time', 0);
        foreach ($request->devices as $device) {
            switch ($device['DEVICE_TYPE']) {
                case 'occupancy_temp_light':
                    for ($i = 0; $i < $device['TOTAL_DATA']; $i++) {
                        $proccess_data = new ProcessedData();
                        $proccess_data->DEVICE_ID = $device['DEVICE_ID'];
                        $proccess_data->DATA = $device['DATA'];
                        $proccess_data->SEND_FLAG = 0;
                        $proccess_data->save();
                    }
                    break;
                case 'window_door_sensor':
                    for ($i = 0; $i < $device['TOTAL_DATA']; $i++) {
                        $proccess_data = new ProcessedData();
                        $proccess_data->DEVICE_ID = $device['DEVICE_ID'];
                        $proccess_data->DATA = $device['DATA'];
                        $proccess_data->SEND_FLAG = 0;
                        $proccess_data->save();
                    }
                    break;
                case 'co2_temp_humid':
                    for ($i = 0; $i < $device['TOTAL_DATA']; $i++) {
                        $proccess_data = new ProcessedData();
                        $proccess_data->DEVICE_ID = $device['DEVICE_ID'];
                        $proccess_data->DATA = $device['DATA'];
                        $proccess_data->SEND_FLAG = 0;
                        $proccess_data->save();
                    }
                    break;
            }
        }
    }

    public function createUserData(Request $request)
    {
        ini_set('max_execution_time', 0);
        for ($i = 0; $i < $request->TOTAL_DATA; $i++) {
            $password = Hash::make($request->PASSWORD);
            $user = new User();
            $user->USER_TYPE = $request->USER_TYPE;
            $user->USERNAME = $request->USERNAME;
            $user->PASSWORD = $password;
            $user->FIRST_NAME = $request->FIRST_NAME;
            $user->LAST_NAME = $request->LAST_NAME;
            $user->CONTACT_NUMBER = $request->CONTACT_NUMBER;
            $user->EMAIL = $request->EMAIL;
            $user->REG_FLAG = 1;
            $user->save();
        }
    }

    public function createGuestData(Request $request)
    {
        $suffix_start = $request->USERNAME__SUFFIX_START;
        $bookId_start = $request->BOOK_ID_START;
        $checkIn_start = $request->CHECK_IN_START;
        $start_date = date('Y-m-d H:i:s', strtotime($checkIn_start));


        for ($i = 0; $i < $request->TOTAL_DATA; $i++) {
            //User Table
            $user = new User();
            $user->USERNAME = 'test' . ($suffix_start + $i);
            $user->PASSWORD = Hash::make($request->PASSWORD);
            $user->USER_TYPE = $request->USER_TYPE;
            $user->REG_FLAG = 1;
            $user->save();
            info($user->USER_ID);

            //Book Table
            $book = new BookPMS();
            $book->BOOK_ID = $bookId_start + $i;
            $book->CONTACT_NUMBER = $request->CONTACT_NUMBER;
            $book->EMAIL = $request->EMAIL;
            $book->save();

            //Book Room Table
            $book_room = new Book_Room();
            $book_room->ROOM_ID = 44;
            $book_room->BOOK_ID = $book->BOOK_ID;
            $book_room->USER_ID = $user->USER_ID;
            $day_increment = ' +' . $i . ' day';
            $check_in_date = date('Y-m-d H:i:s', strtotime($start_date . $day_increment));
            $book_room->CHECK_IN_TIME = $check_in_date;
            $book_room->save();
        }
    }
}
