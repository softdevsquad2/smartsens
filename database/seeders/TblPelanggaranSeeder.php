<?php

namespace Database\Seeders;

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
                'kode' => 'P001',
                'sub_kode' => '1.1',
                'nama_pelanggaran' => 'Terlambat Masuk Sekolah',
                'poin_1' => '1',
                'poin_2' => '2',
                'poin_3' => '3',
            ],
            [
                'kode' => 'P002',
                'sub_kode' => '1.2',
                'nama_pelanggaran' => 'Bolos',
                'poin_1' => '5',
                'poin_2' => '10',
                'poin_3' => '15',
            ],
            [
                'kode' => 'P003',
                'sub_kode' => '1.3',
                'nama_pelanggaran' => 'Tidak Memakai Seragam',
                'poin_1' => '10',
                'poin_2' => '20',
                'poin_3' => '30',
            ],
            [
                'kode' => 'P004',
                'sub_kode' => '1.4',
                'nama_pelanggaran' => 'Membawa Handphone',
                'poin_1' => '15',
                'poin_2' => '30',
                'poin_3' => '45',
            ],
            [
                'kode' => 'P005',
                'sub_kode' => '1.5',
                'nama_pelanggaran' => 'Bolos Kelas',
                'poin_1' => '20',
                'poin_2' => '40',
                'poin_3' => '60',
            ],
        ]);
    }
}
