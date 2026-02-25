<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSiswaTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->foreignId('id_kelas')->constrained('tbl_kelas', 'id_kelas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('nisn', 225)->nullable();
            $table->string('card_code', 20)->nullable();
            $table->string('no_hp_ortu', 20)->nullable();
            $table->string('foto')->nullable();
            $table->integer('total_poin')->default(0);
            $table->string('status_sp')->nullable();
            $table->string('sp_tertinggi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_siswa');
    }
}
