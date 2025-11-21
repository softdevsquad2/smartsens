<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblWaliKelasSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_wali_kelas')->insert([
            ['id_wali_kelas' => 2, 'id_kelas' => 1, 'nama' => 'Siti Aminah Nugroho', 'nip' => 1987654322, 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_wali_kelas' => 12, 'id_kelas' => 3, 'nama' => 'jhjhj', 'nip' => 9898, 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_wali_kelas' => 13, 'id_kelas' => 4, 'nama' => 'Ai Siti Hasanah, S.Pd.', 'nip' => null, 'created_at' => '2025-10-16 13:36:43', 'updated_at' => '2025-10-17 13:16:41'],
        ]);
    }
}
