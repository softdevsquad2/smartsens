<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStokObatTable extends Migration
{
    public function up()
    {
        Schema::create('tbl_stok_obat', function (Blueprint $table) {
            $table->id('id_stok');
            $table->foreignId('id_obat')->constrained('tbl_obat', 'id_obat')->onDelete('cascade');
            $table->integer('jumlah');
            $table->date('tanggal_masuk');
            $table->date('expired_date')->nullable();
            $table->timestamps();



            $table->index('id_obat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_stok_obat');
    }
}