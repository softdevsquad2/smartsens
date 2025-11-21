<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAbsensiTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_siswa')->constrained('tbl_siswa', 'id_siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->decimal('longitude_masuk', 10, 7)->nullable();
            $table->decimal('latitude_masuk', 10, 7)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->decimal('longitude_pulang', 10, 7)->nullable();
            $table->decimal('latitude_pulang', 10, 7)->nullable();
            $table->string('foto_pulang')->nullable();
            $table->enum('status_masuk', ['hadir', 'terlambat', 'sakit', 'izin', 'sakit_izin', 'izin_pulang', 'alfa'])->nullable();
            $table->enum('status_pulang', ['pulang', 'tidak_pulang', 'izin_pulang', 'pulang_sakit'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_absensi');
    }
}