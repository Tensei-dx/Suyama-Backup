<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM011EMETERTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M011_EMETER', function (Blueprint $table) {
            $table->integer('METER_ID', true)->comment('メーターID');
            $table->integer('FLOOR_ID')->comment('フロアID');
            $table->integer('ROOM_ID')->comment('ルームID');
            $table->integer('GATEWAY_ID')->nullable()->comment('ゲートウェイID');
            $table->integer('SLAVE_ID')->nullable()->comment('スレーブID');
            $table->string('SERIAL_NO', 30)->nullable()->comment('シリアル番号');
            $table->integer('REG_FLAG')->nullable()->comment('登録フラグ');
            $table->dateTime('CREATED_AT')->nullable()->useCurrent()->comment('作成日時');
            $table->dateTime('UPDATED_AT')->nullable()->useCurrent()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M011_EMETER');
    }
}
