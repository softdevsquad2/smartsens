<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblJurusanSeeder extends Seeder
{
    public function run()
    {
        Jurusan::insert([
            ['id_jurusan' => 1, 'nama_jurusan' => 'Teknik Komputer dan Jaringan', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 2, 'nama_jurusan' => 'Rekayasa Perangkat Lunak', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 3, 'nama_jurusan' => 'Teknik Listrik', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 4, 'nama_jurusan' => 'Teknik Mesin', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 5, 'nama_jurusan' => 'Teknik Otomotif', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 6, 'nama_jurusan' => 'Desain Permodelan Informasi Bangunan', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 7, 'nama_jurusan' => 'Teknik Elektronik', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
            ['id_jurusan' => 8, 'nama_jurusan' => 'Brodcasting dan Perfilman', 'created_at' => '2025-10-16 11:30:50', 'updated_at' => '2025-10-16 11:30:50'],
        ]);
    }
}
