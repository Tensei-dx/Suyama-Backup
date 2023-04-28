<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM007CODETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M007_CODE', function (Blueprint $table) {
            $table->integer('CODE_ID', true)->comment('コードID');
            $table->integer('MANUFACTURER_ID')->index('fk_M007_CODE_M011_MANUFACTURER1_idx')->comment('製造会社ID');
            $table->string('DEVICE_TYPE_CODE')->comment('デバイスタイプコード');
            $table->string('DEVICE_TYPE_VALUE')->comment('デバイスタイプ値');
            $table->string('STATUS_CODE')->comment('ステータスコード');
            $table->string('STATUS_VALUE')->comment('ステータス値');
            $table->string('COMMAND_CODE')->comment('コマンドコード');
            $table->string('COMMAND_VALUE')->comment('コマンド値');
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
        Schema::dropIfExists('M007_CODE');
    }
}
