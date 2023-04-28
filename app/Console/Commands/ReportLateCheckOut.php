<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReportLateCheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:late-check-out';

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
        $checker = app()->make('App\Http\Controllers\NotificationController');
        $checker->lateCheckOutChecker();
    }
}
