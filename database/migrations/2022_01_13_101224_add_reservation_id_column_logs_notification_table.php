<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservationIdColumnLogsNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T015_LOGS_NOTIFICATION', function (Blueprint $table) {
            $table->unsignedInteger('RESERVATION_ID')
                ->nullable()
                ->after('ROOM_ID');
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
            $table->dropColumn('RESERVATION_ID');
        });
    }
}
