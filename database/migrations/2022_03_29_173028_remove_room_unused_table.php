<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRoomUnusedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('M004_ROOM_UNUSED');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roomUnusedMigration = new CreateM004ROOMUNUSEDTable();
        $roomUnusedMigration->up();
    }
}
