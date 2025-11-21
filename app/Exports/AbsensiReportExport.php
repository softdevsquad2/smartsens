<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $idKelas;

    protected $startDate;

    protected $endDate;

    public function __construct(int $idKelas, $startDate, $endDate)
    {
        $this->idKelas = $idKelas;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Return a collection of Absensi rows for export
     */
    public function collection()
    {
        return Absensi::whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->whereHas('siswa', function ($q) {
                $q->where('id_kelas', $this->idKelas);
            })
            ->with('siswa')
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'ID Siswa',
            'NISN',
            'Nama Siswa',
            'Status Masuk',
            'Waktu Masuk',
            'Foto Masuk',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        return [
            optional($row->tanggal)->format('Y-m-d') ?? $row->tanggal,
            $row->id_siswa,
            $row->siswa->nisn ?? '',
            $row->siswa->nama ?? '',
            $row->status_masuk ?? '',
            $row->waktu_masuk ?? '',
            $row->foto_masuk ?? '',
            $row->keterangan ?? '',
        ];
    }
}
