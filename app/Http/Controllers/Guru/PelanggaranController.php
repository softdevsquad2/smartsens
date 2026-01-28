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
        $siswa = Siswa::where('id_kelas', $kelas->id_kelas)->get();
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
                'tanggal_pelanggaran' => 'required|date',
                'pelapor' => 'required|string|max:100',
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
                    'tanggal_pelanggaran' => $request->tanggal_pelanggaran,
                    'foto_pelanggaran' => $fotoPath,
                    'id_user' => $user->id_user,
                    'pelapor' => $request->pelapor,
                ]);
            }

            return redirect()->route('guru.dashboard')
                ->with('success', 'Pelanggaran berhasil direkam.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
