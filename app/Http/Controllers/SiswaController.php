<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Sholat;
use App\Models\Siswa;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\RekamPelanggaran;
use Illuminate\Support\Facades\Schema;
use App\Models\RekamPrestasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        $absensiHariIni = Absensi::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', Carbon::today())
            ->first();

        // Guard database queries in case tables are not migrated yet
        $jumlahPoin = 0;
        $jumlahPoinPrestasi = 0;

        try {
            if (Schema::hasTable('tbl_rekam_pelanggaran') && Schema::hasTable('tbl_pelanggaran')) {
                $jumlahPoin = RekamPelanggaran::where('id_siswa', $siswa->id_siswa)
                    ->join('tbl_pelanggaran', 'tbl_rekam_pelanggaran.id_pelanggaran', '=', 'tbl_pelanggaran.id')
                    ->sum('poin_pelanggaran');
            }

            if (Schema::hasTable('tbl_rekam_prestasi_siswa') && Schema::hasTable('tbl_jenis_prestasi')) {
                $jumlahPoinPrestasi = RekamPrestasi::where('id_siswa', $siswa->id_siswa)
                    ->join('tbl_jenis_prestasi', 'tbl_rekam_prestasi_siswa.id_jenis_prestasi', '=', 'tbl_jenis_prestasi.id')
                    ->sum('poin_prestasi');
            }
        } catch (\Exception $e) {
            // Log and fallback to zero points when tables are missing or other DB errors occur
            \Illuminate\Support\Facades\Log::warning('Error fetching poin for siswa dashboard: ' . $e->getMessage());
            $jumlahPoin = 0;
            $jumlahPoinPrestasi = 0;
        }

        $absensiBulanIni = Absensi::where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->get();

        $pinjamanAktif = Peminjaman::with('barang')
            ->where('id_user', auth()->id())
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam','desc')
            ->get();

        return view('siswa.dashboard', compact('siswa', 'jumlahPoin', 'jumlahPoinPrestasi', 'absensiHariIni', 'absensiBulanIni', 'pinjamanAktif'));
    }

    public function riwayatSholat()
    {
        $user = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        $siswaId = $siswa->id_siswa;
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
        $user = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

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
        $user = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('siswa.settings', compact('siswa'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tbl_user,username,' . auth()->id() . ',id_user',
            'password' => 'nullable|string|min:6',
            'no_hp_ortu' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
        ]);

        $user = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Update user data
        $userData = ['username' => $request->username];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        // Update siswa data
        $siswa->update([
            'no_hp_ortu' => $request->no_hp_ortu,
        ]);

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
