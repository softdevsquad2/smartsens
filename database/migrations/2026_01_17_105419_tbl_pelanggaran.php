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
        Schema::create('tbl_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode');        // contoh: P1
            $table->string('sub_kode');    // contoh: 1.1
            $table->string('nama_pelanggaran');

            $table->integer('poin_1');
            $table->integer('poin_2');
            $table->integer('poin_3');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pelanggaran');
    }
};
