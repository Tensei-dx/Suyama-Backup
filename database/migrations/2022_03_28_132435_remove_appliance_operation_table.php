<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Migrations;

class RemoveApplianceOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('M016_APPLIANCE_OPERATION');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $applianceOperationMigration = new CreateM016APPLIANCEOPERATIONTable();
        $applianceOperationMigration->up();
    }
}
