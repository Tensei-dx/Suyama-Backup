<?php

namespace App\Console\Commands;

use App\Models\NatureRemoDevice;
use App\Models\NatureRemoSignal;
use App\Models\ParamSettings;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ACAutoStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ac:auto-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $params = ParamSettings::where('PARAM_ID', 1)->first();
        if ($params->AC_MODE == 0) {
            $ac_mode = 'auto mode';
        } elseif ($params->AC_MODE == 1) {
            $ac_mode = 'warm mode';
        } elseif ($params->AC_MODE == 2) {
            $ac_mode = 'cool mode';
        } elseif ($params->AC_MODE == 3) {
            $ac_mode = 'dry mode';
        }
        $rooms = Room::with('checkInToday')->get();
        foreach ($rooms as $room) {
            if ($room->checkInToday != null) {

                $mins = ' -' . $params->AC_START_OFFSET . ' minutes';
                $arrival_time = date('Y-m-d H:i', strtotime($room->checkInToday->ARRIVAL_TIME));
                $now_time = date('Y-m-d H:i', strtotime(Carbon::now()));
                $ac_start_time = date('Y-m-d H:i', strtotime($room->checkInToday->ARRIVAL_TIME . $mins));

                if ($ac_start_time <= $now_time && $now_time <= $arrival_time) {
                    $nature_remo_device = NatureRemoDevice::where('ROOM_ID', $room->ROOM_ID)->with('natureRemoAppliances')->first();
                    if (!$nature_remo_device) continue;

                    foreach ($nature_remo_device['natureRemoAppliances'] as $nature_remo_appliance) {

                        if ($nature_remo_appliance->APPLIANCE_TYPE == 'AC') {
                            $nature_remo_signals = NatureRemoSignal::where('APPLIANCE_ID', $nature_remo_appliance->APPLIANCE_ID)->get();
                            foreach ($nature_remo_signals as $nature_remo_signal) {
                                if ($nature_remo_signal->SIGNAL_LABEL == $ac_mode) {
                                    $signal = NatureRemoSignal::find($nature_remo_signal->SIGNAL_ID);
                                    $nature_remo_service = app()->make('App\Services\NatureRemoService');
                                    $nature_remo_service->processCommand($signal);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
