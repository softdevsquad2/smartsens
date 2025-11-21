<?php

namespace App\Exports;

use App\Models\KunjunganUks;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IzinPulangExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return KunjunganUks::with('siswa.kelas.jurusan')
            ->where('jenis_kunjungan', 'izin_pulang')
            ->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            'ID Kunjungan',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Jurusan',
            'Tanggal',
            'Waktu',
            'Keterangan',
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($kunjungan): array
    {
        return [
            $kunjungan->id_kunjungan,
            $kunjungan->siswa->nama ?? '',
            $kunjungan->siswa->nisn ?? '',
            $kunjungan->siswa->kelas->nama_kelas ?? '',
            $kunjungan->siswa->kelas->jurusan->nama_jurusan ?? '',
            $kunjungan->tanggal,
            $kunjungan->waktu,
            $kunjungan->keterangan,
        ];
    }
}
