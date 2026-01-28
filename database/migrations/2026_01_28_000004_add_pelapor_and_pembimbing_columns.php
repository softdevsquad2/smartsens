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
            $table->string('pelapor')->nullable()->after('id_user')->comment('Nama/identitas yang melaporkan');
        });

        Schema::table('tbl_rekam_prestasi_siswa', function (Blueprint $table) {
            $table->string('pembimbing')->nullable()->after('id_user')->comment('Nama pembimbing/guru yang mencatat prestasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_rekam_pelanggaran', function (Blueprint $table) {
            $table->dropColumn(['pelapor']);
        });

        Schema::table('tbl_rekam_prestasi_siswa', function (Blueprint $table) {
            $table->dropColumn(['pembimbing']);
        });
    }
};
