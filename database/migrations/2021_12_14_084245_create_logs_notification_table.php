<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T015_LOGS_NOTIFICATION', function (Blueprint $table) {
            $table->increments('LOGS_NOTIF_ID')->comment('ログ通知ID');
            $table->string('MESSAGE_ID', 45)->nullable()->comment('メッセージID');
            $table->integer('ROOM_ID', false)->comment('ルームID');
            $table->tinyInteger('EVENT_STATUS', false)->comment('イベントステータス');
            $table->timestamp('CREATED_AT')->useCurrent()->comment('作成日時');
            $table->timestamp('UPDATED_AT')->useCurrent()->comment('作成日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T015_LOGS_NOTIFICATION');
    }
}
