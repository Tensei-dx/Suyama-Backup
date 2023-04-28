<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToNatureRemoAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M027_NATURE_REMO_ACCOUNTS', function (Blueprint $table) {
            $table->string('ACCOUNT_NAME')->unique('M027_NATURE_REMO_ACCOUNTS_ACCOUNT_NAME_UNIQUE')->after('ACCOUNT_ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M027_NATURE_REMO_ACCOUNTS', function (Blueprint $table) {
            $table->dropColumn('ACCOUNT_NAME');
        });
    }
}
