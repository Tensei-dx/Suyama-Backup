<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT012BOOKROOMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T012_BOOK_ROOM', function (Blueprint $table) {
            $table->integer('BOOK_ROOM_ID', true);
            $table->integer('BOOK_ID');
            $table->integer('ROOM_ID');
            $table->integer('USER_ID');
            $table->dateTime('CHECK_IN_TIME')->nullable();
            $table->dateTime('CHECK_OUT_TIME')->nullable();
            $table->string('PIN', 10)->nullable();
            $table->dateTime('CREATED_AT')->nullable();
            $table->dateTime('UPDATED_AT')->nullable();
            $table->text('ROOM_MESSAGE')->nullable();
            $table->integer('ACTIVE')->default(0);
            $table->string('REMOTE_LOCK_GUEST_UUID', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T012_BOOK_ROOM');
    }
}
