<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiwayatExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Peminjaman::with(['siswa.kelas', 'barang', 'user']);

        if ($this->request->kelas) {
            $query->whereHas('siswa', function ($q) {
                $q->where('id_kelas', $this->request->kelas);
            });
        }

        if ($this->request->bulan) {
            $bulan = substr($this->request->bulan, 5, 2);
            $tahun = substr($this->request->bulan, 0, 4);

            $query->whereMonth('tanggal_pinjam', $bulan)
                ->whereYear('tanggal_pinjam', $tahun);
        }

        $data = $query->get();

        return $data->map(function ($item) {
            return [
                'ID' => $item->id_peminjaman,
                'Nama Siswa' => $item->siswa?->nama,
                'Kelas' => $item->siswa?->kelas?->nama_kelas,
                'Barang' => $item->barang?->nama_barang,
                'Jenis' => $item->barang?->jenis,
                'Jumlah' => $item->jumlah,
                'Tujuan' => $item->tujuan,
                'Tanggal Pinjam' => $item->tanggal_pinjam,
                'Tanggal Kembali' => $item->tanggal_kembali,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Siswa',
            'Kelas',
            'Barang',
            'Jenis',
            'Jumlah',
            'Tujuan',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',
        ];
    }
}
