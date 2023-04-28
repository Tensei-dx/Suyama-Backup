<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM018BACNETDEVICELISTTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M018_BACNET_DEVICE_LIST', function (Blueprint $table) {
            $table->integer('PRED_DEVICE_ID', true);
            $table->integer('PRED_DEVICE_NUMBER');
            $table->string('PRED_DEVICE_NAME', 45);
            $table->integer('OBJECT_TYPE');
            $table->integer('OBJECT_ID');
            $table->string('OBJECT_NAME', 45);
            $table->string('DESCRIPTION', 99);
            $table->dateTime('CREATED_AT')->nullable()->useCurrent();
            $table->dateTime('UPDATED_AT')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M018_BACNET_DEVICE_LIST');
    }
}
