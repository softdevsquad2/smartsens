<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasManageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perpage = $request->get('per_page', 10);

        $kelas = Kelas::with('jurusan')
            ->when($search, function ($query) use ($search) {
                return $query->where('nama_kelas', 'like', '%' . $search . '%')
                    ->orWhereHas('jurusan', function ($q) use ($search) {
                        $q->where('nama_jurusan', 'like', '%' . $search . '%');
                    });
            })
            ->paginate($perpage);

        return view('admin.kelas.index', compact('kelas', 'search', 'perpage'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();

        return view('admin.kelas.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'id_jurusan' => 'required|exists:tbl_jurusan,id_jurusan',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kelas)
    {
        $jurusan = Jurusan::all();

        return view('admin.kelas.edit', compact('kelas', 'jurusan'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'id_jurusan' => 'required|exists:tbl_jurusan,id_jurusan',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}
