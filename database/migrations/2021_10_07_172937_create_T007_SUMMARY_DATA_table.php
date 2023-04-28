<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT007SUMMARYDATATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T007_SUMMARY_DATA', function (Blueprint $table) {
            $table->string('SENSOR_TYPE', 45)->nullable();
            $table->float('SUMMARY_DATA', 10, 0)->nullable();
            $table->string('UNIT', 45)->nullable();
            $table->dateTime('CREATED_AT')->nullable();
            $table->dateTime('UPDATED_AT')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T007_SUMMARY_DATA');
    }
}
