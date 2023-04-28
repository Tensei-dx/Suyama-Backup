<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM013SYSTEMMODULETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M013_SYSTEM_MODULE', function (Blueprint $table) {
            $table->integer('MODULE_ID', true)->comment('モジュールID');
            $table->string('MODULE_NAME', 50)->unique('MODULE_NAME_UNIQUE')->comment('モジュール名');
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
        Schema::dropIfExists('M013_SYSTEM_MODULE');
    }
}
