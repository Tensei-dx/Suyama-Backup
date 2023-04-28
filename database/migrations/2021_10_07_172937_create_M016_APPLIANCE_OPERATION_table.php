<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM016APPLIANCEOPERATIONTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('M016_APPLIANCE_OPERATION', function (Blueprint $table) {
            $table->integer('OPERATION_ID')->primary()->comment('オペレーションID');
            $table->string('OPERATION_NAME', 50)->nullable()->comment('オペレーション名');
            $table->dateTime('CREATED_AT')->nullable()->comment('作成日時');
            $table->dateTime('UPDATED_AT')->nullable()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('M016_APPLIANCE_OPERATION');
    }
}
