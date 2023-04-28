<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNatureRemoSignalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M030_NATURE_REMO_SIGNALS', function (Blueprint $table) {
            $table->id('SIGNAL_ID');
            $table->foreignId('APPLIANCE_ID')->constrained('M029_NATURE_REMO_APPLIANCES', 'APPLIANCE_ID')->cascadeOnDelete();
            $table->uuid('SIGNAL_UUID')->nullable();
            $table->string('SIGNAL_NAME');
            $table->string('SIGNAL_LABEL')->nullable();
            $table->string('SIGNAL_GROUP')->nullable();
            $table->text('SIGNAL_DATA')->nullable();
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
        Schema::dropIfExists('M030_NATURE_REMO_SIGNALS');
    }
}
