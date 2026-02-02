<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;

class SiswaManageController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas.jurusan']);

        // 🔍 Search Nama / NISN / Card Code
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qr) use ($q) {
                $qr->where('nama', 'like', "%{$q}%")
                    ->orWhere('nisn', 'like', "%{$q}%")
                    ->orWhere('card_code', 'like', "%{$q}%");
            });
        }

        // ✅ Filter Kelas
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        // ✅ Filter Jurusan
        if ($request->filled('jurusan')) {
            $query->whereHas('kelas.jurusan', function ($qr) use ($request) {
                $qr->where('id_jurusan', $request->jurusan);
            });
        }

        // ✅ Pagination
        $perpage = $request->get('per_page', 10);
        $siswa = $query->paginate($perpage)->withQueryString();

        // ✅ Data dropdown
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();

        return view('admin.siswa.index', compact('siswa', 'kelas', 'jurusan'));
    }

    public function create()
    {
        $kelas = Kelas::with('jurusan')->get();

        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        // Debug: Log request data
        Log::info('Form submission data:', $request->all());

        if (! $request->isMethod('post')) {
            Log::error('Request method is not POST: ' . $request->method());

            return redirect()->back()->with('error', 'Method tidak valid')->withInput();
        }

        if (! $request->has('_token')) {
            Log::error('CSRF token missing');

            return redirect()->back()->with('error', 'CSRF token tidak ditemukan')->withInput();
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nisn' => 'required|numeric|unique:tbl_siswa,nisn',
            'card_code' => 'nullable|numeric|unique:tbl_siswa,card_code',
            'no_hp_ortu' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_kelas' => 'required|exists:tbl_kelas,id_kelas',
        ]);

        try {
            // 1️⃣ Simpan siswa baru
            // Handle foto upload if provided
            $fotoName = null;
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('foto', 'public');
                $fotoName = basename($path);
            }

            $siswa = Siswa::create([
                'id_kelas' => $request->id_kelas,
                'nama' => trim($request->nama),
                'jenis_kelamin' => $request->jenis_kelamin,
                'nisn' => $request->nisn,
                'card_code' => $request->card_code,
                'no_hp_ortu' => $request->no_hp_ortu,
                'foto' => $fotoName,
            ]);

            Log::info('Siswa berhasil dibuat dengan ID: ' . $siswa->id_siswa);

            // 2️⃣ Buat akun user otomatis
            User::create([
                'id_siswa' => $siswa->id_siswa,
                'username' => trim($request->nama), // username = nama
                'password' => Hash::make($request->nisn), // password = NISN (dihash)
                'role' => 'siswa',
                'card_code' => $request->card_code,
            ]);

            Log::info('Akun user berhasil dibuat untuk siswa : ' . $siswa->nama);

            return redirect()->route('siswa.index')->with('success', 'Siswa dan akun user berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating siswa: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::with('jurusan')->get();

        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        // Debug: Log request data
        Log::info('Update form submission data:', $request->all());

        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nisn' => 'required|numeric|unique:tbl_siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'card_code' => 'nullable|numeric|unique:tbl_siswa,card_code,' . $siswa->id_siswa . ',id_siswa',
            'no_hp_ortu' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'id_kelas' => 'required|exists:tbl_kelas,id_kelas',
        ]);

        // Handle foto upload update
        $data = $request->only(['id_kelas', 'nama', 'jenis_kelamin', 'nisn', 'card_code', 'no_hp_ortu']);
        if ($request->hasFile('foto')) {
            // store new foto
            $path = $request->file('foto')->store('foto', 'public');
            $fotoName = basename($path);

            // delete old foto if exists
            if ($siswa->foto) {
                try {
                    Storage::disk('public')->delete('foto/' . $siswa->foto);
                } catch (\Exception $e) {
                    Log::warning('Gagal menghapus foto lama: ' . $e->getMessage());
                }
            }

            $data['foto'] = $fotoName;
        }

        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }

    /**
     * Import siswa from uploaded Excel (.xlsx).
     * Expected columns: nama, nisn, jenis_kelamin, kelas, jurusan, card_code (optional)
     */
    // use Maatwebsite\Excel\Facades\Excel;

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        // Timeout 10 menit untuk handle 3000+ rows
        set_time_limit(600);

        // Disable query log untuk performa
        DB::disableQueryLog();

        \App\Imports\SiswaImport::$inserted = 0;
        \App\Imports\SiswaImport::$updated = 0;
        \App\Imports\SiswaImport::$errors = [];

        Excel::import(new \App\Imports\SiswaImport, $request->file('file'));

        $insert = \App\Imports\SiswaImport::$inserted;
        $update = \App\Imports\SiswaImport::$updated;
        $errors = \App\Imports\SiswaImport::$errors;

        // Jika ada error, tampilkan ke user
        if (!empty($errors)) {
            return back()->with([
                'success' => "Import selesai dengan catatan!\n\nDitambahkan: $insert siswa\nDiperbarui: $update siswa",
                'import_errors' => $errors
            ]);
        }

        return back()->with('success', "
        Import selesai!
        Ditambahkan: $insert siswa
        Diperbarui: $update siswa
    ");
    }






    /**
     * Preview import: parse file and show first N rows with validation result.
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        // store temporarily
        $storedPath = $file->store('imports');

        try {
            $rows = Excel::toArray([], storage_path('app/' . $storedPath));
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', 'Gagal membaca file untuk preview: ' . $e->getMessage());
        }

        if (empty($rows) || ! isset($rows[0])) {
            return redirect()->route('siswa.index')->with('error', 'File kosong atau format tidak dikenali');
        }

        $sheet = $rows[0];
        // Use the same header normalization as import
        // The sheet rows are arrays, so cast to array instead of calling ->toArray().
        $rawHeaderRow = (array) $sheet[0];
        $normalizedHeaders = [];
        foreach ($rawHeaderRow as $i => $h) {
            $key = strtolower(trim((string) $h));
            $key = preg_replace('/[^a-z0-9]+/i', '_', $key);
            $normalizedHeaders[$key] = $i;
        }

        // Build preview rows (up to 50)
        $preview = [];
        $max = min(50, count($sheet) - 1);
        for ($i = 1; $i <= $max; $i++) {
            $row = (array) $sheet[$i];
            $preview[] = $row;
        }

        return view('admin.siswa.preview', [
            'preview' => $preview,
            'stored_file' => $storedPath,
            'headers' => $rawHeaderRow,
        ]);
    }

    /**
     * Download XLSX template for siswa import
     */
    public function downloadTemplate()
    {
        return ExcelFacade::download(new SiswaTemplateExport, 'siswa_import_template.xlsx');
    }
}
