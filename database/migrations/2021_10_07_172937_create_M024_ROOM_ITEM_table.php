<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM024ROOMITEMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M024_ROOM_ITEM', function (Blueprint $table) {
            $table->integer('ROOM_ITEM_ID', true);
            $table->integer('ROOM_TYPE_ID')->nullable();
            $table->string('ITEM_NAME', 50)->nullable();
            $table->integer('QTY')->nullable();
            $table->string('IMAGE_PATH', 50)->nullable();
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
        Schema::dropIfExists('M024_ROOM_ITEM');
    }
}
