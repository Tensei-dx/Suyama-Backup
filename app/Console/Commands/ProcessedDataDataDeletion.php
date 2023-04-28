<?php

namespace App\Console\Commands;

use App\Models\ProcessedData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessedDataDataDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:process-data';

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
        $deleteData = ProcessedData::where('UPDATED_AT', '<=', Carbon::now()->subDays(30))->limit(3000)->get();
        foreach ($deleteData as $data) {
            $data->delete();
        }
    }
}
