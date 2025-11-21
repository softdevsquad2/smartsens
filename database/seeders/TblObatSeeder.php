<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblObatSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_obat')->insert([
            ['id_obat' => 1, 'nama_obat' => 'Paracetamol', 'deskripsi' => 'Penurun Panas', 'kategori' => 'Obat Bebas', 'kadaluarsa_default' => 365, 'created_at' => '2025-10-18 04:22:24', 'updated_at' => '2025-10-18 04:29:07'],
            ['id_obat' => 2, 'nama_obat' => 'Betadin', 'deskripsi' => 'Mengobati luka terbuka', 'kategori' => 'P3K', 'kadaluarsa_default' => 365, 'created_at' => '2025-10-18 04:37:01', 'updated_at' => '2025-10-18 04:37:01'],
        ]);
    }
}
