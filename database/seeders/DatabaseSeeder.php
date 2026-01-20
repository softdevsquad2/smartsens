<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TblJurusanSeeder::class,
            TblKelasSeeder::class,
            TblWaliKelasSeeder::class,
            TblBarang::class,
            TblSiswaSeeder::class,
            TblUserSeeder::class,
            TblSettingsSeeder::class,
            TblObatSeeder::class,
            TblStokObatSeeder::class,
            TblKunjunganUksSeeder::class,
            TblRekamMedisSeeder::class,
            TblAbsensiSeeder::class,
            TblPelanggaranSeeder::class,
        ]);
    }
}
