<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM012IRLEARNINGLISTTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M012_IR_LEARNING_LIST', function (Blueprint $table) {
            $table->integer('IR_LEARNING_LIST_ID', true)->comment('IR学習リストID');
            $table->integer('DEVICE_ID')->comment('元デバイスID');
            $table->integer('APPLIANCE_ID')->comment('アプライアンスID');
            $table->integer('OPERATION_ID')->comment('オペレーションID');
            $table->integer('LEARNING_VALUE')->comment('学習値');
            $table->dateTime('CREATED_AT')->comment('作成日時');
            $table->dateTime('UPDATED_AT')->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M012_IR_LEARNING_LIST');
    }
}
