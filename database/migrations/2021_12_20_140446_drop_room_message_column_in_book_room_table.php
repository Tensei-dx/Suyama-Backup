<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRoomMessageColumnInBookRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T012_BOOK_ROOM', function (Blueprint $table) {
            $table->dropColumn('ROOM_MESSAGE');
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
            $table->addColumn('text', 'ROOM_MESSAGE')->after('UPDATED_AT');
        });
    }
}
