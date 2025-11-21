<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Jurusan;
use App\Exports\AbsensiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();
        $totalGuru = User::where('role', 'guru')->count();

        // Absensi hari ini
        $absensiHariIni = Absensi::where('tanggal', Carbon::today())->count();
        $siswaHadirHariIni = Absensi::where('tanggal', Carbon::today())
            ->where('status_masuk', 'hadir')
            ->count();
        $siswaTerlambatHariIni = Absensi::where('tanggal', Carbon::today())
            ->where('status_masuk', 'terlambat')
            ->count();

        // Statistik bulan ini
        $absensiBulanIni = Absensi::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();

        // Grafik absensi 7 hari terakhir
        $absensi7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i);
            $absensi7Hari[] = [
                'tanggal' => $tanggal->format('Y-m-d'),
                'label' => $tanggal->format('d M'),
                'hadir' => Absensi::where('tanggal', $tanggal->format('Y-m-d'))
                    ->where('status_masuk', 'hadir')
                    ->count(),
                'terlambat' => Absensi::where('tanggal', $tanggal->format('Y-m-d'))
                    ->where('status_masuk', 'terlambat')
                    ->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalKelas',
            'totalJurusan',
            'totalGuru',
            'absensiHariIni',
            'siswaHadirHariIni',
            'siswaTerlambatHariIni',
            'absensiBulanIni',
            'absensi7Hari'
        ));
    }
    public function backup()
    {
        $fileName = 'backup_absensi_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new AbsensiExport, $fileName);
    }
    public function backupDatabase()
    {
        // Ambil konfigurasi dari .env
        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        // Nama file backup
        $fileName = 'backup_' . $dbName . '_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $fileName);

        // Pastikan folder backup ada
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }

        // ✅ Path lengkap ke mysqldump (ubah sesuai lokasi MySQL kamu)
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';

        // Jalankan perintah mysqldump
        $command = sprintf(
            '"%s" --user=%s --password=%s --host=%s --port=%s %s > %s',
            $mysqldumpPath,
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbName),
            escapeshellarg($backupPath)
        );

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result !== 0) {
            return back()->with('error', 'Gagal membuat backup database. Pastikan path mysqldump sudah benar.');
        }

        // Kirim file hasil backup untuk di-download
        return response()->download($backupPath)->deleteFileAfterSend(true);
    }


    public function showRestoreForm()
    {
        return view('admin.restore'); // kita buat view-nya di langkah berikut
    }
}
