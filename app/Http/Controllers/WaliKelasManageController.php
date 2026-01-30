<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WaliKelasTemplateExport;

class WaliKelasManageController extends Controller
{
    public function index(Request $request)
    {
        $perpage = $request->get('per_page', 10);
        $waliKelas = WaliKelas::with(['kelas.jurusan', 'user'])->paginate($perpage);

        return view('admin.walikelas.index', compact('waliKelas', 'perpage'));
    }

    public function create()
    {
        $kelas = Kelas::with('jurusan')->get();
        $users = User::where('role', 'guru')->get();

        return view('admin.walikelas.create', compact('kelas', 'users'));
    }

    public function store(Request $request)
    {
        // Debug: Log request data
        \Log::info('Guru form submission data:', $request->all());

        $request->validate([
            'nama' => 'required|string|max:255',
            // Kelas sekarang opsional untuk Guru
            'id_kelas' => 'nullable|exists:tbl_kelas,id_kelas|unique:tbl_wali_kelas,id_kelas',
            'id_user' => 'nullable|exists:tbl_user,id_user',
        ]);

        try {
            $waliKelas = WaliKelas::create($request->all());
            \Log::info('Guru berhasil dibuat dengan ID: ' . $waliKelas->id_wali_kelas);

            return redirect()->route('walikelas.index')->with('success', 'Guru berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Error creating guru: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(WaliKelas $walikelas)
    {
        $walikelas->load(['kelas.jurusan', 'user']);

        return view('admin.walikelas.show', compact('walikelas'));
    }

    public function edit(WaliKelas $walikelas)
    {
        $kelas = Kelas::with('jurusan')->get();
        $users = User::where('role', 'guru')->get();

        return view('admin.walikelas.edit', compact('walikelas', 'kelas', 'users'));
    }

    public function update(Request $request, WaliKelas $walikelas)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // Kelas opsional untuk guru
            'id_kelas' => 'nullable|exists:tbl_kelas,id_kelas|unique:tbl_wali_kelas,id_kelas,' . $walikelas->id_wali_kelas . ',id_wali_kelas',
            'id_user' => 'nullable|exists:tbl_user,id_user',
        ]);

        try {
            $walikelas->update($request->all());
            \Log::info('Guru berhasil diupdate dengan ID: ' . $walikelas->id_wali_kelas);

            return redirect()->route('walikelas.index')->with('success', 'Guru berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating guru: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(WaliKelas $walikelas)
    {
        try {
            $walikelas->delete();
            \Log::info('Guru berhasil dihapus dengan ID: ' . $walikelas->id_wali_kelas);

            return redirect()->route('walikelas.index')->with('success', 'Guru berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error deleting guru: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Import guru from uploaded Excel (.xlsx).
     * Expected columns: nama, nip, kelas (optional)
     */
    public function import(Request $request)
    {
        // Timeout 10 menit untuk handle 3000+ rows
        set_time_limit(600);

        // Disable query log untuk performa
        DB::disableQueryLog();

        \App\Imports\WaliKelasImport::$inserted = 0;
        \App\Imports\WaliKelasImport::$updated = 0;
        \App\Imports\WaliKelasImport::$errors = [];

        Excel::import(new \App\Imports\WaliKelasImport, $request->file('file'));

        $insert = \App\Imports\WaliKelasImport::$inserted;
        $update = \App\Imports\WaliKelasImport::$updated;
        $errors = \App\Imports\WaliKelasImport::$errors;

        // Jika ada error, tampilkan ke user
        if (!empty($errors)) {
            return back()->with([
                'success' => "Import selesai dengan catatan!\n\nDitambahkan: $insert guru\nDiperbarui: $update guru",
                'import_errors' => $errors
            ]);
        }

        return back()->with('success', "Import selesai!\nDitambahkan: $insert guru\nDiperbarui: $update guru");
    }

    /**
     * Download XLSX template for guru import
     */
    public function downloadTemplate()
    {
        return Excel::download(new WaliKelasTemplateExport, 'guru_import_template.xlsx');
    }
}
