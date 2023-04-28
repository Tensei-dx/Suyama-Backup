<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M033_ACCESS_PEOPLE', function (Blueprint $table) {
            $table->uuid('ACCESS_PERSON_ID')->primary();
            $table->foreignId('USER_ID');
            $table->string('ACCESS_TYPE');
            $table->timestamp('ACCESS_STARTS_AT')->nullable();
            $table->timestamp('ACCESS_ENDS_AT')->nullable();
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
        Schema::dropIfExists('M033_ACCESS_PEOPLE');
    }
}
