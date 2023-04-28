<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateNetvoxDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netvox:update-device-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test connection to NetVox Cloud API, then report the connection status';

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
        app('App\Http\Controllers\NotificationController')->checkNetvoxConnectivity();

        return 0;
    }
}
