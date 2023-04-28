<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingCreatedMailSentFlagInBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T010_BOOK', function (Blueprint $table) {
            $table->addColumn('boolean', 'BOOKING_CREATED_MAIL_SENT_FLAG')
                ->after('PAID_FLAG');
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
            $table->dropColumn('BOOKING_CREATED_MAIL_SENT_FLAG');
        });
    }
}
