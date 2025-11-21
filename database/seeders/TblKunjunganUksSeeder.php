<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblKunjunganUksSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_kunjungan_uks')->insert([
            ['id_kunjungan' => 1, 'id_siswa' => 135, 'id_petugas_uks' => 166, 'tanggal' => '2025-10-18', 'waktu' => '15:14:40', 'jenis_kunjungan' => 'izin_pulang', 'keterangan' => 'sakit', 'created_at' => '2025-10-18 08:14:40', 'updated_at' => '2025-10-18 08:14:40'],
        ]);
    }
}
