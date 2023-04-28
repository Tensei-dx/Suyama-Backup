<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M032_TASKS', function (Blueprint $table) {
            $table->id('TASK_ID');
            $table->string('COMMAND');
            $table->string('CRON_SCHEDULE');
            $table->boolean('ACTIVE_FLAG')->default(false);
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
        Schema::dropIfExists('M032_TASKS');
    }
}
