<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateL001SESSIONTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('L001_SESSION', function (Blueprint $table) {
            $table->text('id')->nullable()->comment('ID');
            $table->integer('USER_ID')->nullable()->comment('ユーザーID');
            $table->text('ip_address')->nullable()->comment('IPアドレス');
            $table->text('user_agent')->nullable()->comment('ユーザーエージェント');
            $table->text('payload')->nullable()->comment('ペイロード');
            $table->integer('last_activity')->nullable()->comment('最終動作');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('L001_SESSION');
    }
}
