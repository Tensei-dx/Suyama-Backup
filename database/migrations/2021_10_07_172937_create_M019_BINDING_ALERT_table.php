<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM019BINDINGALERTTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M019_BINDING_ALERT', function (Blueprint $table) {
            $table->integer('BINDING_ALERT_ID', true);
            $table->integer('SOURCE_DEVICE_ID');
            $table->json('SOURCE_DEVICE_CONDITION')->nullable();
            $table->json('TARGET_USER_ALERT')->nullable();
            $table->integer('TIME_INTERVAL')->nullable();
            $table->boolean('BINDING_STATUS')->nullable();
            $table->dateTime('CREATED_AT')->nullable();
            $table->dateTime('UPDATED_AT')->nullable();
            $table->dateTime('LAST_ACTIVITY')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M019_BINDING_ALERT');
    }
}
