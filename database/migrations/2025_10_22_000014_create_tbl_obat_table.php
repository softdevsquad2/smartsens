<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblObatTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_obat', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('nama_obat');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('kadaluarsa_default')->default(365);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_obat');
    }
}