<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWaktuSholatTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_waktu_sholat', function (Blueprint $table) {
            $table->id('id_waktu_sholat');
            $table->time('dzuhur')->nullable();
            $table->time('akhir_dzuhur')->nullable();
            $table->time('ashar')->nullable();
            $table->time('akhir_ashar')->nullable();
            $table->integer('selang_dzuhur')->nullable();
            $table->integer('selang_ashar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_waktu_sholat');
    }
}