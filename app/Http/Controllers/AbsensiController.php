<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pelanggaran;
use App\Models\RekamPelanggaran;
use App\Models\Setting;
use App\Models\Sholat;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('siswa.kelas.jurusan');

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%');
            });
        }

        // Filter by date
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_masuk', $request->status);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->paginate(20)->withQueryString();

        // Get filter values for view
        $search = $request->get('search');
        $tanggal = $request->get('tanggal');
        $status = $request->get('status');

        return view('admin.absensi.index', compact('absensi', 'search', 'tanggal', 'status'));
    }

    public function show($id)
    {
        $absensi = Absensi::with('siswa.kelas.jurusan')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $absensi,
        ]);
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);

        // Hapus foto jika ada
        if ($absensi->photo_path && Storage::disk('public')->exists($absensi->photo_path)) {
            Storage::disk('public')->delete($absensi->photo_path);
        }

        $absensi->delete();

        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil dihapus');
    }

    public function absenMasuk(Request $request)
    {
        // Log the request for debugging
        Log::info('AbsenMasuk request received:', [
            'id_siswa' => $request->id_siswa,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'has_photo' => $request->hasFile('photo'),
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role ?? 'not_authenticated',
        ]);

        try {
            // Cek apakah siswa ada
            $siswa = Siswa::find($request->id_siswa);
            if (! $siswa) {
                Log::error('Siswa not found:', ['id_siswa' => $request->id_siswa]);

                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan',
                ], 404);
            }

            $request->validate([
                'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
                'longitude' => 'required|numeric',
                'latitude' => 'required|numeric',
                'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in absenMasuk:', $e->errors());
            $errors = $e->errors();
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                $errorMessages[] = $field.': '.implode(', ', $messages);
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: '.implode('; ', $errorMessages),
            ], 422);
        }

        try {
            // Cek apakah sudah absen masuk hari ini
            $absensiHariIni = Absensi::where('id_siswa', $request->id_siswa)
                ->where('tanggal', Carbon::today())
                ->first();

            if ($absensiHariIni && $absensiHariIni->waktu_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi masuk hari ini',
                ]);
            }

            // Validasi GPS - cek apakah dalam radius sekolah
            $schoolLat = Setting::getSetting('school_latitude');
            $schoolLng = Setting::getSetting('school_longitude');
            $radius = Setting::getSetting('attendance_radius') ?? 100; // default 100 meter

            Log::info('GPS Settings:', [
                'school_lat' => $schoolLat,
                'school_lng' => $schoolLng,
                'radius' => $radius,
                'user_lat' => $request->latitude,
                'user_lng' => $request->longitude,
            ]);

            $isWithinRadius = $this->isWithinRadius($request->latitude, $request->longitude, $schoolLat, $schoolLng, $radius);

            // Foto selalu diperlukan untuk semua absensi
            if (! $request->hasFile('photo') || ! $request->file('photo')->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan upload foto untuk absensi.',
                ]);
            }

            // Cek waktu absensi
            $jamMasuk = Setting::getSetting('jam_masuk') ?? '06:00';
            $jamTerlambat = Setting::getSetting('jam_terlambat') ?? '06:30';

            $waktuSekarang = Carbon::now();
            $waktuSekarangStr = $waktuSekarang->format('H:i');
            $statusMasuk = 'hadir';
            $photoPath = null;
            $jamMasuk = Setting::getSetting('jam_masuk') ?? '06:00';

            $waktuSekarang = Carbon::now();
            $waktuMasuk = Carbon::createFromFormat('H:i', $jamMasuk);

            // Cek apakah terlambat
            if ($waktuSekarangStr > $jamTerlambat) {
                $statusMasuk = 'terlambat';
            }

            // Handle photo upload for all attendance
            $photoPath = null;
            if ($request->hasFile('photo')) {
                try {
                    $photo = $request->file('photo');
                    $photoName = time().'_'.$request->id_siswa.'_masuk.'.$photo->getClientOriginalExtension();
                    $photoPath = $photo->storeAs('attendance_photos', $photoName, 'public');
                    Log::info('Photo uploaded successfully:', ['path' => $photoPath]);
                } catch (\Exception $e) {
                    Log::error('Photo upload failed:', ['error' => $e->getMessage()]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload foto: '.$e->getMessage(),
                    ], 500);
                }
            }

            // Jika di luar radius, set status sakit/izin
            if (! $isWithinRadius) {
                $attendanceType = $request->input('attendance_type', 'sakit_izin');
                if ($attendanceType === 'sakit') {
                    $statusMasuk = 'sakit';
                } elseif ($attendanceType === 'izin') {
                    $statusMasuk = 'izin';
                } else {
                    $statusMasuk = 'sakit_izin';
                }
            }

            // Simpan absensi
            try {
                if ($absensiHariIni) {
                    $absensiHariIni->update([
                        'waktu_masuk' => Carbon::now()->format('H:i:s'),
                        'longitude_masuk' => $request->longitude,
                        'latitude_masuk' => $request->latitude,
                        'status_masuk' => $statusMasuk,
                        'foto_masuk' => $photoPath,
                    ]);
                    Log::info('Absensi updated successfully');
                } else {
                    Absensi::create([
                        'id_siswa' => $request->id_siswa,
                        'tanggal' => Carbon::today(),
                        'waktu_masuk' => Carbon::now()->format('H:i:s'),
                        'longitude_masuk' => $request->longitude,
                        'latitude_masuk' => $request->latitude,
                        'status_masuk' => $statusMasuk,
                        'foto_masuk' => $photoPath,
                    ]);
                    Log::info('Absensi created successfully');
                }

                if ($statusMasuk === 'terlambat') {

                    try {

                        $selisihMenit = $waktuMasuk->diffInMinutes($waktuSekarang);

                        // Tentukan pelanggaran berdasarkan menit
                        if ($selisihMenit <= 10) {
                            $pelanggaran = Pelanggaran::where('nama_pelanggaran', 'Terlambat Kurang dari 10 menit')->first();
                        } elseif ($selisihMenit <= 30) {
                            $pelanggaran = Pelanggaran::where('nama_pelanggaran', 'Terlambat Lebih dari 10 menit')->first();
                        } else {
                            $pelanggaran = Pelanggaran::where('nama_pelanggaran', 'Terlambat Lebih dari 30 menit')->first();
                        }

                        if (! $pelanggaran) {
                            Log::error('Pelanggaran tidak ditemukan untuk keterlambatan', [
                                'selisih_menit' => $selisihMenit,
                            ]);

                            return response()->json([
                                'success' => false,
                                'message' => 'Data pelanggaran keterlambatan tidak ditemukan di database',
                            ], 500);
                        }

                        $jumlah = RekamPelanggaran::where('id_siswa', $request->id_siswa)
                            ->where('id_pelanggaran', $pelanggaran->id)
                            ->count();

                        if ($jumlah == 0) {
                            $poin = $pelanggaran->poin_1;
                        } elseif ($jumlah == 1) {
                            $poin = $pelanggaran->poin_2;
                        } else {
                            $poin = $pelanggaran->poin_3;
                        }

                        RekamPelanggaran::create([
                            'id_siswa' => $request->id_siswa,
                            'id_pelanggaran' => $pelanggaran->id,
                            'tanggal_pelanggaran' => Carbon::today(),
                            'poin_diberikan' => $poin,
                            'foto_pelanggaran' => null,
                            'id_user' => Auth::id(),
                            'pelapor' => 'system',
                        ]);
                        $siswa = Siswa::where('id_siswa', $request->id_siswa)
                            ->lockForUpdate()
                            ->first();

                        $totalBaru = $siswa->total_poin - $poin;

                        $spBaru = null;

                        if ($totalBaru <= -76) {
                            $spBaru = 'SP3';
                        } elseif ($totalBaru <= -51) {
                            $spBaru = 'SP2';
                        } elseif ($totalBaru <= -25) {
                            $spBaru = 'SP1';
                        }

                        $urutan = [
                            null => 0,
                            'SP1' => 1,
                            'SP2' => 2,
                            'SP3' => 3,
                        ];

                        $spFinal = $siswa->sp_tertinggi;

                        if ($urutan[$spBaru] > $urutan[$siswa->sp_tertinggi]) {
                            $spFinal = $spBaru;
                        }

                        $siswa->update([
                            'total_poin' => $totalBaru,
                            'status_sp' => $spFinal,
                            'sp_tertinggi' => $spFinal,
                        ]);

                        Log::info('Rekam pelanggaran berhasil disimpan', [
                            'id_siswa' => $request->id_siswa,
                            'poin' => $poin,
                        ]);

                    } catch (\Exception $e) {
                        Log::error('ERROR SAAT SIMPAN REKAM PELANGGARAN: '.$e->getMessage());

                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal menyimpan rekam pelanggaran: '.$e->getMessage(),
                        ], 500);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Database error in absenMasuk:', ['error' => $e->getMessage()]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan absensi: '.$e->getMessage(),
                ], 500);
            }

            // return redirect()->route('siswa.dashboard')->with('success', 'Absensi masuk berhasil');
            return response()->json([
                'success' => true,
                'message' => 'Absensi masuk berhasil',
                'status' => $statusMasuk,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in absenMasuk: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: '.$e->getMessage(),
            ], 500);
        }
    }

    public function absenPulang(Request $request)
    {
        try {
            // 1. Validasi request
            $request->validate([
                'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
                'longitude' => 'required|numeric',
                'latitude' => 'required|numeric',
                'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in absenPulang:', $e->errors());
            $errors = $e->errors();
            $errorMessages = [];
            foreach ($errors as $field => $messages) {
                $errorMessages[] = $field.': '.implode(', ', $messages);
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: '.implode('; ', $errorMessages),
            ], 422);
        }

        try {
            // 2. Cek jam pulang
            $jamPulang = Setting::getSetting('jam_pulang') ?? '15:00';
            $waktuSekarang = Carbon::now();
            $waktuSekarangStr = $waktuSekarang->format('H:i');

            if ($waktuSekarangStr < $jamPulang) {
                return response()->json([
                    'success' => false,
                    'message' => "Belum jam pulang. Absen pulang hanya bisa dilakukan setelah jam {$jamPulang}",
                ], 400);
            }

            // 3. Cek absensi masuk hari ini
            $absensiHariIni = Absensi::where('id_siswa', $request->id_siswa)
                ->where('tanggal', Carbon::today())
                ->first();

            if (! $absensiHariIni || ! $absensiHariIni->waktu_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum melakukan absensi masuk hari ini',
                ]);
            }

            if ($absensiHariIni->waktu_pulang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi pulang hari ini',
                ]);
            }

            // 4. Cek sholat Dzuhur & Ashar
            $sholatHariIni = Sholat::where('id_siswa', $request->id_siswa)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            // if (
            //     !$sholatHariIni ||
            //     !$sholatHariIni->dzuhur_masuk ||
            //     !$sholatHariIni->dzuhur_keluar ||
            //     !$sholatHariIni->ashar_masuk ||
            //     !$sholatHariIni->ashar_keluar
            // ) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Anda belum melakukan sholat Dzuhur/Ashar. Harus sholat dulu sebelum absen pulang.',
            //     ], 400);
            // }

            // 5. Validasi GPS (apakah dalam radius sekolah)
            $schoolLat = Setting::getSetting('school_latitude');
            $schoolLng = Setting::getSetting('school_longitude');
            $radius = Setting::getSetting('attendance_radius') ?? 100;

            $isWithinRadius = $this->isWithinRadius(
                $request->latitude,
                $request->longitude,
                $schoolLat,
                $schoolLng,
                $radius
            );

            if (! $isWithinRadius) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar radius sekolah. Absen pulang tidak diperbolehkan.',
                ], 400);
            }

            // 6. Upload foto
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time().'_'.$request->id_siswa.'_pulang.'.$photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('attendance_photos', $photoName, 'public');
            }

            // 7. Update absensi pulang
            $absensiHariIni->update([
                'waktu_pulang' => Carbon::now()->format('H:i:s'),
                'longitude_pulang' => $request->longitude,
                'latitude_pulang' => $request->latitude,
                'status_pulang' => 'pulang',
                'foto_pulang' => $photoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi pulang berhasil',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in absenPulang: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: '.$e->getMessage(),
            ], 500);
        }
    }

    public function markAbsentStudents()
    {
        // Fungsi ini dinonaktifkan - siswa yang tidak absen tidak akan mendapat record di database
        // Sebelumnya: menandai siswa yang tidak absen sebagai alfa
        // return; // Non-aktifkan proses penandaan alpha
    }

    /**
     * Cek dan tandai siswa yang tidak absen pulang hingga jam 17:00 dengan pelanggaran "bolos"
     * Method ini bisa di-trigger dari scheduled task atau endpoint
     */
    public function checkAndMarkAbsenBolos()
    {
        try {
            Log::info('Starting bolos attendance check...');

            // Cari siswa yang sudah absen masuk hari ini tapi belum absen pulang
            $siswaBolos = Absensi::where('tanggal', Carbon::today())
                ->whereNotNull('waktu_masuk')
                ->whereNull('waktu_pulang')
                ->whereIn('status_masuk', ['hadir', 'terlambat'])
                ->get();

            Log::info('Found '.$siswaBolos->count().' candidate(s) for bolos');

            // Get pelanggaran record once
            $pelanggaranBolos = Pelanggaran::where('nama_pelanggaran', 'Tidak Absensi Pulang')->first();
            if (! $pelanggaranBolos) {
                Log::warning('Pelanggaran "Tidak Absensi Pulang" tidak ditemukan di database');

                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggaran "Tidak Absensi Pulang" tidak ditemukan di database',
                ]);
            }

            Log::info('Pelanggaran Tidak Absensi Pulang id: '.$pelanggaranBolos->id);

            $countBolos = 0;

            foreach ($siswaBolos as $absensi) {
                Log::info('Processing siswa: '.$absensi->id_siswa);

                // Cek apakah sudah ada record pelanggaran bolos untuk siswa ini hari ini
                $rekamBolosSudahAda = RekamPelanggaran::where('id_siswa', $absensi->id_siswa)
                    ->where('id_pelanggaran', $pelanggaranBolos->id)
                    ->whereDate('tanggal_pelanggaran', Carbon::today())
                    ->first();

                Log::info('Existing RekamPelanggaran: '.($rekamBolosSudahAda ? 'YES' : 'NO'));

                if (! $rekamBolosSudahAda) {
                    try {
                        $jumlah = RekamPelanggaran::where('id_siswa', $absensi->id_siswa)
                            ->where('id_pelanggaran', $pelanggaranBolos->id)
                            ->count();

                        if ($jumlah == 0) {
                            $poin = $pelanggaranBolos->poin_1;
                        } elseif ($jumlah == 1) {
                            $poin = $pelanggaranBolos->poin_2;
                        } else {
                            $poin = $pelanggaranBolos->poin_3;
                        }
                        // Buat record pelanggaran bolos
                        RekamPelanggaran::create([
                            'id_siswa' => $absensi->id_siswa,
                            'id_pelanggaran' => $pelanggaranBolos->id,
                            'tanggal_pelanggaran' => Carbon::today(),
                            'foto_pelanggaran' => null,
                            'id_user' => null,
                            'pelapor' => 'system',
                            'poin_diberikan' => $poin,
                        ]);

                        Log::info('Created RekamPelanggaran for siswa '.$absensi->id_siswa);

                        // Update status pulang menjadi 'bolos' HANYA JIKA RekamPelanggaran berhasil dibuat
                        $absensi->update([
                            'status_pulang' => 'bolos',
                        ]);

                        $siswa = Siswa::where('id_siswa', $absensi->id_siswa)
                            ->lockForUpdate()
                            ->first();

                        $totalBaru = $siswa->total_poin - $poin;

                        $spBaru = null;

                        if ($totalBaru <= -76) {
                            $spBaru = 'SP3';
                        } elseif ($totalBaru <= -51) {
                            $spBaru = 'SP2';
                        } elseif ($totalBaru <= -25) {
                            $spBaru = 'SP1';
                        }

                        $urutan = [
                            null => 0,
                            'SP1' => 1,
                            'SP2' => 2,
                            'SP3' => 3,
                        ];

                        $spFinal = $siswa->sp_tertinggi;

                        if ($urutan[$spBaru] > $urutan[$siswa->sp_tertinggi]) {
                            $spFinal = $spBaru;
                        }

                        $siswa->update([
                            'total_poin' => $totalBaru,
                            'status_sp' => $spFinal,
                            'sp_tertinggi' => $spFinal,
                        ]);

                        Log::info('Updated status_pulang to bolos for siswa: '.$absensi->id_siswa);

                        $countBolos++;
                    } catch (\Exception $e) {
                        Log::error('Error creating RekamPelanggaran for siswa '.$absensi->id_siswa.': '.$e->getMessage());
                    }
                } else {
                    Log::info('RekamPelanggaran sudah ada untuk siswa: '.$absensi->id_siswa);
                }
            }

            Log::info('Bolos attendance check completed', ['total_marked' => $countBolos]);

            return response()->json([
                'success' => true,
                'message' => "Pengecekan bolos selesai. Total {$countBolos} siswa ditandai bolos.",
                'total_marked' => $countBolos,
                'status' => $countBolos,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in checkAndMarkAbsenBolos: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: '.$e->getMessage(),
            ], 500);
        }
    }

    private function isWithinRadius($lat1, $lng1, $lat2, $lng2, $radius)
    {
        if (! $lat2 || ! $lng2) {
            return true; // Jika setting GPS belum ada, izinkan absensi
        }

        $earthRadius = 6371000; // Radius bumi dalam meter

        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLng / 2) * sin($deltaLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance <= $radius;
    }

    public function showDetail($id)
    {
        $absen = Absensi::with(['siswa.kelas.jurusan'])->findOrFail($id);

        // Ambil setting lokasi sekolah & radius
        $schoolLat = Setting::getSetting('school_latitude');
        $schoolLng = Setting::getSetting('school_longitude');
        $radius = Setting::getSetting('attendance_radius') ?? 100; // default 100 meter

        // Default lokasi
        $lokasi = '-';

        // Jika ada koordinat absensi
        if ($absen->latitude_masuk && $absen->longitude_masuk) {
            $isWithin = $this->isWithinRadius(
                $absen->latitude_masuk,
                $absen->longitude_masuk,
                $schoolLat,
                $schoolLng,
                $radius
            );

            if ($isWithin) {
                $lokasi = 'SMK Negeri 2 Tasikmalaya';
            } else {
                $lokasi = 'Absen di luar radius';
            }
        }

        return response()->json([
            'nama_siswa' => $absen->siswa->nama ?? '-',
            'statusMasuk' => $absen->status_masuk ?? '-',
            'kelas' => $absen->siswa->kelas->nama_kelas ?? '-',
            'jurusan' => $absen->siswa->kelas->jurusan->nama_jurusan ?? '-',
            'tanggal' => \Carbon\Carbon::parse($absen->tanggal)->format('d M Y'),
            'waktu_masuk' => $absen->waktu_masuk ?? '-',
            'photo_path' => $absen->foto_masuk ? asset('storage/'.$absen->foto_masuk) : null,
            'photo_path_pulang' => $absen->foto_pulang ? asset('storage/'.$absen->foto_pulang) : null,
            'lokasi' => $lokasi,
        ]);
    }
}
