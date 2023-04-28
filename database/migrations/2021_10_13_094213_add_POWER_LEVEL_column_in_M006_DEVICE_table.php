<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPowerLevelColumnInM006DEVICETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M006_DEVICE', function (Blueprint $table) {
            $table->float('POWER_LEVEL')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M006_DEVICE', function (Blueprint $table) {
            $table->dropColumn('POWER_LEVEL');  //Delete column
        });
    }
}
