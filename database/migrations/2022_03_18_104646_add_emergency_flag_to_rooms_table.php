<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmergencyFlagToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M004_ROOM', function (Blueprint $table) {
            $table->boolean('EMERGENCY_FLAG')->after('STATUS_ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M004_ROOM', function (Blueprint $table) {
            $table->dropColumn('EMERGENCY_FLAG');
        });
    }
}
