<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT014APPLOGSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T014_APPLOGS', function (Blueprint $table) {
            $table->increments('APPLOGS_ID')->comment('');
            $table->foreignId('USER_ID')->nullable()->comment('ユーザーID');
            $table->string('CONTENT', 45)->nullable()->comment('');
            $table->string('EVENT', 45)->nullable()->comment('エベント');
            $table->timestamp('CREATED_AT')->useCurrent()->comment('作成日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T014_APPLOGS');
    }
}
