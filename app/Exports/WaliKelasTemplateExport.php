<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class WaliKelasTemplateExport implements FromArray
{
    /**
     * Return array of rows (first row is header)
     */
    public function array(): array
    {
        return [
            ['nama', 'nip', 'kelas'],
            ['Ahmad Susanto', '1987654321', 'X IPA 1'],
            ['Siti Nurhaliza', '1987654322', 'XI IPS 2'],
            ['Budi Santoso', '1987654323', ''], // Kelas kosong untuk wali kelas yang belum ditugaskan
        ];
    }
}
