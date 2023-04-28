<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThankYouMailSentFlagInBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T010_BOOK', function (Blueprint $table) {
            $table->addColumn('boolean', 'THANK_YOU_MAIL_SENT_FLAG')->after('ADDRESS');
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
            $table->dropColumn('THANK_YOU_MAIL_SENT_FLAG');
        });
    }
}
