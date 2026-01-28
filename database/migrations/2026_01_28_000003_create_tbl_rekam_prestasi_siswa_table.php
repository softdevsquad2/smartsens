<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_rekam_prestasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_jenis_prestasi');
            $table->date('tanggal_prestasi');
            $table->string('bukti_prestasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('tbl_siswa')->onDelete('cascade');
            $table->foreign('id_jenis_prestasi')->references('id')->on('tbl_jenis_prestasi')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('tbl_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_rekam_prestasi_siswa');
    }
};
