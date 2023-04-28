<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEMeterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('M011_EMETER');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $emeterMigration = new CreateM011EMETERTable();
        $emeterMigration->up();
    }
}
