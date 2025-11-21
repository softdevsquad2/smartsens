<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblWaliKelasTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_wali_kelas', function (Blueprint $table) {
            $table->id('id_wali_kelas');
            $table->string('nama')->nullable();
            $table->bigInteger('nip')->nullable();
            $table->timestamps();
            $table->foreignid('id_kelas')->constrained('tbl_kelas', 'id_kelas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_wali_kelas');
    }
}