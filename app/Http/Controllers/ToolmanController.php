<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kelas;
use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
// use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ToolmanController extends Controller
{
    public function dashboard()
    {
        $token = Cache::get('kembali_token');
        $tokenCreatedAt = Cache::get('kembali_token_created_at');
        $isExpired = false;

        if ($token && $tokenCreatedAt) {
            $isExpired = Carbon::parse($tokenCreatedAt)->diffInMinutes(now()) >= 15;

            if ($isExpired) {
                Cache::forget('kembali_token');
                Cache::forget('kembali_token_created_at');
                $token = null;
            }
        }

        return view('toolman.dashboard', [
            'totalBarang' => Barang::count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'totalSiswa' => Siswa::count(),
            'totalUser' => User::where('role', 'toolman')->count(),
            'token' => $token,
            'tokenExpired' => $isExpired,
        ]);
    }

    public function generateReturnToken(Request $request)
    {
        $token = random_int(100000, 999999);

        Cache::put('kembali_token', (string) $token, now()->addMinutes(15));
        Cache::put('kembali_token_created_at', now()->toDateTimeString(), now()->addMinutes(15));

        return redirect()->route('toolman.dashboard')
            ->with('success', "Token pengembalian berhasil dibuat: {$token}. Token berlaku 15 menit dan hanya sekali pakai.");
    }

    public function barang(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $barangs = Barang::paginate($perPage);

        return view('toolman.barang', compact('barangs'));
    }

    public function peminjaman(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $peminjamans = Peminjaman::with(['siswa', 'barang'])

            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('toolman.riwayat-pinjaman', compact('peminjamans'));
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR UNDUH + FILTER (KELAS & BULAN)
    |--------------------------------------------------------------------------
    */
    public function unduh(Request $request)
    {
        $query = Peminjaman::with(['siswa.kelas', 'barang', 'user']);

        // Filter kelas
        if ($request->kelas) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        // Filter bulan (format: 2025-02)
        if ($request->bulan) {
            $query->whereMonth('tanggal_pinjam', substr($request->bulan, 5, 2))
                ->whereYear('tanggal_pinjam', substr($request->bulan, 0, 4));
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate();
        $kelas = Kelas::all();

        return view('toolman.unduh', compact('peminjamans', 'kelas'));
    }

    // =============================
    // 👉 EXPORT EXCEL
    // =============================
    public function exportExcel(Request $request)
    {
        return Excel::download(new \App\Exports\RiwayatExport($request), 'riwayat_peminjaman.xlsx');
    }

    // =============================
    // 👉 EXPORT PDF
    // =============================
    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['siswa.kelas', 'barang', 'user']);

        if ($request->kelas) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas);
            });
        }

        if ($request->bulan) {
            $query->whereMonth('tanggal_pinjam', substr($request->bulan, 5, 2))
                ->whereYear('tanggal_pinjam', substr($request->bulan, 0, 4));
        }

        $peminjamans = $query->get();

        $pdf = PDF::loadView('toolman.export_php', [
            'peminjamans' => $peminjamans,
        ])->setPaper('A4', 'portrait'); // opsional

        return $pdf->download('riwayat_peminjaman.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD BARANG (TIDAK DIUBAH)
    |--------------------------------------------------------------------------
    */
    public function pengembalian()
    {
        $peminjamans = Peminjaman::with(['siswa', 'barang'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('toolman.pengembalian', compact('peminjamans'));
    }

    public function storeBarang(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string',
            'satuan' => 'required|string',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image',
            'jenis' => 'required|string',
            'kode_barang' => 'required|string|unique:tbl_barang,kode_barang',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'jenis' => $request->jenis,
            'kode_barang' => $request->kode_barang,
            'gambar' => $request->hasFile('gambar') ? $request->file('gambar')->store('barangs', 'public') : null,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::find($id);

        if (! $barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (! $barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $validated = $request->validate([
            'nama_barang' => 'required',
            'kode_barang' => 'required',
            'satuan' => 'required',
            'stok' => 'required|integer',
            'jenis' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);
        // dd([
        //     'mime' => $request->file('gambar')->getMimeType(),
        //     'size' => $request->file('gambar')->getSize(),
        //     'extension' => $request->file('gambar')->getClientOriginalExtension(),
        // ]);

        // Redirect manual jika gambar lebih dari 2MB
        if ($request->hasFile('gambar')) {
            if ($request->file('gambar')->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Gambar maksimal 2MB!');
            }
        }

        $barang->update($request->only('nama_barang', 'kode_barang', 'satuan', 'stok', 'jenis'));

        if ($request->hasFile('gambar')) {

            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $barang->gambar = $request->file('gambar')->store('barangs', 'public');
            $barang->save();
        }

        return redirect()->back()->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (! $barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}
