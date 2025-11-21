<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRekamMedisObatTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_rekam_medis_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekam_medis')->constrained('tbl_rekam_medis', 'id_rekam_medis')->onDelete('cascade');
            $table->foreignId('id_obat')->constrained('tbl_obat', 'id_obat')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('aturan_pakai')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('tbl_rekam_medis_obat');
    }
}