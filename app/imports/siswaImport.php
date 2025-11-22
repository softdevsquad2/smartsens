<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public static $inserted = 0;
    public static $updated = 0;

    protected $kelasList = [];
    protected $siswaList = [];

    public function __construct()
    {
        // Cache semua kelas -> super cepat
        $this->kelasList = DB::table('tbl_kelas')->pluck('id_kelas', 'nama_kelas')->toArray();

        // Cache semua NISN yg sudah ada -> super cepat
        $this->siswaList = DB::table('tbl_siswa')
            ->pluck('id_siswa', 'nisn')
            ->toArray();
    }

    public function collection(Collection $rows)
    {
        $insertBulk = [];

        foreach ($rows as $row) {

            $nama  = trim($row['nama'] ?? '');
            $nisn  = trim($row['nisn'] ?? '');
            $jk    = strtoupper(trim($row['jenis_kelamin'] ?? ''));
            $kelas = trim($row['kelas'] ?? '');
            $card  = trim($row['card_code'] ?? '');
            $hp    = trim($row['no_hp_ortu'] ?? '');

            if (!$nama || !$nisn || !$kelas) {
                continue;
            }

            // Mapping kelas
            $idKelas = $this->kelasList[$kelas] ?? null;
            if (!$idKelas) continue;

            // UPDATE (jika NISN sudah ada)
            if (isset($this->siswaList[$nisn])) {

                $idSiswa = $this->siswaList[$nisn];

                DB::table('tbl_siswa')
                    ->where('id_siswa', $idSiswa)
                    ->update([
                        'nama'          => $nama,
                        'jenis_kelamin' => $jk,
                        'id_kelas'      => $idKelas,
                        'card_code'     => $card ?: null,
                        'no_hp_ortu'    => $hp ?: null,
                        'updated_at'    => now(),
                    ]);

                DB::table('tbl_user')
                    ->where('id_siswa', $idSiswa)
                    ->update([
                        'username'   => $nama,
                        'card_code'  => $card ?: null,
                        'updated_at' => now(),
                    ]);

                self::$updated++;
                continue;
            }

            // INSERT (disimpan ke bulk)
            $insertBulk[] = [
                'nama'          => $nama,
                'nisn'          => $nisn,
                'jenis_kelamin' => $jk,
                'id_kelas'      => $idKelas,
                'card_code'     => $card ?: null,
                'no_hp_ortu'    => $hp ?: null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            self::$inserted++;
        }

        // BULK INSERT (super cepat)
        if (!empty($insertBulk)) {

            $chunks = array_chunk($insertBulk, 500);

            foreach ($chunks as $chunk) {

                $ids = DB::table('tbl_siswa')->insert($chunk);

                // Buat user untuk siswa baru
                foreach ($chunk as $s) {
                    $idSiswa = DB::table('tbl_siswa')
                        ->where('nisn', $s['nisn'])
                        ->value('id_siswa');

                    DB::table('tbl_user')->insert([
                        'id_siswa' => $idSiswa,
                        'username' => $s['nama'],
                        'password' => Hash::make($s['nisn'], ['rounds' => 4]), // CEPAT
                        'role' => 'siswa',
                        'card_code' => $s['card_code'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}