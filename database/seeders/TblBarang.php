<?php

namespace Database\Seeders;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;

class TblBarang extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FacadesDB::table('tbl_barang')->insert([
            [
                'id_barang' => 1,
                'kode_barang' => 'BRG001',
                'satuan' => 'pcs',
                'nama_barang' => 'Laptop Dell Inspiron',
                'stok' => 15,
                'gambar' => 'ROG.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_barang' => 2,
                'kode_barang' => 'BRG002',
                'satuan' => 'pcs',
                'nama_barang' => 'Proyektor Epson',
                'stok' => 8,
                'gambar' => 'proyektor.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_barang' => 3,
                'kode_barang' => 'BRG003',
                'satuan' => 'unit',
                'nama_barang' => 'Speaker Bluetooth JBL',
                'stok' => 20,
                'gambar' => 'speaker.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
