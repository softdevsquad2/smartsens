<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SiswaTemplateExport implements FromArray
{
    /**
     * Return array of rows (first row is header)
     */
    public function array(): array
    {
        return [
            ['nama', 'nisn', 'jenis_kelamin', 'kelas', 'jurusan', 'card_code', 'no_hp_ortu'],
            ['Budi Santoso', '1234567890', 'L', 'X IPA 1', 'IPA', '987654321', '08123456789'],
            ['Siti Aminah', '2345678901', 'P', 'XI IPS 2', 'IPS', '', '08234567890'],
        ];
    }
}
