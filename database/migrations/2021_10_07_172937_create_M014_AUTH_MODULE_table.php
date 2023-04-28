<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM014AUTHMODULETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M014_AUTH_MODULE', function (Blueprint $table) {
            $table->integer('AUTH_MODULE_ID', true)->comment('権限モジュールID');
            $table->integer('USER_ID')->comment('ユーザーID');
            $table->integer('MODULE_ID')->comment('モジュールID');
            $table->dateTime('CREATED_AT')->useCurrent()->comment('作成日付');
            $table->dateTime('UPDATED_AT')->useCurrent()->comment('更新日付');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M014_AUTH_MODULE');
    }
}
