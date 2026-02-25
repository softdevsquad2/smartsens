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
            $table->string('nip')->nullable();
            $table->foreignId('id_kelas')
                ->nullable()
                ->constrained('tbl_kelas', 'id_kelas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_wali_kelas');
    }
}
