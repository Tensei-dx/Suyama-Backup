<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM005GATEWAYTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M005_GATEWAY', function (Blueprint $table) {
            $table->integer('GATEWAY_ID', true)->comment('ゲートウェイID');
            $table->integer('FLOOR_ID')->nullable()->comment('フロアID');
            $table->integer('ROOM_ID')->nullable()->index('fk_M008_GATEWAY_M007_ROOM1_idx')->comment('ルームID');
            $table->integer('MANUFACTURER_ID')->index('fk_M006_GATEWAY_M012_MAKER1_idx')->comment('製造会社ID');
            $table->string('GATEWAY_SERIAL_NO', 50)->comment('ゲートウェイシリアル番号');
            $table->string('GATEWAY_IP', 50)->comment('ゲートウェイIP');
            $table->string('GATEWAY_NAME', 50)->nullable()->comment('ゲートウェイ名');
            $table->boolean('ONLINE_FLAG')->comment('オンラインフラグ');
            $table->boolean('REG_FLAG')->default(true)->comment('登録フラグ');
            $table->dateTime('CREATED_AT')->useCurrent()->comment('作成日時');
            $table->dateTime('UPDATED_AT')->useCurrent()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M005_GATEWAY');
    }
}
