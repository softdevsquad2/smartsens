<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblKunjunganUksTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_kunjungan_uks', function (Blueprint $table) {
            $table->id('id_kunjungan');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_petugas_uks');
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('jenis_kunjungan', ['sakit', 'cedera', 'pemeriksaan_rutin', 'konsultasi', 'izin_pulang']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk mempercepat query
            $table->index('id_siswa');
            $table->index('id_petugas_uks');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_kunjungan_uks');
    }
}