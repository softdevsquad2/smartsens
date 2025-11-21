<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblKonfirmasiSholatTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_konfirmasi_sholat', function (Blueprint $table) {
            $table->id('id_konfirmasi');
            $table->foreignId('id_wali_kelas')->constrained('tbl_wali_kelas', 'id_wali_kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal')->nullable();
            $table->time('waktu')->nullable();
            $table->boolean('sudah_dilihat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_konfirmasi_sholat');
    }
}