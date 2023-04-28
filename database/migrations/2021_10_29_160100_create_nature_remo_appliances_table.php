<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNatureRemoAppliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M029_NATURE_REMO_APPLIANCES', function (Blueprint $table) {
            $table->id('APPLIANCE_ID');
            $table->foreignId('DEVICE_ID')->constrained('M028_NATURE_REMO_DEVICES', 'DEVICE_ID')->cascadeOnDelete();
            $table->uuid('APPLIANCE_UUID')->unique();
            $table->string('APPLIANCE_TYPE');
            $table->string('APPLIANCE_NAME');
            $table->json('APPLIANCE_SETTINGS')->nullable();
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
        Schema::dropIfExists('M029_NATURE_REMO_APPLIANCES');
    }
}
