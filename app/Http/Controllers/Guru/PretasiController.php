<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JenisPrestasi;
use App\Models\RekamPrestasi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PretasiController extends Controller
{
    public function formPrestasi()
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;

        if (!$waliKelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki akses sebagai wali kelas.');
        }

        $kelas = $waliKelas->kelas;
        $siswa = Siswa::where('id_kelas', $kelas->id_kelas)->get();
        $jenisPrestasi = JenisPrestasi::all();

        return view('guru.rekam.prestasi', compact('siswa', 'jenisPrestasi', 'kelas'));
    }

    public function storePrestasi(Request $request)
    {
        try {
            $request->validate([
                'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
                'id_jenis_prestasi' => 'required|exists:tbl_jenis_prestasi,id',
                'tanggal_prestasi' => 'required|date',
                'bukti_prestasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'nullable|string|max:500',
                'pembimbing' => 'required|string|max:100',
            ]);

            $user = Auth::user();
            $buktiPath = null;

            // Simpan bukti jika ada
            if ($request->hasFile('bukti_prestasi')) {
                $file = $request->file('bukti_prestasi');
                $filename = 'prestasi_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('prestasi', $filename, 'public');
            }

            RekamPrestasi::create([
                'id_siswa' => $request->id_siswa,
                'id_jenis_prestasi' => $request->id_jenis_prestasi,
                'tanggal_prestasi' => $request->tanggal_prestasi,
                'bukti_prestasi' => $buktiPath,
                'keterangan' => $request->keterangan,
                'id_user' => $user->id_user,
                'pembimbing' => $request->pembimbing,
            ]);

            return redirect()->route('guru.dashboard')
                ->with('success', 'Prestasi berhasil direkam.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
