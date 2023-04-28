<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM002AUTHLOCATIONTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M002_AUTH_LOCATION', function (Blueprint $table) {
            $table->integer('LOCATION_ID', true)->comment('ロケーションID');
            $table->integer('USER_ID')->index('fk_M003_AUTH_LOCATION_M001_USERS1_idx')->comment('ユーザーID');
            $table->integer('FLOOR_ID')->index('fk_M003_AUTH_LOCATION_M005_FLOOR1_idx')->comment('フロアID');
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
        Schema::dropIfExists('M002_AUTH_LOCATION');
    }
}
