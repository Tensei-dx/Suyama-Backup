<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT017GUESTCARDDATATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T017_GUESTCARD_DATA', function (Blueprint $table) {
            $table->bigIncrements('GUESTCARD_DATA_ID');
            $table->smallInteger('BOOK_ID')->nullable();
            $table->string('BOOK_NO')->nullable();
            $table->integer('MEMBERS_ID')->unique()->nullable();
            $table->tinyInteger('MEMBER_TYPE')->nullable();
            $table->string('NAME', 100)->nullable();
            $table->tinyInteger('SEX')->nullable();
            $table->smallInteger('AGE')->nullable();
            $table->string('OCCUPATION', 100)->nullable();
            $table->string('TEL', 20)->nullable();
            $table->string('EMAIL', 100)->nullable();
            $table->string('ADDRESS', 200)->nullable();
            $table->string('PASSPORT_URL', 250)->nullable();
            $table->string('NATIONALITY', 150)->nullable();
            $table->string('PASSPORT_NO', 100)->nullable();
            $table->string('PREVIOUS_PLACE', 200)->nullable();
            $table->string('NEXT_DESTINATION', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T013_GUESTCARD_DATA');
    }
}
