<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblAbsensiSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_absensi')->insert([
            ['id_absensi' => 39, 'id_siswa' => 135, 'tanggal' => '2025-10-17', 'waktu_masuk' => '20:21:37', 'waktu_pulang' => '20:26:04', 'longitude_masuk' => 108.2291768, 'latitude_masuk' => -7.3578748, 'foto_masuk' => 'attendance_photos/1760707297_135_masuk.jpg', 'longitude_pulang' => 108.2291856, 'latitude_pulang' => -7.3578164, 'foto_pulang' => 'attendance_photos/1760707564_135_pulang.jpg', 'status_masuk' => 'terlambat', 'status_pulang' => 'pulang', 'created_at' => '2025-10-17 13:21:37', 'updated_at' => '2025-10-17 13:26:04'],
            ['id_absensi' => 42, 'id_siswa' => 135, 'tanggal' => '2025-10-18', 'waktu_masuk' => '15:44:10', 'waktu_pulang' => '15:44:59', 'longitude_masuk' => 108.2296250, 'latitude_masuk' => -7.3574833, 'foto_masuk' => 'attendance_photos/1760777050_135_masuk.jpg', 'longitude_pulang' => null, 'latitude_pulang' => null, 'foto_pulang' => null, 'status_masuk' => 'terlambat', 'status_pulang' => 'pulang_sakit', 'created_at' => '2025-10-18 08:44:10', 'updated_at' => '2025-10-18 08:44:59'],
            ['id_absensi' => 43, 'id_siswa' => 134, 'tanggal' => '2025-10-22', 'waktu_masuk' => '12:53:19', 'waktu_pulang' => '12:53:39', 'longitude_masuk' => 108.2295683, 'latitude_masuk' => -7.3577243, 'foto_masuk' => 'attendance_photos/1761112399_134_masuk.JPG', 'longitude_pulang' => null, 'latitude_pulang' => null, 'foto_pulang' => null, 'status_masuk' => 'terlambat', 'status_pulang' => 'pulang_sakit', 'created_at' => '2025-10-22 05:53:19', 'updated_at' => '2025-10-22 05:53:39'],
        ]);
    }
}
