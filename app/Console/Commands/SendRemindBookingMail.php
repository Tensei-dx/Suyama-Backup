<?php

namespace App\Console\Commands;

use App\Services\StayseeReservationService;
use Illuminate\Console\Command;

class SendRemindBookingMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:remind-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder email for booking';

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
        $service = new StayseeReservationService();
        $service->sendRemindMail();
        return 0;
    }
}
