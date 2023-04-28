<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM010MANUFACTURERTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M010_MANUFACTURER', function (Blueprint $table) {
            $table->integer('MANUFACTURER_ID', true)->comment('製造会社ID');
            $table->string('MANUFACTURER_NAME', 50)->comment('製造会社名');
            $table->string('GW_VENDOR_ID', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M010_MANUFACTURER');
    }
}
