<?php
/*
* <System Name> iBMS
* <Program Name> BindingCheckNextActivity.php
*
* <Create> 2018.XX.XX TP Yani
* <Update> 2019.07.04 TP Harvey Apply Coding Standard
*          2019.07.04 TP Jethro Removed unecessary code and line breaks
*
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * <Class Name> BindingCheckNextActivity
 * <Overview> Check binding schedule
 */
class BindingCheckNextActivity extends Command
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                      (1.0) Create a new command instance
    // handle                           (2.0) Execute the console command
    // getActivity                      (3.0) Retrieve binding from database

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binding:check-next-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger Binding Function';

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
        try {
            $this->getActivity();
        } catch (\Throwable $e) {
            $type = '3';
            $instructionType = 'System Error';
            $uri = "Batch Binding";
            $content = $uri . " : " . $e->getMessage();
            $ip = "";
            $username = "";
            app('App\Traits\CommonFunctions')->storeLogs($type, $instructionType, $content, $ip, $username);
            return $e->getMessage();
        }
    }

    /**
     * <Layer number> (3.0) Retrieve all bindings from database
     * <Processing name> getActivity
     * <Function>
     *
     * @return
     */
    public function getActivity()
    {
        app('App\Http\Controllers\BindingController')->checkNextActivity();
    }
}
