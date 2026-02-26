<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\RekamPelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelanggaranController extends Controller
{
    public function pilihJenis()
    {
        return view('guru.rekam.pilih-jenis');
    }

    public function formPelanggaran()
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;

        if (! $waliKelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki akses sebagai wali kelas.');
        }

        $kelas = $waliKelas->kelas;
        $siswa = Siswa::all();
        $pelanggaran = Pelanggaran::all();

        return view('guru.rekam.pelanggaran', compact('siswa', 'pelanggaran', 'kelas'));
    }

    public function storePelanggaran(Request $request)
    {
        try {
            $request->validate([
                'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
                'pelanggaran' => 'required|array|min:1',
                'pelanggaran.*' => 'exists:tbl_pelanggaran,id',
                'foto_pelanggaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

            ]);

            $user = Auth::user();
            $fotoPath = null;

            // Simpan foto jika ada
            if ($request->hasFile('foto_pelanggaran')) {
                $file = $request->file('foto_pelanggaran');
                $filename = 'pelanggaran_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $fotoPath = $file->storeAs('pelanggaran', $filename, 'public');
            }

            // Simpan data pelanggaran untuk setiap jenis yang dipilih

            DB::transaction(function () use ($request, $user, $fotoPath) {

                foreach ($request->pelanggaran as $idPelanggaran) {

                    $pelanggaran = Pelanggaran::findOrFail($idPelanggaran);

                    $jumlah = RekamPelanggaran::where('id_siswa', $request->id_siswa)
                        ->where('id_pelanggaran', $idPelanggaran)
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
                        'id_pelanggaran' => $idPelanggaran,
                        'tanggal_pelanggaran' => today(),
                        'foto_pelanggaran' => $fotoPath,
                        'id_user' => $user->id_user,
                        'poin_diberikan' => $poin,
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
                }

            });

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil direkam.']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pelanggaran berhasil direkam.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: '.$e->getMessage()], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function settings()
    {
        $user = auth()->user();
        $waliKelas = $user->waliKelas ?? null;

        return view('guru.settings', compact('user', 'waliKelas'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tbl_user,username,'.auth()->id().',id_user',
            'password' => 'nullable|string|min:6',
        ]);

        $user = auth()->user();

        $userData = ['username' => $request->username];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
