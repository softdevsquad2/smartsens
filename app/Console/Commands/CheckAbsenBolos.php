<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Pelanggaran;
use App\Models\RekamPelanggaran;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAbsenBolos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:check-bolos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and mark students who did not attend checkout after 17:00';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $waktuSekarang = Carbon::now();
            $waktuSekarangStr = $waktuSekarang->format('H:i');
            $jamBatasBolos = '08:40';

            $this->info('Checking for bolos attendance at: ' . $waktuSekarangStr);

            // Hanya jalankan jika waktu sudah melewati 08:30
            if ($waktuSekarangStr < $jamBatasBolos) {
                $this->warn('Bolos check can only be run after 08:40. Current time: ' . $waktuSekarangStr);
                return Command::FAILURE;
            }

            // Cari siswa yang sudah absen masuk hari ini tapi belum absen pulang
            $siswaBolos = Absensi::where('tanggal', Carbon::today())
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_pulang')
                ->get();

            $countBolos = 0;

            foreach ($siswaBolos as $absensi) {
                // Cek apakah sudah ada pencatatan pelanggaran bolos untuk hari ini
                $pelanggaranBolos = Pelanggaran::where('nama_pelanggaran', 'Bolos')->first();

                if (!$pelanggaranBolos) {
                    $this->warn('Pelanggaran "Bolos" tidak ditemukan di database');
                    Log::warning('Pelanggaran "Bolos" tidak ditemukan di database');
                    continue;
                }

                // Cek apakah sudah ada record pelanggaran bolos untuk siswa ini hari ini
                $rekamBolosSudahAda = RekamPelanggaran::where('id_siswa', $absensi->id_siswa)
                    ->where('id_pelanggaran', $pelanggaranBolos->id)
                    ->whereDate('tanggal_pelanggaran', Carbon::today())
                    ->first();

                if (!$rekamBolosSudahAda) {
                    // Buat record pelanggaran bolos
                    RekamPelanggaran::create([
                        'id_siswa' => $absensi->id_siswa,
                        'id_pelanggaran' => $pelanggaranBolos->id,
                        'tanggal_pelanggaran' => Carbon::today(),
                        'foto_pelanggaran' => null,
                        'id_user' => null,
                        'pelapor' => 'system',
                    ]);

                    $countBolos++;
                    $this->line("Marked student {$absensi->id_siswa} as bolos");
                    Log::info('Pelanggaran bolos recorded for student:', [
                        'id_siswa' => $absensi->id_siswa,
                        'tanggal' => Carbon::today(),
                    ]);
                }
            }

            $this->info("Bolos check completed. Total {$countBolos} students marked as bolos.");
            Log::info('Bolos attendance check completed', ['total_marked' => $countBolos]);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error in checkAbsenBolos: ' . $e->getMessage());
            Log::error('Error in checkAbsenBolos: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
