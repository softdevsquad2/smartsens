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
        Schema::create('tbl_rekam_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_pelanggaran');
            $table->date('tanggal_pelanggaran');
            $table->integer('poin_diberikan');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('tbl_siswa')->onDelete('cascade');
            $table->foreign('id_pelanggaran')->references('id')->on('tbl_pelanggaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_rekam_pelanggaran');
    }
};
