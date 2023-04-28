<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT001PROCESSEDDATATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T001_PROCESSED_DATA', function (Blueprint $table) {
            $table->integer('PROCESSED_DATA_ID', true)->comment('センサ識別キー');
            $table->integer('DEVICE_ID')->index('fk_T003_PROCESSED_DATA_M009_DEVICE1_idx')->comment('デバイスID');
            $table->json('DATA')->comment('データ');
            $table->boolean('SEND_FLAG')->comment('送信フラグ');
            $table->dateTime('CREATED_AT')->useCurrent()->comment('作成日時');
            $table->dateTime('UPDATED_AT')->useCurrent()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T001_PROCESSED_DATA');
    }
}
