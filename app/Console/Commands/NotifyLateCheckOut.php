<?php
/*
* <System Name> iBMS
* <Program Name> lateCheckoutNotifications.php
*
* <Create> 2021.08.25 TP Ivin
*
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * <Class Name> lateCheckoutNotifications
 * <Overview> Check late check out Guest
 */
class NotifyLateCheckOut extends Command
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                      (1.0) Create a new command instance
    // handle                           (2.0) Execute the console command

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:late-check-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * <Layer number> (1.0) Create a new command instance.
     * <Processing name> __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * <Layer number> (2.0) Execute the console command.
     * <Processing name> handle
     *
     * @return mixed
     */
    public function handle()
    {
        $request = new \Illuminate\Http\Request();
        app('App\Http\Controllers\ClientController')->lateCheckoutNotifications();

        return 0;
    }
}
