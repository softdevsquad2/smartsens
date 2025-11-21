<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

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
        \Log::info('WaliKelas form submission data:', $request->all());

        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kelas' => 'required|exists:tbl_kelas,id_kelas|unique:tbl_wali_kelas,id_kelas',
            'id_user' => 'nullable|exists:tbl_user,id_user',
        ]);

        try {
            $waliKelas = WaliKelas::create($request->all());
            \Log::info('WaliKelas berhasil dibuat dengan ID: ' . $waliKelas->id_wali_kelas);

            return redirect()->route('walikelas.index')->with('success', 'Wali kelas berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Error creating wali kelas: ' . $e->getMessage());

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
            'id_kelas' => 'required|exists:tbl_kelas,id_kelas|unique:tbl_wali_kelas,id_kelas,' . $walikelas->id_wali_kelas . ',id_wali_kelas',
            'id_user' => 'nullable|exists:tbl_user,id_user',
        ]);

        try {
            $walikelas->update($request->all());
            \Log::info('WaliKelas berhasil diupdate dengan ID: ' . $walikelas->id_wali_kelas);

            return redirect()->route('walikelas.index')->with('success', 'Wali kelas berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating wali kelas: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(WaliKelas $walikelas)
    {
        try {
            $walikelas->delete();
            \Log::info('WaliKelas berhasil dihapus dengan ID: ' . $walikelas->id_wali_kelas);

            return redirect()->route('walikelas.index')->with('success', 'Wali kelas berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error deleting wali kelas: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
