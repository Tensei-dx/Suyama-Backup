<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRemoteLockDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remotelock:update-device-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of Remote Lock devices, then report if the device is disconnected';

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
        $remoteLock = app()->make('App\Http\Controllers\RemoteLockController');
        $remoteLock->checkRemoteLockConnection();

        return 0;
    }
}
