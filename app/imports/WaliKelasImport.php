<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class WaliKelasImport implements ToCollection, WithHeadingRow
{
    public static $inserted = 0;
    public static $updated = 0;
    public static $errors = [];

    protected $kelasList = [];
    protected $waliKelasList = [];
    protected $rowNumber = 2; // Mulai dari baris 2 (skip header)

    public function __construct()
    {
        // Cache semua kelas -> super cepat
        $this->kelasList = DB::table('tbl_kelas')->pluck('id_kelas', 'nama_kelas')->toArray();

        // Cache semua NIP yg sudah ada -> super cepat
        $this->waliKelasList = DB::table('tbl_wali_kelas')
            ->pluck('id_wali_kelas', 'nip')
            ->toArray();
    }

    public function collection(Collection $rows)
    {
        $insertBulk = [];
        self::$errors = []; // Reset errors

        foreach ($rows as $row) {
            $nama  = trim($row['nama'] ?? '');
            $nip   = trim($row['nip'] ?? '');
            $kelas = trim($row['kelas'] ?? '');

            // Validasi required fields
            if (!$nama) {
                self::$errors[] = "Baris {$this->rowNumber}: Nama wali kelas tidak boleh kosong";
                $this->rowNumber++;
                continue;
            }

            if (!$nip) {
                self::$errors[] = "Baris {$this->rowNumber}: NIP tidak boleh kosong";
                $this->rowNumber++;
                continue;
            }

            // Validasi Kelas (harus ada di database)
            $idKelas = null;
            if ($kelas) {
                $idKelas = $this->kelasList[$kelas] ?? null;
                if (!$idKelas) {
                    self::$errors[] = "Baris {$this->rowNumber}: Kelas '{$kelas}' tidak ditemukan di sistem";
                    $this->rowNumber++;
                    continue;
                }
            }

            // Validasi NIP (harus unik)
            if (isset($this->waliKelasList[$nip])) {
                // UPDATE (jika NIP sudah ada)
                $idWaliKelas = $this->waliKelasList[$nip];

                DB::table('tbl_wali_kelas')
                    ->where('id_wali_kelas', $idWaliKelas)
                    ->update([
                        'nama'     => $nama,
                        'id_kelas' => $idKelas,
                        'updated_at' => now(),
                    ]);

                DB::table('tbl_user')
                    ->where('id_wali_kelas', $idWaliKelas)
                    ->update([
                        'username'   => $nama,
                        'updated_at' => now(),
                    ]);

                self::$updated++;
                $this->rowNumber++;
                continue;
            }

            // INSERT (disimpan ke bulk)
            $insertBulk[] = [
                'nama'       => $nama,
                'nip'        => $nip,
                'id_kelas'   => $idKelas,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            self::$inserted++;
            $this->rowNumber++;
        }

        // BULK INSERT dengan batch yang lebih besar (super cepat)
        if (!empty($insertBulk)) {

            $chunks = array_chunk($insertBulk, 1000);

            foreach ($chunks as $chunk) {

                DB::table('tbl_wali_kelas')->insert($chunk);

                // Buat user untuk wali kelas baru
                foreach ($chunk as $w) {
                    $idWaliKelas = DB::table('tbl_wali_kelas')
                        ->where('nip', $w['nip'])
                        ->value('id_wali_kelas');

                    DB::table('tbl_user')->insert([
                        'id_wali_kelas' => $idWaliKelas,
                        'username'      => $w['nama'],
                        'password'      => Hash::make($w['nip'], ['rounds' => 4]),
                        'role'          => 'guru',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        }
    }
}
