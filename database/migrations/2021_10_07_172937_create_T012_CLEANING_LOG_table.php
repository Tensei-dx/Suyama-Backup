<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT012CLEANINGLOGTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T012_CLEANING_LOG', function (Blueprint $table) {
            $table->integer('CLEANING_LOG_ID', true);
            $table->integer('ROOM_ID')->nullable();
            $table->integer('BOOK_ID')->nullable();
            $table->integer('STATUS_ID')->nullable();
            $table->string('INSTRUCTION', 200)->nullable();
            $table->dateTime('DUE_TIME')->nullable();
            $table->dateTime('START_TIME')->nullable();
            $table->dateTime('END_TIME')->nullable();
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
        Schema::dropIfExists('T012_CLEANING_LOG');
    }
}
