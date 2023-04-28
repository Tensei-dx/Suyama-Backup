<?php
/*
* <System Name> iBMS
* <Program Name> deleteOldRecords.php
*
* <Create> 2019.09.09 TP Jethro
* <Update> 2019.10.31 TP Harvey
*
*/

namespace App\Console\Commands;

use App\Traits\CommonFunctions;
use Illuminate\Console\Command;

class DeleteOldRecords extends Command
{
    use CommonFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes records that are 3 months on from the database';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            app('App\Http\Controllers\LogController')->deleteOldRecords();
        } catch (\Throwable $e) {
            $type = '3';
            $instructionType = 'System Error';
            $uri = "Delete Old Records";
            $content = $uri . " : " . $e->getMessage();
            $ip = "";
            $username = "";
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }
}
