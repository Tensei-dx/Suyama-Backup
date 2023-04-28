<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM008BINDINGTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M008_BINDING', function (Blueprint $table) {
            $table->integer('BINDING_ID', true)->comment('バインディングID');
            $table->integer('SOURCE_DEVICE_ID')->comment('元デバイスID');
            $table->integer('TARGET_DEVICE_ID')->comment('先デバイスID');
            $table->integer('BINDING_LIST_ID')->nullable()->comment('バインディングリストID');
            $table->json('SOURCE_DEVICE_CONDITION')->nullable();
            $table->json('CUSTOM_CONDITION')->nullable();
            $table->integer('TIME_INTERVAL')->nullable()->comment('時間間隔');
            $table->integer('BINDING_STATUS')->default(1)->comment('バインディングステータス');
            $table->integer('MANUAL')->default(0)->comment('マニュアル');
            $table->dateTime('CREATED_AT')->useCurrent()->comment('作成日時');
            $table->dateTime('UPDATED_AT')->useCurrent()->comment('更新日時');
            $table->dateTime('LAST_ACTIVITY')->useCurrent()->comment('最終動作');
            $table->dateTime('NEXT_ACTIVITY')->useCurrent()->comment('次動作');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M008_BINDING');
    }
}
