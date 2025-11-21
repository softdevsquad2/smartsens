<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Absensi::with(['siswa.kelas.jurusan']);

        // Filter Nama Siswa
        if (!empty($this->filters['nama'])) {
            $query->whereHas('siswa', function ($q) {
                $q->where('nama', 'like', '%' . $this->filters['nama'] . '%');
            });
        }

        // Filter Kelas
        if (!empty($this->filters['kelas'])) {
            $query->whereHas('siswa.kelas', function ($q) {
                $q->where('id_kelas', $this->filters['kelas']);
            });
        }

        // Filter Jurusan
        if (!empty($this->filters['jurusan'])) {
            $query->whereHas('siswa.kelas', function ($q) {
                $q->where('id_jurusan', $this->filters['jurusan']);
            });
        }

        // Filter Bulan
        if (!empty($this->filters['bulan'])) {
            [$year, $month] = explode('-', $this->filters['bulan']);
            $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
        }

        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Tanggal',
            'Waktu Masuk',
            'Status Masuk',
            'Waktu Pulang',
            'Status Pulang',
        ];
    }

    public function map($absen): array
    {
        return [
            $absen->id_absensi,
            $absen->siswa->nama ?? '-',
            $absen->siswa->kelas->nama_kelas ?? '-',
            $absen->siswa->kelas->jurusan->nama_jurusan ?? '-',
            $absen->tanggal,
            $absen->waktu_masuk ?? '-',
            $absen->status_masuk ?? '-',
            $absen->waktu_pulang ?? '-',
            $absen->status_pulang ?? '-',
        ];
    }
}