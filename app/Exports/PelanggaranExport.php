<?php

namespace App\Exports;

use App\Models\rekam_pelanggaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PelanggaranExport implements FromView
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

        return view('pelanggaran.export_excel', compact('dataPelanggaran'));
    }
}
