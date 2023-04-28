<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditEmergencyFlagToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M004_ROOM', function (Blueprint $table) {
            $table->boolean('EMERGENCY_FLAG')->default(0)->change();
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
            $table->boolean('EMERGENCY_FLAG')->change();
        });
    }
}
