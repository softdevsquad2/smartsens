<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiReportExport;
use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Pelanggaran;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;

        if (! $waliKelas) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses sebagai wali kelas.');
        }

        // If wali kelas doesn't have a class assigned, redirect to choose type
        if (!$waliKelas->id_kelas) {
            return redirect()->route('guru.rekam.pilih');
        }

        $kelas = $waliKelas->kelas;

        // Statistik siswa
        $totalSiswa = Siswa::where('id_kelas', $kelas->id_kelas)->count();

        // Statistik absensi hari ini
        $today = Carbon::today();
        $absensiHariIni = Absensi::where('tanggal', $today)
            ->whereHas('siswa', function ($query) use ($kelas) {
                $query->where('id_kelas', $kelas->id_kelas);
            })
            ->get();

        $hadirHariIni = $absensiHariIni
    ->whereIn('status_masuk', ['hadir', 'terlambat'])
    ->count();

        $izinHariIni = $absensiHariIni->where('status_masuk', 'izin')->count();
        $sakitHariIni = $absensiHariIni->where('status_masuk', 'sakit')->count();
        // Alpha = total siswa - siswa yang sudah absen (karena siswa yang tidak absen tidak masuk database)
        $sudahAbsenHariIni = $hadirHariIni + $izinHariIni + $sakitHariIni;
        $alphaHariIni = $totalSiswa - $sudahAbsenHariIni;

        // Statistik absensi bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $absensiBulanIni = Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereHas('siswa', function ($query) use ($kelas) {
                $query->where('id_kelas', $kelas->id_kelas);
            })
            ->get();

        $totalAbsensiBulanIni = $absensiBulanIni->count();
        $hadirBulanIni = $absensiBulanIni->whereIn('status_masuk', ['masuk', 'terlambat'])->count();
        $izinBulanIni = $absensiBulanIni->where('status_masuk', 'izin')->count();
        $sakitBulanIni = $absensiBulanIni->where('status_masuk', 'sakit')->count();
        // Alpha bulan ini = total siswa × jumlah hari dalam bulan - total record absensi
        $jumlahHariBulanIni = Carbon::now()->daysInMonth;
        $totalAbsensiYangSeharusnya = $totalSiswa * $jumlahHariBulanIni;
        $alphaBulanIni = $totalAbsensiYangSeharusnya - $totalAbsensiBulanIni;

        // Data absensi per hari untuk diagram garis (hanya hadir)
        $dailyAttendanceData = [];
        $currentDate = $startOfMonth->copy();
        while ($currentDate <= $endOfMonth) {
            $dateString = $currentDate->format('Y-m-d');
            $absensiHariIni = $absensiBulanIni->where('tanggal', $dateString);

            $hadir = $absensiHariIni->whereIn('status_masuk', ['masuk', 'terlambat'])->count();

            $dailyAttendanceData[] = [
                'date' => $currentDate->format('d/m'),
                'hadir' => $hadir
            ];

            $currentDate->addDay();
        }

        // Persentase kehadiran bulan ini
        $persentaseKehadiran = $totalAbsensiBulanIni > 0
            ? round(($hadirBulanIni / $totalAbsensiBulanIni) * 100, 1)
            : 0;

        return view('guru.dashboard', compact(
            'waliKelas',
            'kelas',
            'totalSiswa',
            'hadirHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alphaHariIni',
            'hadirBulanIni',
            'izinBulanIni',
            'sakitBulanIni',
            'alphaBulanIni',
            'persentaseKehadiran',
            'dailyAttendanceData'
        ));
    }

    public function daftarSiswa(Request $request)
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;
        $kelas = $waliKelas->kelas;
        $perpage = $request->get('per_page', 10);
        $siswa = Siswa::where('id_kelas', $kelas->id_kelas)
            ->with('user')
            ->paginate($perpage);

        return view('guru.siswa.index', compact('waliKelas', 'kelas', 'siswa', 'perpage'));
    }

    public function absensiHariIni()
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;
        $kelas = $waliKelas->kelas;
        $today = Carbon::today();

        // Ambil semua siswa di kelas beserta absensi hari ini (jika ada)
        $siswa = Siswa::where('id_kelas', $kelas->id_kelas)
            ->with(['absensi' => function ($query) use ($today) {
                $query->where('tanggal', $today);
            }])
            ->get();

        return view('guru.absensi.hari-ini', compact('waliKelas', 'kelas', 'siswa', 'today'));
    }

    public function laporanAbsensi(Request $request)
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;
        $kelas = $waliKelas->kelas;

        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $absensi = Absensi::whereBetween('tanggal', [$startDate, $endDate])
            ->whereHas('siswa', function ($query) use ($kelas) {
                $query->where('id_kelas', $kelas->id_kelas);
            })
            ->with('siswa')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        return view('guru.absensi.laporan', compact('waliKelas', 'kelas', 'absensi', 'bulan', 'startDate', 'endDate'));
    }

    /**
     * Export absensi laporan as XLSX for the selected month
     */
    public function exportAbsensiXlsx(Request $request)
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;
        $kelas = $waliKelas->kelas;

        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $filename = sprintf('laporan-absensi-%s-%s.xlsx', $kelas->nama_kelas, $bulan);

        return Excel::download(new AbsensiReportExport($kelas->id_kelas, $startDate, $endDate), $filename);
    }
}
