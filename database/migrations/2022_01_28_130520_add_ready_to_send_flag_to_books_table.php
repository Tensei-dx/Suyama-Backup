<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadyToSendFlagToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T010_BOOK', function (Blueprint $table) {
            $table->addColumn('boolean', 'READY_TO_SEND_FLAG')->after('ADDRESS');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T010_BOOK', function (Blueprint $table) {
            $table->dropColumn('READY_TO_SEND_FLAG');
        });
    }
}
