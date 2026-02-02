<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sholat;
use App\Models\Absensi;
use App\Models\Jurusan;
use App\Models\KunjunganUks;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class PiketController extends Controller
{
    /**
     * Dashboard
     */
    public function dashboard()
    {
        $now = Carbon::now();

        $totalSiswa = Siswa::count();

        $absensiHariIni = Absensi::whereDate('tanggal', today())
            ->whereNotNull('status_masuk')

            ->count();

        $izinPulangHariIni = KunjunganUks::where('jenis_kunjungan', 'izin_pulang')
            ->whereDate('tanggal', today())
            ->count();
        $dzuhur = Sholat::whereNotNull('dzuhur_masuk')
            ->whereDate('tanggal', today())
            ->count();
        $ashar = Sholat::whereNotNull('ashar_masuk')
            ->whereDate('tanggal', today())
            ->count();
        $absensi = Absensi::whereDate('tanggal', today())->latest()->take(10)->get();

        return view('piket.dashboard', compact(
            'totalSiswa',
            'absensiHariIni',
            'izinPulangHariIni',
            'dzuhur',
            'ashar',
            'absensi'
        ));
    }

    /**
     * List siswa
     */
    public function daftarSiswa(Request $request)
    {
        $query = Siswa::with('kelas.jurusan');

        // 🔍 Filter pencarian nama atau NISN
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%')
                    ->orWhere('card_code', 'like', '%' . $request->search . '%');
            });
        }

        // 🎓 Filter kelas
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        // 🏫 Filter jurusan
        if ($request->filled('jurusan')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('id_jurusan', $request->jurusan);
            });
        }

        // 📋 Urutkan berdasarkan nama kelas (ASC) lalu nama siswa (ASC)
        $query->join('tbl_kelas', 'tbl_siswa.id_kelas', '=', 'tbl_kelas.id_kelas')
            ->orderBy('tbl_kelas.nama_kelas', 'asc')
            ->orderBy('tbl_siswa.nama', 'asc')
            ->select('tbl_siswa.*'); // penting agar pagination tidak error
        $perpage = $request->get('per_page', 10);
        $siswa = $query->paginate($perpage)->withQueryString();
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        return view('piket.siswa.index', compact('siswa', 'kelas', 'jurusan'));
    }


    /**
     * List izin pulang
     */
    public function izinPulang(Request $request)
    {
        $siswa = Siswa::with('kelas.jurusan')->get();
        $perpage = $request->get('per_page', 10);
        $izin = KunjunganUks::with(['siswa.kelas.jurusan'])
            ->where('jenis_kunjungan', 'izin_pulang')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate($perpage);

        return view('piket.izin-pulang.index', compact('siswa', 'izin', 'perpage'));
    }

    /**
     * Form create izin pulang
     */
    public function createIzinPulang(Request $request)
    {
        $daftarSiswa = Siswa::with('kelas.jurusan')->get();
        $selectedSiswa = $request->id_siswa ?? null;

        return view('piket.izin-pulang.create', compact('daftarSiswa', 'selectedSiswa'));
    }

    /**
     * Simpan izin pulang
     */
    public function storeIzinPulang(Request $request)
    {

        $request->validate([
            'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
            'keterangan' => 'required|string|max:255',
        ]);

        try {

            // Pastikan ada user login
            if (! Auth::check()) {
                return back()->with('error', 'User belum login.');
            }

            $today = today();
            $now = now();

            // Ambil siswa
            $siswa = Siswa::findOrFail($request->id_siswa);

            // Cek absensi
            $absensi = Absensi::where('id_siswa', $siswa->id_siswa)
                ->whereDate('tanggal', $today)
                ->first();

            if (! $absensi || ! $absensi->waktu_masuk) {
                return back()
                    ->with('error', 'Siswa belum melakukan presensi masuk hari ini')
                    ->withInput();
            }

            // Cek apakah sudah pernah izin pulang
            $sudahIzin = KunjunganUks::where('id_siswa', $siswa->id_siswa)
                ->where('jenis_kunjungan', 'izin_pulang')
                ->whereDate('tanggal', $today)
                ->count();

            if ($sudahIzin > 0) {
                return back()
                    ->with('error', 'Izin pulang sudah dicatat hari ini')
                    ->withInput();
            }

            // Simpan izin
            KunjunganUks::create([
                'id_siswa' => $siswa->id_siswa,
                'id_petugas_uks' => Auth::id(),
                'tanggal' => $today,
                'waktu' => $now->toTimeString(),
                'jenis_kunjungan' => 'izin_pulang',
                'keterangan' => $request->keterangan,
            ]);

            // Update absensi
            $absensi->update([
                'waktu_pulang' => $now->toTimeString(),
                'status_pulang' => 'izin_pulang',
            ]);


            /* ================================ */

            return redirect()
                ->route('piket.izin-pulang')
                ->with('success', 'Izin pulang berhasil dicatat');
        } catch (\Exception $e) {

            Log::error('Error creating izin pulang: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function riwayat(Request $request)
    {
        $query = Absensi::with('siswa.kelas.jurusan');

        // Filter by search keyword (nama atau NISN)
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->whereHas('siswa', function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%");
            });
        }

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        // Filter by jurusan
        if ($request->filled('jurusan')) {
            $query->whereHas('siswa.kelas.jurusan', function ($q) use ($request) {
                $q->where('id_jurusan', $request->jurusan);
            });
        }

        // Filter by tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        $perpage = $request->get('per_page', 10);
        $absensi = $query->orderBy('tanggal', 'desc')->latest()->paginate($perpage)->withQueryString();

        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        return view('piket.riwayat', compact('absensi', 'kelas', 'jurusan', 'perpage'));
    }
    public function laporan(Request $request)
    {
        $query = Absensi::query()->with(['siswa.kelas.jurusan']);

        // Filter Nama Siswa
        if ($request->filled('nama')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        // Filter Kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        // Filter Jurusan melalui kelas
        if ($request->filled('jurusan')) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('id_jurusan', $request->jurusan);
            });
        }

        // Filter Bulan
        if ($request->filled('bulan')) {
            // Format input: YYYY-MM
            [$year, $month] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month);
        }

        $perpage = $request->get('per_page', 10);
        $absensi = $query->orderBy('tanggal', 'desc')->paginate($perpage);
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        return view('piket.laporan', compact('absensi', 'kelas', 'jurusan', 'request', 'perpage'));
    }



    // Export Excel
    public function exportLaporan(Request $request)
    {
        $filters = $request->only(['nama', 'kelas', 'jurusan', 'tanggal']);

        return Excel::download(new AbsensiExport($filters), 'laporan_absensi.xlsx');
    }
}
