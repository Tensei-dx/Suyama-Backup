<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM021APIINFOTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M021_API_INFO', function (Blueprint $table) {
            $table->integer('API_ID', true);
            $table->string('API_NAME', 50);
            $table->text('TOKEN_URL')->nullable();
            $table->text('API_URL');
            $table->text('CLIENT_ID')->nullable();
            $table->text('CLIENT_SECRET')->nullable();
            $table->text('REDIRECT_URL')->nullable();
            $table->string('GRANT_TYPE', 50);
            $table->string('CONTENT_TYPE', 50)->nullable();
            $table->text('AUTH_CODE')->nullable();
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
        Schema::dropIfExists('M021_API_INFO');
    }
}
