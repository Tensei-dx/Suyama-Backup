<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT006EMETERDATATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T006_EMETER_DATA', function (Blueprint $table) {
            $table->integer('EMETER_DATA_ID', true)->comment('電気メータデータID');
            $table->integer('METER_ID')->comment('メーターID');
            $table->float('CURRENT_MONTH_KWH', 10, 0)->comment('月間消費電力');
            $table->float('DAILY_CONSUMPTION_KWH', 10, 0)->comment('日間消費電力');
            $table->float('TOTAL_CONSUMPTION_KWH', 10, 0)->comment('消費電力合計');
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
        Schema::dropIfExists('T006_EMETER_DATA');
    }
}
