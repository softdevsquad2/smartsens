<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_user')->insert([
            ['id_user' => 1, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'admin', 'password' => '$2y$12$t9UJp9wtdK8pOczVF5Wx/.8STqANWEui8FM2hM5mfTjzqk4pQQVe.', 'role' => 'admin', 'card_code' => 1001, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 2, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'superadmin', 'password' => '$2y$12$.E8MXfhjukSzskPCf/1Tjur3Th.qn6e0qcoeiOekhYu6oBKf9038a', 'role' => 'admin', 'card_code' => 1002, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 3, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'operator', 'password' => '$2y$12$HX8Q19x7EXXEVdqmnd0kdeukSYEJcHtQ8bXeti9PzpmLNJcFxmgYS', 'role' => 'operator', 'card_code' => 2001, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 4, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'petugas_uks', 'password' => '$2y$10$eC9P1Dzbw/k94UWfcuz3G.wNd3W4s3B4f/mweD1qesq2Ja8xUCpqO', 'role' => 'uks', 'card_code' => null, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 5, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'piket', 'password' => '$2y$10$Deifdgi426HdZwfWy267CuH9Dr6cTIABxvbLemK5D9P05PR2IB9lS', 'role' => 'piket', 'card_code' => null, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 6, 'id_wali_kelas' => null, 'id_siswa' => 134, 'username' => 'ALYA ALAWIYAH', 'password' => '$2y$10$F4SB/IphfoP7rfgCfgRLcOcbMpAvMMqJOiYrZyViQvi7U.Zao7yVy', 'role' => 'siswa', 'card_code' => 3560276504, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 7, 'id_wali_kelas' => null, 'id_siswa' => 135, 'username' => 'ARIEF MAULANA RIZKI', 'password' => '$2y$12$AHWCFjvmqIg2WG6soY5zFea3WTbQ1C28GpHh7qQOlpVWSb5XuZpuy', 'role' => 'siswa', 'card_code' => 2146798597, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 8, 'id_wali_kelas' => 13, 'id_siswa' => null, 'username' => 'aisiti.', 'password' => '$2y$10$8FTsC8DU/UGXSM6U.sbuYeRYSgxTerMbH.MR1uqIJVa8Xos0azo5O', 'role' => 'guru', 'card_code' => null, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 9, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'toolman', 'password' => '$2y$10$Deifdgi426HdZwfWy267CuH9Dr6cTIABxvbLemK5D9P05PR2IB9lS', 'role' => 'toolman', 'card_code' => null, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
            ['id_user' => 10, 'id_wali_kelas' => null, 'id_siswa' => null, 'username' => 'kesiswaan', 'password' => '$2y$10$11GuGKXJLVfOOVfPY.U4uuViXAOXw5Ahp.UWSqdyU1Wuspx.jCNfC', 'role' => 'kesiswaan', 'card_code' => null, 'created_at' => '2025-10-16 11:30:56', 'updated_at' => '2025-10-16 11:30:56'],
        ]);
    }
}
