<?php

namespace App\Exports;

use App\Models\rekam_pelanggaran;
use App\Models\RekamPrestasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;

class PelanggaranExport implements WithMultipleSheets
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            'Pelanggaran' => new PelanggaranSheet($this->request),
            'Prestasi' => new PrestasiSheet($this->request),
        ];
    }
}

class PelanggaranSheet implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = rekam_pelanggaran::with(['siswa.kelas', 'pelanggaran']);

        if ($this->request->kelas) {
            $query->whereHas('siswa', function($q) {
                $q->where('id_kelas', $this->request->kelas);
            });
        }

        if ($this->request->jenis) {
            $query->where('id_pelanggaran', $this->request->jenis);
        }

        if ($this->request->tanggal) {
            $query->whereDate('tanggal_pelanggaran', $this->request->tanggal);
        }

        $dataPelanggaran = $query->get();

        return view('pelanggaran.export_excel_pelanggaran', compact('dataPelanggaran'));
    }
}

class PrestasiSheet implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = RekamPrestasi::with(['siswa.kelas', 'jenisPrestasi', 'petugas']);

        if ($this->request->kelas) {
            $query->whereHas('siswa', function($q) {
                $q->where('id_kelas', $this->request->kelas);
            });
        }

        if ($this->request->tanggal) {
            $query->whereDate('tanggal_prestasi', $this->request->tanggal);
        }

        $dataPrestasi = $query->get();

        return view('pelanggaran.export_excel_prestasi', compact('dataPrestasi'));
    }
}
