<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpareKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T018_SPARE_KEYS', function (Blueprint $table) {
            $table->id('SPARE_KEY_ID');
            $table->foreignId('ROOM_ID');
            $table->foreignId('REMOTE_LOCK_DEVICE_ID');
            $table->uuid('REMOTE_LOCK_USER_ID');
            $table->string('PIN_CODE');
            $table->dateTime('STARTS_AT');
            $table->dateTime('ENDS_AT');
            $table->timestamp('CREATED_AT')->useCurrent();
            $table->timestamp('UPDATED_AT')->useCurrent();
            $table->softDeletes('DELETED_AT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T018_SPARE_KEYS');
    }
}
