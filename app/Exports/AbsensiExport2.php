<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport2 implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Absensi::select(
            'id_absensi',
            'id_siswa',
            'tanggal',
            'waktu_masuk',
            'status_masuk',
            'waktu_pulang',
            'status_pulang'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID Absensi',
            'ID Siswa',
            'Tanggal',
            'Waktu Masuk',
            'Status Masuk',
            'Waktu Pulang',
            'Status Pulang'
        ];
    }
}
