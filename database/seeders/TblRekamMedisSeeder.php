<?php

namespace Database\Seeders;

use App\Models\RekamMedis;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblRekamMedisSeeder extends Seeder
{
    public function run()
    {
        RekamMedis::insert([
            ['id_rekam_medis' => 1, 'id_siswa' => 135, 'id_kunjungan' => 1, 'tanggal' => '2025-10-18', 'keluhan' => 'Sering sakit sakitan', 'diagnosis' => null, 'tindakan' => null, 'catatan' => null, 'obat_diberikan' => null, 'created_at' => '2025-10-18 05:00:30', 'updated_at' => '2025-10-18 05:00:30'],
        ]);
    }
}