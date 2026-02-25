<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JenisPrestasi;
use App\Models\RekamPrestasi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PretasiController extends Controller
{
    public function formPrestasi()
    {
        $user = Auth::user();
        $waliKelas = $user->waliKelas;

        if (! $waliKelas) {
            return redirect()->route('guru.rekam.pilih')->with('error', 'Anda tidak memiliki akses sebagai wali kelas.');
        }

        if (! $waliKelas->id_kelas) {
            return redirect()->route('guru.rekam.pilih')->with('error', 'Anda belum memiliki kelas yang ditugaskan.');
        }

        $kelas = $waliKelas->kelas;
        $siswa = Siswa::all();
        $jenisPrestasi = JenisPrestasi::all();

        return view('guru.rekam.prestasi', compact('siswa', 'jenisPrestasi', 'kelas'));
    }

    public function storePrestasi(Request $request)
    {
        try {
            Log::info('PretasiController storePrestasi started', ['user_id' => Auth::id()]);
            $jenis = JenisPrestasi::findOrFail($request->id_jenis_prestasi);
            $poin = $jenis->poin_prestasi;
            $validated = $request->validate([
                'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
                'id_jenis_prestasi' => 'required|exists:tbl_jenis_prestasi,id',
                'bukti_prestasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'nullable|string|max:500',

            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            $user = Auth::user();
            $buktiPath = null;

            // Simpan bukti jika ada
            if ($request->hasFile('bukti_prestasi')) {
                Log::info('Processing bukti_prestasi file');
                $file = $request->file('bukti_prestasi');
                $filename = 'prestasi_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('prestasi', $filename, 'public');
                Log::info('File stored', ['buktiPath' => $buktiPath]);
            }

            Log::info('About to create RekamPrestasi', [
                'id_siswa' => $request->id_siswa,
                'id_jenis_prestasi' => $request->id_jenis_prestasi,
                'id_user' => $user->id_user,
                'bukti_prestasi' => $buktiPath,
            ]);
            $siswa = Siswa::where('id_siswa', $request->id_siswa)->first();

            $skorBaru = $siswa->total_poin + $poin;

            if ($skorBaru > 100) {
                $skorBaru = 100;
            }
            $siswa->update(['total_poin' => $skorBaru]);
            Log::info('Nilai poin sebelum create', ['poin' => $poin]);
            RekamPrestasi::create([
                'id_siswa' => $request->id_siswa,
                'id_jenis_prestasi' => $request->id_jenis_prestasi,
                'tanggal_prestasi' => $request->input('tanggal_prestasi', today()),
                'bukti_prestasi' => $buktiPath,
                'keterangan' => $request->keterangan,
                'id_user' => $user->id_user,
                'pembimbing' => $request->input('pembimbing', $user->username ?? ''),
                'poin_diberikan' => $poin,
            ]);

            Log::info('RekamPrestasi created successfully');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Prestasi berhasil direkam.']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil direkam.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', ['errors' => $e->errors()]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('PretasiController storePrestasi error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'bukti_prestasi']),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: '.$e->getMessage()], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }
}
