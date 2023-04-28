<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of devices based on the status of their respective gateways';

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
        // $request = new \Illuminate\Http\Request();
        // app('App\Http\Controllers\DeviceController')->onlineDeviceByGateway();

        return 0;
    }
}
