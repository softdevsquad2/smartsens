<?php

namespace App\Http\Controllers\UKS;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUks;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\StokObat;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();

        // Kunjungan Statistics
        $totalKunjungan = KunjunganUks::count();
        $kunjunganBulanIni = KunjunganUks::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Obat Statistics
        $totalObat = Obat::count();
        $obatMenipis = StokObat::with('obat')
            ->where('jumlah', '<=', 10)
            ->count();

        // Rekam Medis Statistics
        $totalRekamMedis = RekamMedis::count();
        $rekamMedisMingguIni = RekamMedis::whereBetween('created_at', [
            $now->copy()->startOfWeek(),
            $now->copy()->endOfWeek(),
        ])->count();

        // Izin Pulang Statistics
        $totalIzinPulang = KunjunganUks::where('izin_pulang', true)->count();
        $izinPulangHariIni = KunjunganUks::where('izin_pulang', true)
            ->whereDate('created_at', $now->toDateString())
            ->count();

        // Recent Data
        $recentKunjungan = KunjunganUks::with(['siswa.kelas'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockObat = StokObat::with('obat')
            ->where('jumlah', '<=', 10)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('uks.dashboard', compact(
            'totalKunjungan',
            'kunjunganBulanIni',
            'totalObat',
            'obatMenipis',
            'totalRekamMedis',
            'rekamMedisMingguIni',
            'totalIzinPulang',
            'izinPulangHariIni',
            'recentKunjungan',
            'lowStockObat'
        ));
    }
}
