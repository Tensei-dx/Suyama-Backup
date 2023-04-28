<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM006DEVICETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M006_DEVICE', function (Blueprint $table) {
            $table->integer('DEVICE_ID', true)->comment('デバイスID');
            $table->integer('FLOOR_ID')->nullable()->comment('フロアID');
            $table->integer('ROOM_ID')->nullable()->comment('ルームID');
            $table->integer('GATEWAY_ID')->nullable()->index('fk_M009_DEVICE_M008_GATEWAY1_idx')->comment('ゲートウェイID');
            $table->integer('MANUFACTURER_ID')->index('fk_M006_DEVICE_M011_MANUFACTURER1_idx')->comment('製造会社ID');
            $table->string('DEVICE_SERIAL_NO', 50)->comment('デバイスシリアル番号');
            $table->string('DEVICE_TYPE', 50)->comment('デバイスタイプ');
            $table->boolean('DEVICE_CATEGORY')->nullable()->comment('デバイスカテゴリ');
            $table->json('DATA')->nullable()->comment('データ');
            $table->string('DEVICE_NAME', 50)->nullable()->comment('デバイス名');
            $table->string('DEVICE_MAP_NAME', 50)->nullable()->comment('デバイスマップ名');
            $table->boolean('EMERGENCY_DEVICE')->nullable();
            $table->boolean('REG_FLAG')->default(true)->comment('登録フラグ');
            $table->boolean('ONLINE_FLAG')->comment('オンラインフラグ');
            $table->dateTime('CREATED_AT')->useCurrent()->comment('作成日時');
            $table->dateTime('UPDATED_AT')->useCurrent()->comment('更新日時');
            $table->float('LOW_VOLTAGE', 10, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M006_DEVICE');
    }
}
