<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegFlagInNatureRemoDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M028_NATURE_REMO_DEVICES', function (Blueprint $table) {
            $table->boolean('REG_FLAG')->after('NEW_FLAG');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M028_NATURE_REMO_DEVICES', function (Blueprint $table) {
            $table->dropColumn('REG_FLAG');
        });
    }
}
