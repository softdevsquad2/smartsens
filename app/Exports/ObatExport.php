<?php

namespace App\Exports;

use App\Models\RekamMedis;
use App\Models\StokObat;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObatExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return StokObat::with('obat')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Stok',
            'Nama Item',
            'Kategori',
            'Deskripsi',
            'Tanggal Masuk',
            'Tanggal Kadaluarsa',
            'Jumlah Masuk',
            'Status',

        ];
    }

    public function map($stok): array
    {
        // Ambil rekam medis yang pakai obat ini
        $usages = RekamMedis::all();

        $usageDetails = [];
        foreach ($usages as $usage) {
            $tanggal = $usage->tanggal ?? null;

            // Pastikan format tanggal benar-benar Carbon
            try {
                $tanggalFormatted = $tanggal ? Carbon::parse($tanggal) : null;
            } catch (\Exception $e) {
                $tanggalFormatted = null;
            }

            $usageDetails[] = [
                'siswa' => $usage->siswa->nama ?? 'Unknown',
                'tanggal' => $tanggalFormatted,
                'obat' => is_array($usage->obat_diberikan)
                    ? implode(', ', $usage->obat_diberikan)
                    : $usage->obat_diberikan,
            ];
        }

        // Tentukan status stok
        $status = 'Tersedia';
        if ($stok->expired_date && $stok->expired_date < now()) {
            $status = 'Kadaluarsa';
        }

        // Format hasil export
        return [
            $stok->id_stok,
            $stok->obat->nama_obat ?? '',
            $stok->obat->kategori ?? '',
            $stok->obat->deskripsi ?? '',
            $stok->tanggal_masuk ? Carbon::parse($stok->tanggal_masuk)->format('d/m/Y') : '',
            $stok->expired_date ? Carbon::parse($stok->expired_date)->format('d/m/Y') : '',
            $stok->jumlah,
            $status,

        ];
    }
}
