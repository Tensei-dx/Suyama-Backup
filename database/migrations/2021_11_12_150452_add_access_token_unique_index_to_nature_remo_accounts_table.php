<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessTokenUniqueIndexToNatureRemoAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M027_NATURE_REMO_ACCOUNTS', function (Blueprint $table) {
            $table->unique('ACCESS_TOKEN', 'M027_NATURE_REMO_ACCOUNTS_ACCESS_TOKEN_UNIQUE');
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
            $table->dropIndex('M027_NATURE_REMO_ACCOUNTS_ACCESS_TOKEN_UNIQUE');
        });
    }
}
