<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblStokObatSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_stok_obat')->insert([
            ['id_stok' => 1, 'id_obat' => 1, 'jumlah' => 20, 'tanggal_masuk' => '2025-10-18', 'expired_date' => '2025-10-31', 'created_at' => '2025-10-18 04:50:40', 'updated_at' => '2025-10-18 04:57:08'],
            ['id_stok' => 2, 'id_obat' => 1, 'jumlah' => 22, 'tanggal_masuk' => '2025-10-22', 'expired_date' => '2025-10-25', 'created_at' => '2025-10-22 05:49:56', 'updated_at' => '2025-10-22 05:49:56'],
        ]);
    }
}
