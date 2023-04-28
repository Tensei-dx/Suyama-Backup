<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT011BOOKUNUSEDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T011_BOOK_UNUSED', function (Blueprint $table) {
            $table->integer('BOOK_ID', true);
            $table->integer('ROOM_ID')->nullable();
            $table->integer('USER_ID')->nullable();
            $table->string('FIRST_NAME', 50)->nullable();
            $table->string('LAST_NAME', 50)->nullable();
            $table->dateTime('CHECK_IN_TIME')->nullable();
            $table->dateTime('CHECK_OUT_TIME')->nullable();
            $table->integer('PIN')->nullable();
            $table->string('CONTACT_NUMBER', 50)->nullable();
            $table->string('EMAIL', 50)->nullable();
            $table->string('ADDRESS', 50)->nullable();
            $table->string('PRICE', 10)->nullable();
            $table->integer('NUMBER_OF_PEOPLE')->nullable();
            $table->string('BOOK_THRU', 20)->nullable();
            $table->string('PAYMENT_STATUS', 20)->nullable();
            $table->string('MODE_OF_PAYMENT', 20)->nullable();
            $table->string('ID_TYPE', 20)->nullable();
            $table->string('ID_PHOTO_FRONT', 100)->nullable();
            $table->string('ID_PHOTO_BACK', 100)->nullable();
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
        Schema::dropIfExists('T011_BOOK_UNUSED');
    }
}
