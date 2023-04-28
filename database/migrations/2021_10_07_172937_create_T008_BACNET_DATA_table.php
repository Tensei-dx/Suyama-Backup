<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT008BACNETDATATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T008_BACNET_DATA', function (Blueprint $table) {
            $table->integer('BACNET_DATA_ID', true);
            $table->integer('GATEWAY_ID');
            $table->integer('MANUFACTURER_ID');
            $table->integer('BACNETDEVICE_ID');
            $table->string('DEVICE_TYPE', 45);
            $table->string('DEVICE_ID', 45);
            $table->integer('OBJECT_TYPE');
            $table->integer('OBJECT_ID');
            $table->string('OBJECT_NAME', 45);
            $table->string('DESCRIPTION', 99);
            $table->double('OBJECT_VALUE')->nullable();
            $table->boolean('REG_FLAG');
            $table->boolean('ONLINE_FLAG');
            $table->dateTime('CREATED_AT')->useCurrent();
            $table->dateTime('UPDATED_AT')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T008_BACNET_DATA');
    }
}
