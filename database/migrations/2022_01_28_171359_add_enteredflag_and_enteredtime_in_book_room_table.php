<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnteredflagAndEnteredtimeInBookRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T012_BOOK_ROOM', function (Blueprint $table) {
            $table->integer('ENTERED_FLAG')->default('0')->after('PIN');
            $table->dateTime('ENTERED_TIME')->nullable()->after('ENTERED_FLAG');
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
            $table->dropColumn('ENTERED_FLAG');
            $table->dropColumn('ENTERED_TIME');
        });
    }
}
