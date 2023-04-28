<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetRoomIdColumnAsNullableInLogsNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T015_LOGS_NOTIFICATION', function (Blueprint $table) {
            $table->integer('ROOM_ID', false)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T015_LOGS_NOTIFICATION', function (Blueprint $table) {
            $table->integer('ROOM_ID', false)->nullable(false)->change();
        });
    }
}
