<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('M031_ROOM_MESSAGES')->insert([
            [
                'MESSAGE_ID' => 1,
                'MESSAGE' => 'management.roomStats.noMessage'
            ],
            [
                'MESSAGE_ID' => 2,
                'MESSAGE' => 'management.roomStats.dontDisturb'
            ],
            [
                'MESSAGE_ID' => 3,
                'MESSAGE' => 'management.roomStats.cleanUpTime'
            ]
        ]);
    }
}
