<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Sholat;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $siswa = auth()->user()->siswa;
        $absensiHariIni = Absensi::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', Carbon::today())
            ->first();

        $absensiBulanIni = Absensi::where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->get();

        return view('siswa.dashboard', compact('siswa', 'absensiHariIni', 'absensiBulanIni'));
    }

    public function riwayatSholat()
    {
        $siswaId = auth()->user()->siswa->id_siswa;
        $tanggalHariIni = Carbon::today()->toDateString();

        // Ambil hanya data sholat hari ini
        $riwayatHariIni = Sholat::where('id_siswa', $siswaId)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        return view('siswa.riwayat-sholat', compact('riwayatHariIni', 'tanggalHariIni'));
    }

    public function absen()
    {
        return view('siswa.absen');
    }

    public function searchSiswa(Request $request)
    {
        $query = $request->get('q');

        // Ambil data siswa yang cocok dengan nama atau NISN
        $students = Siswa::with(['kelas.jurusan'])
            ->where('nama', 'like', "%{$query}%")
            ->orWhereRaw('CAST(nisn AS CHAR) LIKE ?', ["%{$query}%"])
            ->limit(10)
            ->get();

        // Format data untuk dropdown (biar ringan dan rapi)
        $formatted = $students->map(function ($s) {
            return [
                'id_siswa' => $s->id_siswa,
                'nama' => $s->nama,
                'nisn' => $s->nisn,
                'kelas' => $s->kelas->nama_kelas ?? '-',
                'jurusan' => $s->kelas->jurusan->nama_jurusan ?? '-',
            ];
        });

        return response()->json($formatted);
    }

    public function getStatusAbsensi()
    {
        $siswa = auth()->user()->siswa;
        $absensiHariIni = Absensi::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', Carbon::today())
            ->first();

        return response()->json([
            'waktu_masuk' => $absensiHariIni->waktu_masuk ?? null,
            'waktu_pulang' => $absensiHariIni->waktu_pulang ?? null,
            'status_masuk' => $absensiHariIni->status_masuk ?? null,
            'status_pulang' => $absensiHariIni->status_pulang ?? null,
        ]);
    }

    public function riwayatAbsensi(Request $request)
    {
        $siswa = auth()->user()->siswa;

        // Default bulan dan tahun saat ini
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        // Ambil data absensi untuk bulan dan tahun yang dipilih
        $absensi = Absensi::where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        // Hitung statistik untuk bulan yang dipilih
        $totalAbsensi = Absensi::where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $statistik = [
            'hadir' => $totalAbsensi->where('status_masuk', 'hadir')->count(),
            'terlambat' => $totalAbsensi->where('status_masuk', 'terlambat')->count(),
            'sakit' => $totalAbsensi->where('status_masuk', 'sakit')->count(),
            'izin' => $totalAbsensi->where('status_masuk', 'izin')->count(),
            'sakit_izin' => $totalAbsensi->where('status_masuk', 'sakit_izin')->count(),
            'alfa' => $totalAbsensi->where('status_masuk', 'alfa')->count(),
            'total' => $totalAbsensi->count(),
        ];

        // Daftar bulan untuk dropdown
        $daftarBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('siswa.riwayat-absensi', compact('absensi', 'statistik', 'bulan', 'tahun', 'daftarBulan'));
    }

    public function settings()
    {
        $siswa = auth()->user()->siswa;

        return view('siswa.settings', compact('siswa'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'no_hp_ortu' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
        ]);

        $siswa = auth()->user()->siswa;
        $siswa->update([
            'no_hp_ortu' => $request->no_hp_ortu,
        ]);

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
