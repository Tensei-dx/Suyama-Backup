<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Migrations;

class RemoveRoomitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('M024_ROOM_ITEM');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roomItemMigration = new CreateM024ROOMITEMTable();
        $roomItemMigration->up();
    }
}
