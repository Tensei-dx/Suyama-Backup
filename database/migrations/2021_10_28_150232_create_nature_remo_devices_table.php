<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNatureRemoDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M028_NATURE_REMO_DEVICES', function (Blueprint $table) {
            $table->id('DEVICE_ID');
            $table->foreignId('ROOM_ID')->nullable();
            $table->foreignId('ACCOUNT_ID')->constrained('M027_NATURE_REMO_ACCOUNTS', 'ACCOUNT_ID')->cascadeOnDelete();
            $table->uuid('DEVICE_UUID')->unique();
            $table->string('DEVICE_NAME');
            $table->string('DEVICE_SERIAL_NO');
            $table->macAddress('MAC_ADDRESS');
            $table->json('DATA')->nullable();
            $table->boolean('NEW_FLAG');
            $table->timestamp('CREATED_AT')->useCurrent();
            $table->timestamp('UPDATED_AT')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M028_NATURE_REMO_DEVICES');
    }
}
