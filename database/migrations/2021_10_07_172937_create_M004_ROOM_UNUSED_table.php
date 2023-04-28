<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM004ROOMUNUSEDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M004_ROOM_UNUSED', function (Blueprint $table) {
            $table->integer('ROOM_ID', true)->comment('ルームID');
            $table->integer('FLOOR_ID')->index('fk_M007_ROOM_M005_FLOOR1_idx')->comment('フロアID');
            $table->string('ROOM_NAME', 50)->comment('ルーム名');
            $table->integer('ROOM_STATUS')->default(0);
            $table->string('ROOM_MESSAGE', 45)->nullable()->default('NO MESSAGE');
            $table->integer('ROOM_TOTAL_PEOPLE')->default(0);
            $table->json('ROOM_MAP_DATA')->comment('ルームマップデータ');
            $table->integer('STATUS_ID')->nullable();
            $table->integer('ROOM_TYPE_ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M004_ROOM_UNUSED');
    }
}
