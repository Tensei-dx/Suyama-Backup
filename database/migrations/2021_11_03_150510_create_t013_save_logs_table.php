<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT013SaveLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T013_SAVELOGS', function (Blueprint $table) {
            $table->increments('LOGS_ID')->comment('ログID');
            $table->string('EVENTS', 45)->nullable()->comment('エベント');
            $table->string('LOG_LEVEL', 45)->nullable()->comment('ログレベル');
            $table->string('ERROR_TYPE', 45)->nullable()->comment('エラータイプ');
            $table->foreignId('USER_ID')->nullable()->comment('ユーザーID');
            $table->text('REQUEST_TARGET')->nullable()->comment('リクエスト対象');
            $table->string('PROCESSING_OBJECT')->nullable()->comment('処理対象');
            $table->integer('ERROR_CODE')->nullable()->comment('エラーコード');
            $table->text('PROCESSING_DETAILS')->nullable()->comment('処理内容');
            $table->timestamp('CREATED_AT')->useCurrent()->comment('作成日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T013_SAVELOGS');
    }
}
