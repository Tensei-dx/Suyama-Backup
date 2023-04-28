<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT005AUDITLOGSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T005_AUDIT_LOGS', function (Blueprint $table) {
            $table->increments('AUDIT_LOGS_ID')->comment('監査ログID');
            $table->string('IP', 45)->nullable()->comment('IPアドレス');
            $table->string('HOST', 45)->nullable()->comment('ホスト');
            $table->string('MODULE', 45)->nullable()->comment('モジュール');
            $table->string('INSTRUCTION', 100)->nullable()->comment('インストラクション');
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
        Schema::dropIfExists('T005_AUDIT_LOGS');
    }
}
