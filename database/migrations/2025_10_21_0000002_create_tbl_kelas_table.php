<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblKelasTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->foreignId('id_jurusan')->constrained('tbl_jurusan', 'id_jurusan')->onDelete('cascade');
            $table->string('nama_kelas', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_kelas');
    }
}