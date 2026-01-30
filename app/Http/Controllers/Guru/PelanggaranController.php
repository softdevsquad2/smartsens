<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\RekamPelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        if (!$waliKelas) {
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
                $filename = 'pelanggaran_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $fotoPath = $file->storeAs('pelanggaran', $filename, 'public');
            }

            // Simpan data pelanggaran untuk setiap jenis yang dipilih
            foreach ($request->pelanggaran as $idPelanggaran) {
                RekamPelanggaran::create([
                    'id_siswa' => $request->id_siswa,
                    'id_pelanggaran' => $idPelanggaran,
                    'tanggal_pelanggaran' => today(),
                    'foto_pelanggaran' => $fotoPath,
                    'id_user' => $user->id_user,

                ]);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Pelanggaran berhasil direkam.']);
            }

            return response()->json([
        'success' => true,
        'message' => 'Prestasi berhasil direkam.'
    ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
