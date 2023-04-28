<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('M022_STATUS_CODE')->insert([
            [
                'STATUS_ID' => 101,
                'STATUS_NAME' => 'CURRENTLY CLEANING'
            ],
            [
                'STATUS_ID' => 102,
                'STATUS_NAME' => 'TO CLEAN'
            ],
            [
                'STATUS_ID' => 103,
                'STATUS_NAME' => 'FINISH CLEANING'
            ],
            [
                'STATUS_ID' => 104,
                'STATUS_NAME' => 'NO NEED CLEANING'
            ],
            [
                'STATUS_ID' => 201,
                'STATUS_NAME' => 'CHECKED IN'
            ],
            [
                'STATUS_ID' => 202,
                'STATUS_NAME' => 'CHECK OUT'
            ],
            [
                'STATUS_ID' => 203,
                'STATUS_NAME' => 'AVAILABLE'
            ],
            [
                'STATUS_ID' => 204,
                'STATUS_NAME' => 'UNAVAILABLE'
            ],
            [
                'STATUS_ID' => 205,
                'STATUS_NAME' => 'BOOKED'
            ],
        ]);
    }
}
