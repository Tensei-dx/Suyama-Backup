<?php
/*
* <System Name> iBMS
* <Program Name> DeviceController.php
*
* <Create> 2018.XX.XX Laravel Generated File
* <Update> 2019.07.15 TP Jethro Added File Header, deleted unecessary code and spaces and fixed indentation format
*          2020.03.03 TP Uddin  Added scheduled task for Bacnet Device Data
*          2021.07.16 TP Harvey Added Low Battery Checking Daily
*          2021.08.25 TP Ivin   SPRINT_04 [Task125] Late Check out Notification
*/

namespace App\Console;

use App\Models\Task;
use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // get only active tasks
        $tasks = Task::active()->get();

        foreach ($tasks as $task) {
            $schedule->command($task->COMMAND)
                ->cron($task->CRON_SCHEDULE);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return new DateTimeZone(config('app.timezone'));
    }
}
