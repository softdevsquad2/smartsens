<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSholatTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_sholat', function (Blueprint $table) {
            $table->id('id_sholat');
            $table->foreignId('id_siswa')->constrained('tbl_siswa', 'id_siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('card_code')->nullable();
            $table->time('masuk')->nullable();
            $table->date('tanggal')->nullable();
            $table->time('dzuhur_masuk')->nullable();
            $table->time('dzuhur_keluar')->nullable();
            $table->time('ashar_masuk')->nullable();
            $table->time('ashar_keluar')->nullable();
            $table->enum('status_dzuhur', ['sholat', 'haid', 'sakit', 'tidak sekolah'])->nullable();
            $table->enum('status_ashar', ['sholat', 'haid', 'sakit', 'tidak sekolah'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_sholat');
    }
}