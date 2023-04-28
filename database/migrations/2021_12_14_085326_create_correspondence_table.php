<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrespondenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T016_CORRESPONDENCE', function (Blueprint $table) {
            $table->increments('CORRESPONDENCE_ID')->comment('対応ID');
            $table->foreignId('LOGS_NOTIF_ID')->comment('ログ通知ID');
            $table->string('CORRESPONDING_PERSON', 50)->comment('対応者');
            $table->timestamp('RESPONSE_TIME')->useCurrent()->comment('対応日時');
            $table->timestamp('CREATED_AT')->useCurrent();
            $table->timestamp('UPDATED_AT')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T016_CORRESPONDENCE');
    }
}
