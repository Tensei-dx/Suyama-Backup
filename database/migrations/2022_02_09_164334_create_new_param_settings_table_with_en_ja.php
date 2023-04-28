<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNewParamSettingsTableWithEnJa extends Migration
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
            $table->text('MAIL_THANKYOU_EN_CONTENT');
            $table->text('MAIL_THANKYOU_JA_CONTENT');
            $table->unsignedTinyInteger('MAIL_REMIND_OFFSET', false);
            $table->text('WIFI_NAME');
            $table->text('WIFI_PASSWORD');
            $table->timestamp('CREATED_AT')->useCurrent();
            $table->timestamp('UPDATED_AT')->useCurrent();
        });

        DB::table('T019_PARAM_SETTINGS')->insert(
            array(
                'PARAM_ID' => 1,
                'AC_AUTO_START' => 0,
                'AC_START_OFFSET' => 60,
                'AC_MODE' => 0,
                'RL_NUM_PIN' => 6,
                'MAIL_THANKYOU_EN_CONTENT' => "Please entry the ThankYou mail text here.",
                'MAIL_THANKYOU_JA_CONTENT' => "ここにお礼メールのテキストを入力してください。",
                'MAIL_REMIND_OFFSET' => 1,
                'WIFI_NAME' => "wi-fi name",
                'WIFI_PASSWORD' => "password"
            )
        );
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
