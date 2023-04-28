<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Migrations;

class RemoveIrLearningListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('M012_IR_LEARNING_LIST');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $irLearningListMigration = new CreateM012IRLEARNINGLISTTable();
        $irLearningListMigration->up();
    }
}
