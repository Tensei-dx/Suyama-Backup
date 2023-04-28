<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Traits\CommonFunctions;
use Illuminate\Console\Command;

class TriggerDeviceManually extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:manual-trigger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggering Manual Device';

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

    use CommonFunctions;

    public function handle()
    {
        $device = Device::where('DEVICE_TYPE', 'dust_detector')->first();
        if (isset($device)) {
            $newData = (object)[];
            $newData->status = rand(200, 300);
            $device->DATA = $newData;
            $device->save();
            $this->broadcastNewData($device);
            $this->insertDevicetoNotification($device);
        } else {
            echo "not set";
        }
        return 0;
    }
}
