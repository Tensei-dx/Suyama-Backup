<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM003FLOORTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M003_FLOOR', function (Blueprint $table) {
            $table->integer('FLOOR_ID', true)->comment('フロアID');
            $table->string('FLOOR_NAME', 50)->unique('FLOOR_NAME_UNIQUE')->comment('フロア名');
            $table->json('FLOOR_MAP_DATA');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M003_FLOOR');
    }
}
