<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPmsUpdatedAtColumnInBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T010_BOOK', function (Blueprint $table) {
            $table->timestamp('PMS_UPDATED_AT')->after('ADDRESS');
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
            $table->dropColumn('PMS_UPDATED_AT');
        });
    }
}
