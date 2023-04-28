<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM025TASKLISTTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M025_TASK_LIST', function (Blueprint $table) {
            $table->integer('TASK_ID')->primary();
            $table->integer('ROOM_ID')->nullable();
            $table->string('TASK_NAME', 50)->nullable();
            $table->string('TASK_CONTENT', 200)->nullable();
            $table->string('IMAGE_PATH', 100)->nullable();
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
        Schema::dropIfExists('M025_TASK_LIST');
    }
}
