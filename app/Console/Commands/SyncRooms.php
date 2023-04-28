<?php

/*
 * <System Name> iBMS
 * <Program Name> syncRooms.php
 *
 * <Create> 2021.08.11 TP Uddin
 */

namespace App\Console\Commands;

use App\Traits\CommonFunctions;
use Illuminate\Console\Command;

class SyncRooms extends Command
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
    protected $signature = 'sync:rooms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync room data from third-party property management system.';

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
            app('App\Http\Controllers\Staysee\StayseeRoomController')
                ->sync();
        } catch (\Throwable $th) {
            $type = 3;
            $instructionType = 'System Error';
            $uri = 'Batch Sync Rooms';
            $content = $uri . ' : ' . $th->getMessage();
            $ip = '';
            $username = '';
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
        }
    }
}
