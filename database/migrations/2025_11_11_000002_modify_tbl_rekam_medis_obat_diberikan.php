<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTblRekamMedisObatDiberikan extends Migration
{
    public function up()
    {
        // Change column type to TEXT to store JSON array of medicines
        if (Schema::hasColumn('tbl_rekam_medis', 'obat_diberikan')) {
            Schema::table('tbl_rekam_medis', function (Blueprint $table) {
                $table->text('obat_diberikan')->nullable()->change();
            });
        }
    }

    public function down()
    {
        // Revert to string if needed
        if (Schema::hasColumn('tbl_rekam_medis', 'obat_diberikan')) {
            Schema::table('tbl_rekam_medis', function (Blueprint $table) {
                $table->string('obat_diberikan', 255)->nullable()->change();
            });
        }
    }
}
