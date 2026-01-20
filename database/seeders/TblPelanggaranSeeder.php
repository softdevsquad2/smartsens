<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblPelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_pelanggaran')->insert([
            [
                'nama_pelanggaran' => 'Terlambat Masuk Sekolah',
                'poin_pelanggaran' => '5',
            ],
            [
                'nama_pelanggaran' => 'Tidak Memakai Seragam',
                'poin_pelanggaran' => '10',
            ],
            [
                'nama_pelanggaran' => 'Membawa Handphone',
                'poin_pelanggaran' => '15',
            ],
            [
                'nama_pelanggaran' => 'Bolos Kelas',
                'poin_pelanggaran' => '20',
            ],
        ]);
    }
}
