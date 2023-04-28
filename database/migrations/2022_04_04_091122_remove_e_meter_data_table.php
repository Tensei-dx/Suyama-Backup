<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEMeterDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('T006_EMETER_DATA');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $emeterDataMigration = new CreateT006EMETERDATATable();
        $emeterDataMigration->up();
    }
}
