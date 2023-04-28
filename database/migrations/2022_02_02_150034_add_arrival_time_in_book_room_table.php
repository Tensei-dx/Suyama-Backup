<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArrivalTimeInBookRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T012_BOOK_ROOM', function (Blueprint $table) {
            $table->timestamp('ARRIVAL_TIME')
                ->useCurrent()
                ->after('CHECK_OUT_TIME');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T012_BOOK_ROOM', function (Blueprint $table) {
            $table->dropColumn('ARRIVAL_TIME');
        });
    }
}
