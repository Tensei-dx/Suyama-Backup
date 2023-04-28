<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('M010_MANUFACTURER')->insert([
            [
                'MANUFACTURER_ID' => 1,
                'MANUFACTURER_NAME' => 'Wulian',
                'GW_VENDOR_ID' => null
            ],
            [
                'MANUFACTURER_ID' => 2,
                'MANUFACTURER_NAME' => 'ElectricMeter',
                'GW_VENDOR_ID' => null
            ],
            [
                'MANUFACTURER_ID' => 3,
                'MANUFACTURER_NAME' => 'BACnet',
                'GW_VENDOR_ID' => null
            ],
            [
                'MANUFACTURER_ID' => 4,
                'MANUFACTURER_NAME' => 'Axis',
                'GW_VENDOR_ID' => null
            ],
            [
                'MANUFACTURER_ID' => 5,
                'MANUFACTURER_NAME' => 'RemoteLock',
                'GW_VENDOR_ID' => null
            ],
            [
                'MANUFACTURER_ID' => 6,
                'MANUFACTURER_NAME' => 'Netvox',
                'GW_VENDOR_ID' => '70:76:FF'
            ],
            [
                'MANUFACTURER_ID' => 7,
                'MANUFACTURER_NAME' => 'NatureRemo',
                'GW_VENDOR_ID' => '2C:F4:32'
            ],
        ]);
    }
}
