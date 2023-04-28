<?php

namespace App\Http\Controllers;

use App\Events\UpdateErrorLogsEvent;
use App\Models\Correspondence;
use App\Models\LogsNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * <Class Name> LogsNotificationController
 *
 * <Function Name> Notification Management and Processing<br>
 * Create : 2021.12.17 TP Russell<br>
 *
 * <Overview> This controller is responsible for managing logs notifications.
 * @package Controller
 * @author TP Russell <r-russell@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2021 Tensei Data Net Inc.
 */

class LogsNotificationController extends Controller
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // getAllLogsNotification           (1.0) Get all logs notification data
    // getLogsNotification              (2.0) Get filtered logs notification data
    // createResponse                   (3.0) Create a response for the error data
    // updateLogsNotification           (4.0) Update logs notification table

    /**
     * <Layer number> (1.0)
     *
     * <Processing name>Get All Logs Notification<br>
     * <Function> Get all logs notification data<br>
     *            URL: http://localhost/logs-notification/get/all<br>
     *            METHOD: GET
     *
     * @return array $logsNotification
     * @throws Throwable When an exception occurs in this process
     */
    public function getAllLogsNotification(Request $request)
    {
        $startDateTime = Carbon::parse($request->START_TIME);
        $endDateTime = Carbon::parse($request->END_TIME);
        try {
            $logsNotification = LogsNotification::whereIn('EVENT_STATUS', $request->EVENT_STATUS)
                ->where('CREATED_AT', '>=', $startDateTime)
                ->where('CREATED_AT', '<=', $endDateTime)
                ->with('room', 'correspondence', 'reservation.bookingRooms')
                ->orderBy('CREATED_AT', 'DESC')
                ->get();
            return $logsNotification;
        } catch (\Throwable $e) {
            info($e);
            return response($e->getMessage(), 400);
        }
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name>Get Logs Notification<br>
     * <Function> Get filtered logs notification data<br>
     *            URL: http://localhost/logs-notification/get/notification<br>
     *            METHOD: GET
     *
     * @return array $logsNotification
     * @throws Throwable When an exception occurs in this process
     */
    public function getLogsNotification()
    {
        try {
            $notifs = LogsNotification::with('room', 'correspondence')
                ->orderBy('CREATED_AT', 'desc')
                ->get();
            $return_notifs = [];
            foreach ($notifs as $notif) {
                if ($notif->EVENT_STATUS == 0) {
                    $filtered_notifs = [
                        'EVENT_TYPE' => 'ERROR',
                        'LOGS_NOTIF_ID' => $notif->LOGS_NOTIF_ID,
                        'MESSAGE_ID' => $notif->MESSAGE_ID,
                        'ROOM_NAME' => $notif->room->ROOM_NAME ?? null,
                        'EVENT_STATUS' => $notif->EVENT_STATUS,
                        'DATE' => $notif->CREATED_AT,
                        'correspondence' => $notif->correspondence
                    ];
                    $return_notifs[] = $filtered_notifs;
                }
                if ($notif->EVENT_STATUS == 2) {
                    $filtered_notifs = [
                        'EVENT_TYPE' => 'WARNING',
                        'LOGS_NOTIF_ID' => $notif->LOGS_NOTIF_ID,
                        'MESSAGE_ID' => $notif->MESSAGE_ID,
                        'ROOM_NAME' => $notif->room->ROOM_NAME ?? null,
                        'EVENT_STATUS' => $notif->EVENT_STATUS,
                        'DATE' => $notif->CREATED_AT,
                        'correspondence' => $notif->correspondence
                    ];
                    $return_notifs[] = $filtered_notifs;
                }
                if ($notif->EVENT_STATUS == 4 && $notif->MESSAGE_ID == 'I007') {
                    $filtered_notifs = [
                        'EVENT_TYPE' => 'INFORMATION',
                        'LOGS_NOTIF_ID' => $notif->LOGS_NOTIF_ID,
                        'MESSAGE_ID' => $notif->MESSAGE_ID,
                        'ROOM_NAME' => $notif->room->ROOM_NAME ?? null,
                        'EVENT_STATUS' => $notif->EVENT_STATUS,
                        'DATE' => $notif->CREATED_AT,
                        'correspondence' => $notif->correspondence
                    ];
                    $return_notifs[] = $filtered_notifs;
                }
                if ($notif->EVENT_STATUS == 4 && $notif->MESSAGE_ID == 'I008') {
                    $filtered_notifs = [
                        'EVENT_TYPE' => 'INFORMATION',
                        'LOGS_NOTIF_ID' => $notif->LOGS_NOTIF_ID,
                        'MESSAGE_ID' => $notif->MESSAGE_ID,
                        'ROOM_NAME' => $notif->room->ROOM_NAME,
                        'EVENT_STATUS' => $notif->EVENT_STATUS,
                        'DATE' => $notif->CREATED_AT,
                        'correspondence' => $notif->correspondence
                    ];
                    $return_notifs[] = $filtered_notifs;
                }
                if ($notif->EVENT_STATUS == 4 && $notif->MESSAGE_ID == 'I009') {
                    $filtered_notifs = [
                        'EVENT_TYPE' => 'INFORMATION',
                        'LOGS_NOTIF_ID' => $notif->LOGS_NOTIF_ID,
                        'MESSAGE_ID' => $notif->MESSAGE_ID,
                        'ROOM_NAME' => $notif->room->ROOM_NAME ?? null,
                        'EVENT_STATUS' => $notif->EVENT_STATUS,
                        'DATE' => $notif->CREATED_AT,
                        'correspondence' => $notif->correspondence
                    ];
                    $return_notifs[] = $filtered_notifs;
                }
                if ($notif->EVENT_STATUS == 4 && $notif->MESSAGE_ID == 'I010') {
                    $filtered_notifs = [
                        'EVENT_TYPE' => 'INFORMATION',
                        'LOGS_NOTIF_ID' => $notif->LOGS_NOTIF_ID,
                        'MESSAGE_ID' => $notif->MESSAGE_ID,
                        'ROOM_NAME' => $notif->room->ROOM_NAME ?? null,
                        'EVENT_STATUS' => $notif->EVENT_STATUS,
                        'DATE' => $notif->CREATED_AT,
                        'correspondence' => $notif->correspondence
                    ];
                    $return_notifs[] = $filtered_notifs;
                }
            }
            return $return_notifs;
        } catch (\Throwable $th) {
            info($th);
            return $th;
        }
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name>createResponse<br>
     * <Function> Create a response for the error data<br>
     *            URL: http://localhost/logs-notification/create/response<br>
     *            METHOD: POST
     *
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function createResponse(Request $request)
    {
        $user = auth()->user();
        $name = $user->USERNAME;

        try {
            $response = new Correspondence();
            $response->LOGS_NOTIF_ID = $request->LOGS_NOTIF_ID;
            $response->CORRESPONDING_PERSON = $name;
            $response->RESPONSE_TIME = Carbon::now();
            $response->save();

            $logsNotification = LogsNotification::firstWhere('LOGS_NOTIF_ID', $request->LOGS_NOTIF_ID);
            $logsNotification->EVENT_STATUS = 1;
            $logsNotification->save();
            event(new UpdateErrorLogsEvent($logsNotification));
            return 'success';
        } catch (\Throwable $e) {
            info($e);
        }
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name>Update Logs Notification<br>
     * <Function> Update event status of logs notification<br>
     *            URL: http://localhost/logs-notification/update/event-status<br>
     *            METHOD: POST
     *
     * @return string 'success'
     * @throws Throwable When an exception occurs in this process
     */
    public function updateLogsNotification(Request $request)
    {
        try {
            $updateLogsNotification = LogsNotification::where('LOGS_NOTIF_ID', $request->LOGS_NOTIF_ID)->first();
            if ($updateLogsNotification->EVENT_STATUS == 2) {
                $updateLogsNotification->EVENT_STATUS = 3;
                $updateLogsNotification->save();
            }
            if ($updateLogsNotification->EVENT_STATUS == 4) {
                $updateLogsNotification->EVENT_STATUS = 5;
                $updateLogsNotification->save();
            }
            return 'success';
        } catch (\Throwable $th) {
            info($th);
        }
    }
}
