<?php
/*
* <System Name> iBMS
* <Program Name> SaveElectricMeter.php
*
* <Create> 2018.XX.XX TP Yani
* <Update> 2019.07.04 TP Harvey Apply Coding Standard
*          2019.07.04 TP Jethro Removed unecessary code and line breaks
*
*/

namespace App\Console\Commands;

use App\Traits\CommonFunctions;
use Illuminate\Console\Command;

/**
 * <Class Name> SaveElectricMeter
 * <Overview> Save the electric meter data
 */
class SaveElectricMeter extends Command
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                      (1.0) Create a new command instance
    // handle                           (2.0) Execute the console command
    // getElectricData                  (3.0) Get the electric meter data
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:electricMeter';
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
    use CommonFunctions;
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
            $this->getElectricData();
        } catch (\Throwable $e) {
            $type = '3';
            $instructionType = 'System Error';
            $uri = "Batch Save Electric Meter";
            $content = $uri . " : " . $e->getMessage();
            $ip = "";
            $username = "";
            $this->storeLogs($type, $instructionType, $content, $ip, $username);
        }
    }
    /**
     * <Layer number> (3.0) Get the electric meter data
     * <Processing name> getElectricData
     * <Function>
     *
     * @return
     */
    public function getElectricData()
    {
        app('App\Http\Controllers\ModBusController')->createModBusData();
    }
}
