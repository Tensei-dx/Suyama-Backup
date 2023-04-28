<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Migrations;

class RemoveRoomtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('M023_ROOM_TYPE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roomTypeMigration = new CreateM023ROOMTYPETable();
        $roomTypeMigration->up();
    }
}
