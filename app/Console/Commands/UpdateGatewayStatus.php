<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateGatewayStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of all gateways';

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
        app('App\Http\Controllers\GatewayController')->onlineGateway($request);

        return 0;
    }
}
