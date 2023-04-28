<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T019_PARAM_SETTINGS', function (Blueprint $table) {
            $table->id('PARAM_ID');
            $table->tinyInteger('AC_AUTO_START');
            $table->unsignedSmallInteger('AC_START_OFFSET', false);
            $table->unsignedTinyInteger('AC_MODE', false);
            $table->unsignedTinyInteger('RL_NUM_PIN', false);
            $table->text('MAIL_THANKYOU_CONTENT');
            $table->unsignedTinyInteger('MAIL_REMIND_OFFSET', false);
            $table->text('WIFI_NAME');
            $table->text('WIFI_PASSWORD');
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
        Schema::dropIfExists('T019_PARAM_SETTINGS');
    }
}
