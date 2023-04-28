<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT003NOTIFICATIONTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T003_NOTIFICATION', function (Blueprint $table) {
            $table->integer('NOTIFICATION_ID', true)->comment('通知ID');
            $table->integer('OBJECT_ID')->comment('オブジェクトID');
            $table->string('OBJECT_NAME', 50)->comment('オブジェクト名');
            $table->integer('ROOM_ID')->nullable();
            $table->string('SUBJECT', 50)->comment('サブジェクト');
            $table->string('CONTENT')->comment('内容');
            $table->string('NOTIFICATION_LINK')->comment('通知リンク先');
            $table->boolean('ERROR_FLAG')->nullable()->comment('エラーフラグ');
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
        Schema::dropIfExists('T003_NOTIFICATION');
    }
}
