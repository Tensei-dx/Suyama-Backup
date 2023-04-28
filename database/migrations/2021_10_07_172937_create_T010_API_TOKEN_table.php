<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT010APITOKENTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T010_API_TOKEN', function (Blueprint $table) {
            $table->integer('TOKEN_ID', true);
            $table->integer('API_ID');
            $table->string('TOKEN_NAME', 50);
            $table->text('ACCESS_TOKEN');
            $table->text('REFRESH_TOKEN')->nullable();
            $table->dateTime('EXPIRED_AT');
            $table->dateTime('CREATED_AT')->useCurrent();
            $table->dateTime('UPDATED_AT')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T010_API_TOKEN');
    }
}
