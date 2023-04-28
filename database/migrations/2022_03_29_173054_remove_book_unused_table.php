<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class RemoveBookUnusedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('T011_BOOK_UNUSED');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $bookUnusedMigration = new CreateT011BOOKUNUSEDTable();
        $bookUnusedMigration->up();
    }
}
