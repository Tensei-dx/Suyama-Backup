<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshSpareKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remotelock:refresh-spare-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all spare PIN codes for all Remote Lock devices';

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
        app('App\Http\Controllers\SpareKeyController')->refresh();

        return 0;
    }
}
