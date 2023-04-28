<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT004USERNOTIFICATIONTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T004_USER_NOTIFICATION', function (Blueprint $table) {
            $table->integer('USER_NOTIFICATION_ID', true)->comment('ユーザー通知ID');
            $table->integer('USER_ID')->comment('ユーザーID');
            $table->integer('NOTIFICATION_ID')->index('fk_USER_NOTIF_T005_NOTIFICATION1_idx')->comment('通知ID');
            $table->boolean('SEEN_FLAG')->comment('確認フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T004_USER_NOTIFICATION');
    }
}
