<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM015APPLIANCESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M015_APPLIANCES', function (Blueprint $table) {
            $table->integer('APPLIANCE_ID', true)->comment('アプライアンスID');
            $table->string('APPLIANCE_NAME', 13)->comment('アプライアンス名');
            $table->string('APPLIANCE_TYPE', 13)->comment('アプライアンスタイプ');
            $table->string('BRAND_NAME', 13)->comment('ブランド名');
            $table->dateTime('CREATED_AT')->comment('作成日時');
            $table->dateTime('UPDATED_AT')->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M015_APPLIANCES');
    }
}
