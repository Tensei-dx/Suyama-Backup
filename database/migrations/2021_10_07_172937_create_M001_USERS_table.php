<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM001USERSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M001_USERS', function (Blueprint $table) {
            $table->integer('USER_ID', true)->comment('ユーザーID');
            $table->string('LAST_NAME', 50)->nullable()->comment('姓');
            $table->string('FIRST_NAME', 50)->nullable()->comment('名');
            $table->string('USERNAME', 50)->comment('ユーザー名');
            $table->string('EMAIL', 50)->nullable()->comment('メールアドレス');
            $table->string('PASSWORD', 100)->comment('パスワード');
            $table->string('CONTACT_NUMBER', 50)->nullable();
            $table->json('ALLOW_ALERT_NOTIFICATION')->nullable();
            $table->integer('USER_TYPE')->comment('ユーザータイプ');
            $table->string('REMEMBER_TOKEN', 100)->nullable()->comment('記憶トークン');
            $table->boolean('REG_FLAG')->comment('登録フラグ');
            $table->string('USER_LOGO', 100)->nullable()->comment('ユーザーロゴ');
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
        Schema::dropIfExists('M001_USERS');
    }
}
