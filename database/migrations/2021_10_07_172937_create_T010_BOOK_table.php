<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT010BOOKTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T010_BOOK', function (Blueprint $table) {
            $table->integer('BOOK_ID', true);
            $table->string('CONTACT_NUMBER', 50);
            $table->string('EMAIL', 50);
            $table->string('FIRST_NAME', 50)->nullable();
            $table->string('LAST_NAME', 50)->nullable();
            $table->string('ADDRESS', 50)->nullable();
            $table->dateTime('CREATED_AT')->useCurrent();
            $table->dateTime('UPDATED_AT')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T010_BOOK');
    }
}
