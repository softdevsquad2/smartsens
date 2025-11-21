<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        $siswa = $query->paginate(10)->withQueryString();

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
            'id_kelas' => 'required|exists:tbl_kelas,id_kelas',
        ]);

        try {
            // 1️⃣ Simpan siswa baru
            $siswa = Siswa::create([
                'id_kelas' => $request->id_kelas,
                'nama' => trim($request->nama),
                'jenis_kelamin' => $request->jenis_kelamin,
                'nisn' => $request->nisn,
                'card_code' => $request->card_code,
                'no_hp_ortu' => $request->no_hp_ortu,
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
            'card_code' => 'nullable|numeric',
            'no_hp_ortu' => 'nullable|string|max:20',
            'id_kelas' => 'required|exists:tbl_kelas,id_kelas',
        ]);

        $siswa->update($request->all());

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
    public function import(Request $request)
    {
        // Support two flows: direct upload (file) or confirm from preview (stored filename)
        if ($request->has('stored_file')) {
            $stored = $request->input('stored_file');
            $path = storage_path('app/' . $stored);
            if (! file_exists($path)) {
                return redirect()->route('siswa.index')->with('error', 'File temp tidak ditemukan. Silakan preview ulang.');
            }
            $file = new \Illuminate\Http\UploadedFile($path, basename($path));
        } else {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls',
            ]);

            $file = $request->file('file');
        }

        // Use Maatwebsite Excel to read into array
        try {
            $rows = Excel::toArray([], $file);
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());

            return redirect()->route('siswa.index')->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if (empty($rows) || ! isset($rows[0]) || count($rows[0]) === 0) {
            return redirect()->route('siswa.index')->with('error', 'File kosong atau format tidak dikenali');
        }

        $sheet = $rows[0];

        // Normalize header row (first row) and build flexible header map
        // Maatwebsite\Excel::toArray returns native PHP arrays (sheets -> rows -> cells)
        // so treat the header row as an array rather than calling ->toArray() on it.
        $rawHeaderRow = (array) $sheet[0];
        $normalizedHeaders = [];
        foreach ($rawHeaderRow as $i => $h) {
            $key = strtolower(trim((string) $h));
            $key = preg_replace('/[^a-z0-9]+/i', '_', $key); // sanitize to snake_case-ish
            $normalizedHeaders[$key] = $i;
        }

        // Header aliases - map multiple possible header names to canonical keys
        $headerAliases = [
            'nama' => ['nama', 'full_name', 'name', 'nama_lengkap'],
            'nisn' => ['nisn', 'nis', 'id_nisn'],
            'jenis_kelamin' => ['jenis_kelamin', 'jenis kelamin', 'gender', 'sex'],
            'kelas' => ['kelas', 'class', 'nama_kelas'],
            'jurusan' => ['jurusan', 'major', 'department'],
            'card_code' => ['card_code', 'card', 'cardcode'],
            'no_hp_ortu' => ['no_hp_ortu', 'no hp ortu', 'nomor_hp_ortu', 'phone_ortu', 'hp_ortu'],
        ];

        $cols = [];
        foreach ($headerAliases as $canonical => $variants) {
            foreach ($variants as $v) {
                $vnorm = preg_replace('/[^a-z0-9]+/i', '_', strtolower($v));
                if (array_key_exists($vnorm, $normalizedHeaders)) {
                    $cols[$canonical] = $normalizedHeaders[$vnorm];
                    break;
                }
                // also allow substring match in header keys
                foreach ($normalizedHeaders as $hdr => $idx) {
                    if (str_contains($hdr, $vnorm) || str_contains($vnorm, $hdr)) {
                        $cols[$canonical] = $idx;
                        break 2;
                    }
                }
            }
        }

        // Required headers
        $required = ['nama', 'nisn', 'jenis_kelamin', 'kelas'];
        foreach ($required as $r) {
            if (! isset($cols[$r])) {
                return redirect()->route('siswa.index')->with('error', "Header '$r' tidak ditemukan di file. Pastikan file mengikuti template.");
            }
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        // Process rows (skip header)
        for ($i = 1; $i < count($sheet); $i++) {
            $row = (array) $sheet[$i];

            $nama = isset($row[$cols['nama']]) ? trim((string) $row[$cols['nama']]) : null;
            $nisn = isset($row[$cols['nisn']]) ? trim((string) $row[$cols['nisn']]) : null;
            $jenis = isset($row[$cols['jenis_kelamin']]) ? trim((string) $row[$cols['jenis_kelamin']]) : null;
            $kelasName = isset($row[$cols['kelas']]) ? trim((string) $row[$cols['kelas']]) : null;
            $jurusanName = isset($cols['jurusan']) && isset($row[$cols['jurusan']]) ? trim((string) $row[$cols['jurusan']]) : null;
            $card = isset($cols['card_code']) && isset($row[$cols['card_code']]) ? trim((string) $row[$cols['card_code']]) : null;
            $noHpOrtu = isset($cols['no_hp_ortu']) && isset($row[$cols['no_hp_ortu']]) ? trim((string) $row[$cols['no_hp_ortu']]) : null;

            // Basic validation
            // Basic required validation
            if (empty($nama) || empty($nisn) || empty($jenis) || empty($kelasName)) {
                $skipped++;
                $errors[] = 'Baris ' . ($i + 1) . ': Data wajib tidak lengkap';

                continue;
            }

            // Sanitize name (remove extra whitespace, dangerous chars)
            $nama = preg_replace('/\s+/', ' ', strip_tags($nama));
            $nama = trim($nama);

            // Validate NISN: numeric, length between 8 and 12 (adjustable)
            $nisnNumeric = preg_replace('/[^0-9]/', '', $nisn);
            if ($nisnNumeric === '' || strlen($nisnNumeric) < 8 || strlen($nisnNumeric) > 12) {
                $skipped++;
                $errors[] = 'Baris ' . ($i + 1) . ': NISN tidak valid (harus numeric dan 8-12 digit)';

                continue;
            }
            $nisn = $nisnNumeric;

            // Map jenis kelamin variations
            $jenisNorm = strtolower($jenis);
            if (in_array($jenisNorm, ['l', 'laki-laki', 'laki laki', 'male', 'm'])) {
                $jenis = 'L';
            } elseif (in_array($jenisNorm, ['p', 'perempuan', 'female', 'f'])) {
                $jenis = 'P';
            } else {
                // try first char
                $first = strtolower(substr($jenisNorm, 0, 1));
                if ($first === 'l') {
                    $jenis = 'L';
                } elseif ($first === 'p') {
                    $jenis = 'P';
                } else {
                    $skipped++;
                    $errors[] = 'Baris ' . ($i + 1) . ": Jenis kelamin tidak dikenali ($jenis)";

                    continue;
                }
            }

            // Find kelas by name (try exact, case-insensitive, then substring/fuzzy)
            $kelas = Kelas::whereRaw('LOWER(nama_kelas) = ?', [strtolower($kelasName)])->first();
            if (! $kelas) {
                $kelas = Kelas::where('nama_kelas', 'like', '%' . $kelasName . '%')->first();
            }
            // If jurusan provided, try to match using jurusan as well
            if (! $kelas && $jurusanName) {
                $jurusanNameNorm = strtolower($jurusanName);
                $kelas = Kelas::whereHas('jurusan', function ($q) use ($jurusanNameNorm) {
                    $q->whereRaw('LOWER(nama_jurusan) = ?', [$jurusanNameNorm])
                        ->orWhere('nama_jurusan', 'like', '%' . $jurusanNameNorm . '%');
                })->where('nama_kelas', 'like', '%' . $kelasName . '%')->first();
            }

            if (! $kelas) {
                $skipped++;
                $errors[] = 'Baris ' . ($i + 1) . ": Kelas '$kelasName' tidak ditemukan";

                continue;
            }

            // Check duplicate nisn
            $exists = Siswa::where('nisn', $nisn)->first();
            if ($exists) {
                $skipped++;
                $errors[] = 'Baris ' . ($i + 1) . ": NISN '$nisn' sudah ada";

                continue;
            }

            try {
                $siswa = Siswa::create([
                    'id_kelas' => $kelas->id_kelas,
                    'nama' => $nama,
                    'jenis_kelamin' => $jenis,
                    'nisn' => $nisn,
                    'card_code' => $card ?: null,
                    'no_hp_ortu' => $noHpOrtu ?: null,
                ]);

                // Create corresponding user account (username = nisn, password = nisn)
                $user = User::create([
                    'id_siswa' => $siswa->id_siswa,
                    'username' => $nama,
                    'password' => Hash::make($nisn),
                    'role' => 'siswa',
                    'card_code' => $card ?: null,
                ]);

                $created++;
            } catch (\Exception $e) {
                Log::error('Error importing row ' . ($i + 1) . ': ' . $e->getMessage());
                $skipped++;
                $errors[] = 'Baris ' . ($i + 1) . ': Terjadi kesalahan saat menyimpan - ' . $e->getMessage();

                continue;
            }
        }

        $msg = "Import selesai: $created dibuat, $skipped dilewati.";
        if (! empty($errors)) {
            session()->flash('import_errors', $errors);
        }

        return redirect()->route('siswa.index')->with('success', $msg);
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
