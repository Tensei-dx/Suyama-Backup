<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRemoteLockDeviceBatteryLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remotelock:update-device-battery-level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update battery level of Remote Lock devices';

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
        $request = new \Illuminate\Http\Request();
        app('App\Http\Controllers\RemoteLockController')->getRemoteLockBatteryLevel($request);

        return 0;
    }
}
