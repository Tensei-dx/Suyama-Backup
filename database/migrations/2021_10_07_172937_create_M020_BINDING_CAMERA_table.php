<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM020BINDINGCAMERATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M020_BINDING_CAMERA', function (Blueprint $table) {
            $table->integer('BINDING_CAMERA_ID', true);
            $table->integer('SOURCE_DEVICE_ID')->nullable();
            $table->integer('TARGET_DEVICE_ID')->nullable();
            $table->json('SOURCE_DEVICE_CONDITION')->nullable();
            $table->json('CUSTOM_CONDITION')->nullable();
            $table->integer('TIME_INTERVAL')->nullable();
            $table->integer('BINDING_STATUS')->nullable();
            $table->dateTime('CREATED_AT')->nullable();
            $table->dateTime('UPDATED_AT')->nullable();
            $table->dateTime('NEXT_ACTIVITY')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M020_BINDING_CAMERA');
    }
}
