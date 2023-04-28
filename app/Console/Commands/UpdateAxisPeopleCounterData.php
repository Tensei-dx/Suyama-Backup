<?php

/**
 * <System Name> iBMS
 * <Program Name> updatePeopleCounterData.php
 *
 * <Create> 2020.11.26 TP Uddin
 *
 */

namespace App\Console\Commands;

use App\Traits\CommonFunctions;
use Illuminate\Console\Command;

class UpdateAxisPeopleCounterData extends Command
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (1.0) Create a new command instance.
    // handle                           (2.0) Execute the console command

    use CommonFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'axis:update-people-counter-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Axis Camera people counter data';

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
            app('App\Http\Controllers\ManagementController')->peopleCounterData();
        } catch (\Throwable $th) {
            $type = 3;
            $instructionType = 'System Error';
            $uri = 'Batch Update People Counter Data';
            $content = $uri . ' : ' . $th->getMessage();
            $ip = '';
            $username = '';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
        }

        return 0;
    }
}
