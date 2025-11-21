<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblSettingsSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_settings')->insert([
            ['id_setting' => 1, 'nama_setting' => 'school_latitude', 'nilai_setting' => '-7.357950', 'keterangan' => 'Latitude sekolah', 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:42:52'],
            ['id_setting' => 2, 'nama_setting' => 'school_longitude', 'nilai_setting' => '108.229187', 'keterangan' => 'Longitude sekolah', 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:42:52'],
            ['id_setting' => 3, 'nama_setting' => 'attendance_radius', 'nilai_setting' => '100', 'keterangan' => 'Radius absensi dalam meter', 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-17 02:13:12'],
            ['id_setting' => 4, 'nama_setting' => 'jam_masuk', 'nilai_setting' => '05:00', 'keterangan' => 'Jam masuk sekolah', 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 23:03:22'],
            ['id_setting' => 5, 'nama_setting' => 'jam_terlambat', 'nilai_setting' => '06:31', 'keterangan' => 'Batas waktu masuk sebelum dianggap terlambat', 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-17 02:03:17'],
            ['id_setting' => 6, 'nama_setting' => 'jam_pulang', 'nilai_setting' => '14:00', 'keterangan' => 'Jam pulang sekolah', 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 23:03:22'],
        ]);
    }
}
