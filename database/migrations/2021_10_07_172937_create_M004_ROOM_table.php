<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM004ROOMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M004_ROOM', function (Blueprint $table) {
            $table->integer('ROOM_ID')->primary();
            $table->integer('FLOOR_ID');
            $table->string('ROOM_NAME', 50);
            $table->integer('MAX_OCCUPANCY');
            $table->integer('STATUS_ID');
            $table->dateTime('CREATED_AT');
            $table->dateTime('UPDATED_AT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M004_ROOM');
    }
}
