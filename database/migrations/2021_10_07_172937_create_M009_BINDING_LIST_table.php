<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM009BINDINGLISTTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M009_BINDING_LIST', function (Blueprint $table) {
            $table->integer('BINDING_LIST_ID', true)->comment('バインディングリストID');
            $table->string('SOURCE_DEVICE_TYPE', 50)->comment('元デバイスタイプ');
            $table->string('SOURCE_DEVICE_CONDITION', 50)->comment('元デバイス状態');
            $table->string('SOURCE_DEVICE_CODE', 50)->nullable()->comment('元デバイスコード');
            $table->string('SOURCE_DEVICE_COMMAND', 50)->nullable()->comment('元デバイスコマンド');
            $table->string('SOURCE_DEVICE_VALUE', 50)->nullable()->comment('元デバイス値');
            $table->string('TARGET_DEVICE_TYPE', 50)->comment('先デバイスタイプ');
            $table->string('TARGET_DEVICE_CONDITION', 50)->comment('先デバイス状態');
            $table->string('TARGET_DEVICE_COMMAND', 50)->nullable()->comment('先デバイスコマンド');
            $table->string('TARGET_DEVICE_VALUE', 50)->nullable()->comment('先デバイス値');
            $table->string('TARGET_DEVICE_CATEGORY', 50)->nullable()->comment('先デバイスカテゴリ');
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
        Schema::dropIfExists('M009_BINDING_LIST');
    }
}
