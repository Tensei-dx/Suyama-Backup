<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM023ROOMTYPETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M023_ROOM_TYPE', function (Blueprint $table) {
            $table->integer('ROOM_TYPE_ID')->primary();
            $table->string('NAME', 50)->nullable();
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
        Schema::dropIfExists('M023_ROOM_TYPE');
    }
}
