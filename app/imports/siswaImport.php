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
    public static $errors = [];

    protected $kelasList = [];
    protected $siswaList = [];
    protected $rowNumber = 2; // Mulai dari baris 2 (skip header)

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
        self::$errors = []; // Reset errors

        foreach ($rows as $row) {
            $nama  = trim($row['nama'] ?? '');
            $nisn  = trim($row['nisn'] ?? '');
            $jk    = strtoupper(trim($row['jenis_kelamin'] ?? ''));
            $kelas = trim($row['kelas'] ?? '');
            $card  = trim($row['card_code'] ?? '');
            $hp    = trim($row['no_hp_ortu'] ?? '');

            // Validasi required fields
            if (!$nama) {
                self::$errors[] = "Baris {$this->rowNumber}: Nama siswa tidak boleh kosong";
                $this->rowNumber++;
                continue;
            }

            if (!$nisn) {
                self::$errors[] = "Baris {$this->rowNumber}: NISN tidak boleh kosong";
                $this->rowNumber++;
                continue;
            }

            if (!$kelas) {
                self::$errors[] = "Baris {$this->rowNumber}: Nama kelas tidak boleh kosong";
                $this->rowNumber++;
                continue;
            }

            // Validasi Jenis Kelamin
            if ($jk && !in_array($jk, ['L', 'P'])) {
                self::$errors[] = "Baris {$this->rowNumber}: Jenis kelamin '{$row['jenis_kelamin']}' tidak valid. Gunakan L (Laki-laki) atau P (Perempuan)";
                $this->rowNumber++;
                continue;
            }

            // Validasi Kelas (harus ada di database)
            $idKelas = $this->kelasList[$kelas] ?? null;
            if (!$idKelas) {
                self::$errors[] = "Baris {$this->rowNumber}: Kelas '{$kelas}' tidak ditemukan di sistem";
                $this->rowNumber++;
                continue;
            }

            // Validasi NISN (harus unik)
            if (isset($this->siswaList[$nisn])) {
                // UPDATE (jika NISN sudah ada)
                $idSiswa = $this->siswaList[$nisn];

                DB::table('tbl_siswa')
                    ->where('id_siswa', $idSiswa)
                    ->update([
                        'nama'          => $nama,
                        'jenis_kelamin' => $jk ?: 'L',
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
                $this->rowNumber++;
                continue;
            }

            // INSERT (disimpan ke bulk)
            $insertBulk[] = [
                'nama'          => $nama,
                'nisn'          => $nisn,
                'jenis_kelamin' => $jk ?: 'L',
                'id_kelas'      => $idKelas,
                'card_code'     => $card ?: null,
                'no_hp_ortu'    => $hp ?: null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            self::$inserted++;
            $this->rowNumber++;
        }

        // BULK INSERT dengan batch yang lebih besar (super cepat)
        if (!empty($insertBulk)) {

            $chunks = array_chunk($insertBulk, 1000);

            foreach ($chunks as $chunk) {

                DB::table('tbl_siswa')->insert($chunk);

                // Buat user untuk siswa baru
                foreach ($chunk as $s) {
                    $idSiswa = DB::table('tbl_siswa')
                        ->where('nisn', $s['nisn'])
                        ->value('id_siswa');

                    DB::table('tbl_user')->insert([
                        'id_siswa' => $idSiswa,
                        'username' => $s['nama'],
                        'password' => Hash::make($s['nisn'], ['rounds' => 4]),
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
