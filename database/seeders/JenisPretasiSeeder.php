<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPretasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisPrestasi = [
            [
                'nama_prestasi' => 'Juara 1 Tingkat Sekolah',
                'poin_prestasi' => 50,
                'keterangan' => 'Prestasi juara 1 dalam kompetisi tingkat sekolah',
            ],
            [
                'nama_prestasi' => 'Juara 2 Tingkat Sekolah',
                'poin_prestasi' => 40,
                'keterangan' => 'Prestasi juara 2 dalam kompetisi tingkat sekolah',
            ],
            [
                'nama_prestasi' => 'Juara 3 Tingkat Sekolah',
                'poin_prestasi' => 30,
                'keterangan' => 'Prestasi juara 3 dalam kompetisi tingkat sekolah',
            ],
            [
                'nama_prestasi' => 'Juara 1 Tingkat Kota',
                'poin_prestasi' => 100,
                'keterangan' => 'Prestasi juara 1 dalam kompetisi tingkat kota',
            ],
            [
                'nama_prestasi' => 'Juara 2 Tingkat Kota',
                'poin_prestasi' => 80,
                'keterangan' => 'Prestasi juara 2 dalam kompetisi tingkat kota',
            ],
            [
                'nama_prestasi' => 'Juara 3 Tingkat Kota',
                'poin_prestasi' => 60,
                'keterangan' => 'Prestasi juara 3 dalam kompetisi tingkat kota',
            ],
            [
                'nama_prestasi' => 'Juara 1 Tingkat Provinsi',
                'poin_prestasi' => 150,
                'keterangan' => 'Prestasi juara 1 dalam kompetisi tingkat provinsi',
            ],
            [
                'nama_prestasi' => 'Juara 2 Tingkat Provinsi',
                'poin_prestasi' => 120,
                'keterangan' => 'Prestasi juara 2 dalam kompetisi tingkat provinsi',
            ],
            [
                'nama_prestasi' => 'Juara 3 Tingkat Provinsi',
                'poin_prestasi' => 100,
                'keterangan' => 'Prestasi juara 3 dalam kompetisi tingkat provinsi',
            ],
            [
                'nama_prestasi' => 'Prestasi Akademik (IPK Tertinggi)',
                'poin_prestasi' => 75,
                'keterangan' => 'Prestasi akademik dengan IPK tertinggi',
            ],
            [
                'nama_prestasi' => 'Prestasi Olahraga',
                'poin_prestasi' => 50,
                'keterangan' => 'Prestasi dalam bidang olahraga',
            ],
            [
                'nama_prestasi' => 'Prestasi Seni',
                'poin_prestasi' => 50,
                'keterangan' => 'Prestasi dalam bidang seni',
            ],
            [
                'nama_prestasi' => 'Prestasi Kepemimpinan',
                'poin_prestasi' => 60,
                'keterangan' => 'Prestasi dalam bidang kepemimpinan',
            ],
        ];

        DB::table('tbl_jenis_prestasi')->insert($jenisPrestasi);
    }
}
