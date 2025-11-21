<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblSiswaSeeder extends Seeder
{
    public function run()
    {
        Siswa::insert([
            ['id_siswa' => 134, 'id_kelas' => 4, 'nama' => 'ALYA ALAWIYAH', 'jenis_kelamin' => 'P', 'nisn' => '12326097', 'card_code' => '3560276504', 'no_hp_ortu' => null, 'created_at' => '2025-10-16 13:57:31', 'updated_at' => '2025-10-16 13:57:31'],
            ['id_siswa' => 135, 'id_kelas' => 4, 'nama' => 'ARIEF MAULANA RIZKI', 'jenis_kelamin' => 'L', 'nisn' => '12326098', 'card_code' => '2146798597', 'no_hp_ortu' => '081990706575', 'created_at' => '2025-10-16 13:57:31', 'updated_at' => '2025-10-17 02:38:55'],
            ['id_siswa' => 136, 'id_kelas' => 4, 'nama' => 'BINTANG CAESAR PRATAMA PUTRA', 'jenis_kelamin' => 'L', 'nisn' => '12326099', 'card_code' => '82543870', 'no_hp_ortu' => '085173312550', 'created_at' => '2025-10-16 13:57:32', 'updated_at' => '2025-10-17 04:01:09'],
        ]);
    }
}