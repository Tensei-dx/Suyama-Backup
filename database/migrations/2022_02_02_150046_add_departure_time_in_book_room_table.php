<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartureTimeInBookRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T012_BOOK_ROOM', function (Blueprint $table) {
            $table->timestamp('DEPARTURE_TIME')
                ->useCurrent()
                ->after('ARRIVAL_TIME');
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
            $table->dropColumn('DEPARTURE_TIME');
        });
    }
}
