<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_settings', function (Blueprint $table) {
            $table->id('id_setting');
            $table->string('nama_setting', 100)->unique();
            $table->text('nilai_setting');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_settings');
    }
}