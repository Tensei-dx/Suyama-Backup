<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM017BACNETTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M017_BACNET', function (Blueprint $table) {
            $table->integer('BACNETDEVICE_ID', true);
            $table->integer('FLOOR_ID');
            $table->integer('ROOM_ID');
            $table->integer('MANUFACTURER_ID');
            $table->integer('GATEWAY_ID');
            $table->string('DEVICE_TYPE', 15)->nullable();
            $table->string('DEVICE_ID', 15);
            $table->string('DEVICE_SERIAL_NO', 15)->unique('DEVICE_SERIAL_NO_UNIQUE');
            $table->boolean('DEVICE_CATEGORY')->nullable();
            $table->string('DEVICE_NAME', 15)->nullable()->unique('DEVICE_NAME_UNIQUE');
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
        Schema::dropIfExists('M017_BACNET');
    }
}
