<?php

namespace App\Exports;

use App\Models\RekamMedis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekamMedisExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return RekamMedis::with('siswa.kelas.jurusan')->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            'ID Rekam Medis',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Jurusan',
            'Tanggal',
            'Keluhan',
            'Diagnosis',
            'Tindakan',
            'Catatan',
            'Obat Diberikan',
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($rekamMedis): array
    {
        return [
            $rekamMedis->id_rekam_medis,
            $rekamMedis->siswa->nama ?? '',
            $rekamMedis->siswa->nisn ?? '',
            $rekamMedis->siswa->kelas->nama_kelas ?? '',
            $rekamMedis->siswa->kelas->jurusan->nama_jurusan ?? '',
            $rekamMedis->tanggal,
            $rekamMedis->keluhan,
            $rekamMedis->diagnosis,
            $rekamMedis->tindakan,
            $rekamMedis->catatan,
            $rekamMedis->obat_diberikan,
        ];
    }
}
