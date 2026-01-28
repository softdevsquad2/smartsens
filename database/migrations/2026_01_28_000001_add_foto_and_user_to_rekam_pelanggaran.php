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
        Schema::table('tbl_rekam_pelanggaran', function (Blueprint $table) {
            $table->string('foto_pelanggaran')->nullable()->after('tanggal_pelanggaran');
            $table->unsignedBigInteger('id_user')->nullable()->after('foto_pelanggaran');

            $table->foreign('id_user')->references('id_user')->on('tbl_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_rekam_pelanggaran', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn(['foto_pelanggaran', 'id_user']);
        });
    }
};
