<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\Setting;
use App\Models\Kelas;
use App\Models\rekam_pelanggaran;
use Maatwebsite\Excel\Facades\Excel;

class PelanggaranController extends Controller
{
    public function index()
    {
        // Data untuk grafik pelanggaran per bulan
        $pelanggaranPerBulan = rekam_pelanggaran::selectRaw('MONTH(tanggal_pelanggaran) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal_pelanggaran', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Data untuk diagram jenis pelanggaran
        $jenisPelanggaran = rekam_pelanggaran::with('pelanggaran')
            ->selectRaw('id_pelanggaran, COUNT(*) as jumlah')
            ->groupBy('id_pelanggaran')
            ->get()
            ->map(function($item) {
                return [
                    'nama' => $item->pelanggaran->nama_pelanggaran ?? 'Unknown',
                    'jumlah' => $item->jumlah
                ];
            });

        // Data terbaru pelanggaran
        $terbaruPelanggaran = rekam_pelanggaran::with(['siswa', 'pelanggaran'])
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->limit(10)
            ->get();

        // Poin tertinggi siswa
        $poinTertinggi = Siswa::with('kelas')
            ->get()
            ->map(function($s) {
                $totalPoin = rekam_pelanggaran::where('id_siswa', $s->id_siswa)
                    ->join('tbl_pelanggaran', 'tbl_rekam_pelanggaran.id_pelanggaran', '=', 'tbl_pelanggaran.id')
                    ->sum('poin_pelanggaran');
                return [
                    'siswa' => $s,
                    'total_poin' => $totalPoin
                ];
            })
            ->sortByDesc('total_poin')
            ->take(5);

        return view('pelanggaran.index', compact('pelanggaranPerBulan', 'jenisPelanggaran', 'terbaruPelanggaran', 'poinTertinggi'));
    }
    public function store(Request $request)
    {
        // Logic to store pelanggaran data
    }
    public function pelanggaran(Request $request)
    {
        $perPage = $request->get('per_page', Setting::getSetting('pagination_pelanggaran') ?? 10);
        $pelanggaran = Pelanggaran::paginate($perPage);
        return view('pelanggaran.pelanggaran', compact('pelanggaran'));
    }

    public function storePelanggaranJenis(Request $request)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'poin_pelanggaran' => 'required|integer|min:1',
        ]);

        Pelanggaran::create($request->only(['nama_pelanggaran', 'poin_pelanggaran']));

        return redirect()->route('pelanggaran.pelanggaran')->with('success', 'Pelanggaran berhasil ditambahkan.');
    }

    public function updatePelanggaranJenis(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'poin_pelanggaran' => 'required|integer|min:1',
        ]);

        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->update($request->only(['nama_pelanggaran', 'poin_pelanggaran']));

        return redirect()->route('pelanggaran.pelanggaran')->with('success', 'Pelanggaran berhasil diupdate.');
    }

    public function deletePelanggaranJenis($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->delete();

        return redirect()->route('pelanggaran.pelanggaran')->with('success', 'Pelanggaran berhasil dihapus.');
    }
    public function riwayat(Request $request)
    {
        $perPage = $request->get('per_page', Setting::getSetting('pagination_riwayat') ?? 10);

        $query = Siswa::with(['kelas']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
        }

        $siswa = $query->paginate($perPage)->through(function($s) {
            $totalPoin = rekam_pelanggaran::where('id_siswa', $s->id_siswa)
                ->join('tbl_pelanggaran', 'tbl_rekam_pelanggaran.id_pelanggaran', '=', 'tbl_pelanggaran.id')
                ->sum('poin_pelanggaran');
            $s->total_poin = $totalPoin;
            return $s;
        });

        return view('pelanggaran.riwayat', compact('siswa'));
    }
    public function detail($nama)
    {
        // URL decode the name parameter in case it contains spaces or special characters
        $nama = urldecode($nama);

        $siswa = Siswa::where('nama', 'like', '%' . $nama . '%')->first();

        if (!$siswa) {
            // If no student found with partial match, try exact match
            $siswa = Siswa::where('nama', $nama)->first();
        }

        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $totalPoin = rekam_pelanggaran::where('id_siswa', $siswa->id_siswa)
            ->join('tbl_pelanggaran', 'tbl_rekam_pelanggaran.id_pelanggaran', '=', 'tbl_pelanggaran.id')
            ->sum('poin_pelanggaran');

        $riwayatPelanggaran = rekam_pelanggaran::with('pelanggaran')
            ->where('id_siswa', $siswa->id_siswa)
            ->orderBy('tanggal_pelanggaran', 'desc')
            ->get();

        return view('pelanggaran.detail', compact('siswa', 'totalPoin', 'riwayatPelanggaran'));
    }


    public function unduh(Request $request)
    {
        $perPage = $request->get('per_page', Setting::getSetting('pagination_unduh') ?? 15);

        $query = rekam_pelanggaran::with(['siswa.kelas', 'pelanggaran']);

        if ($request->kelas) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        if ($request->jenis) {
            $query->where('id_pelanggaran', $request->jenis);
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_pelanggaran', $request->tanggal);
        }

        $dataPelanggaran = $query->paginate($perPage);

        // Get data for filters
        $kelas = Kelas::all();
        $jenisPelanggaran = Pelanggaran::all();

        return view('pelanggaran.unduh', compact('dataPelanggaran', 'kelas', 'jenisPelanggaran'));
    }

    public function rekamPelanggaran()
    {
        $siswa = Siswa::all();
        $pelanggaran = Pelanggaran::all();
        return view('pelanggaran.rekam', compact('siswa', 'pelanggaran'));
    }

    public function storePelanggaran(Request $request)
    {
        \Log::info('PelanggaranController storePelanggaran Request:', $request->all());
        try {
            $request->validate([
                'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
                'pelanggaran' => 'required|array|min:1',
                'pelanggaran.*' => 'exists:tbl_pelanggaran,id',
            ]);

            $tanggal = now()->toDateString();

            foreach ($request->pelanggaran as $idPelanggaran) {
                rekam_pelanggaran::create([
                    'id_siswa' => $request->id_siswa,
                    'id_pelanggaran' => $idPelanggaran,
                    'tanggal_pelanggaran' => $tanggal,
                ]);
            }
            // dd($request->all());
            return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil direkam.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error in PelanggaranController:', $e->errors());
            return response()->json(['success' => false, 'message' => 'Validasi gagal: ' . implode(', ', $e->errors()['pelanggaran'] ?? $e->errors()['id_siswa'] ?? ['Data tidak valid'])]);
        } catch (\Exception $e) {
            \Log::error('Exception in PelanggaranController:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function listRekamPelanggaran()
    {
        $rekamPelanggaran = rekam_pelanggaran::with(['siswa', 'pelanggaran'])->get();
        return view('pelanggaran.list_rekam', compact('rekamPelanggaran'));
    }

    public function deleteRekamPelanggaran($id)
    {
        try {
            $rekam = rekam_pelanggaran::findOrFail($id);
            $rekam->delete();
            return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }

    public function exportPDF(Request $request)
    {
        $query = rekam_pelanggaran::with(['siswa.kelas', 'pelanggaran']);

        if ($request->kelas) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        if ($request->jenis) {
            $query->where('id_pelanggaran', $request->jenis);
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_pelanggaran', $request->tanggal);
        }

        $dataPelanggaran = $query->get();

        $pdf = \PDF::loadView('pelanggaran.export_pdf', compact('dataPelanggaran'));
        return $pdf->download('laporan_pelanggaran.pdf');
    }

    public function exportExcel(Request $request)
    {
        return \Excel::download(new \App\Exports\PelanggaranExport($request), 'laporan_pelanggaran.xlsx');
    }
}
