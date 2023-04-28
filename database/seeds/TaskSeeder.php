<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear data before running seeder
        DB::table('M032_TASKS')->truncate();

        DB::table('M032_TASKS')->insert([
            [
                'TASK_ID' => 1,
                'COMMAND' => 'gateway:update-status',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => false,
            ],
            [
                'TASK_ID' => 2,
                'COMMAND' => 'binding:check-next-activity',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => false,
            ],
            [
                'TASK_ID' => 3,
                'COMMAND' => 'delete:old-records',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => false,
            ],
            [
                'TASK_ID' => 4,
                'COMMAND' => 'generate:report',
                'CRON_SCHEDULE' => '0 1 * * *',
                'ACTIVE_FLAG' => false,
            ],
            [
                'TASK_ID' => 5,
                'COMMAND' => 'axis:update-people-counter-data',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => false
            ],
            [
                'TASK_ID' => 6,
                'COMMAND' => 'device:manual-trigger',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => false
            ],
            [
                'TASK_ID' => 7,
                'COMMAND' => 'sync:reservations',
                'CRON_SCHEDULE' => '0 * * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 8,
                'COMMAND' => 'sync:rooms',
                'CRON_SCHEDULE' => '50 * * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 9,
                'COMMAND' => 'remotelock:update-device-battery-level',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 10,
                'COMMAND' => 'notify:late-check-out',
                'CRON_SCHEDULE' => '*/15 * * * *',
                'ACTIVE_FLAG' => false
            ],
            [
                'TASK_ID' => 11,
                'COMMAND' => 'report:late-check-out',
                'CRON_SCHEDULE' => '* * * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 12,
                'COMMAND' => 'remotelock:update-device-status',
                'CRON_SCHEDULE' => '0 * * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 13,
                'COMMAND' => 'netvox:update-device-status',
                'CRON_SCHEDULE' => '0 */2 * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 14,
                'COMMAND' => 'delete:booking-data',
                'CRON_SCHEDULE' => '0 1 * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 15,
                'COMMAND' => 'remotelock:refresh-spare-keys',
                'CRON_SCHEDULE' => '0 0 */1 * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 16,
                'COMMAND' => 'delete:process-data',
                'CRON_SCHEDULE' => '0 1 * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 17,
                'COMMAND' => 'report:late-check-in',
                'CRON_SCHEDULE' => '*/10 * * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 18,
                'COMMAND' => 'send:remind-mail',
                'CRON_SCHEDULE' => '0 9 * * *',
                'ACTIVE_FLAG' => true
            ],
            [
                'TASK_ID' => 19,
                'COMMAND' => 'ac:auto-start',
                'CRON_SCHEDULE' => '*/30 * * * *',
                'ACTIVE_FLAG' => true
            ]
        ]);
    }
}
