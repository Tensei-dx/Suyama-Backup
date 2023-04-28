<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT002LOGSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T002_LOGS', function (Blueprint $table) {
            $table->increments('LOGS_ID')->comment('ログID');
            $table->integer('TYPE')->nullable()->comment('タイプ');
            $table->string('INSTRUCTION_TYPE', 45)->nullable()->comment('インストラクションタイプ');
            $table->string('CONTENT')->nullable()->comment('内容');
            $table->string('IP', 45)->nullable()->comment('IPアドレス');
            $table->string('HOST', 45)->nullable()->comment('ホスト');
            $table->dateTime('CREATED_AT')->nullable()->comment('作成日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T002_LOGS');
    }
}
