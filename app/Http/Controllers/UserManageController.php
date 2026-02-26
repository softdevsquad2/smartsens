<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perpage = $request->get('per_page', 10);

        $users = User::with(['siswa.kelas.jurusan', 'waliKelas.kelas.jurusan'])
            ->when($search, function ($query) use ($search) {
                return $query->where('username', 'like', '%'.$search.'%')
                    ->orWhere('role', 'like', '%'.$search.'%');
            })
            ->paginate($perpage);

        return view('admin.user.index', compact('users', 'search', 'perpage'));
    }

    public function create()
    {
        $siswa = Siswa::with('kelas.jurusan')->get();
        $waliKelas = WaliKelas::with('kelas.jurusan')->get();
        $roles = ['admin', 'guru', 'operator', 'siswa', 'ketua', 'piket', 'uks', 'kesiswaan'];

        return view('admin.user.create', compact('siswa', 'waliKelas', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tbl_user,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru,operator,siswa,ketua',
            'id_siswa' => 'nullable|exists:tbl_siswa,id_siswa',
            'id_wali_kelas' => 'nullable|exists:tbl_wali_kelas,id_wali_kelas',
            'card_code' => 'nullable|numeric|unique:tbl_user,card_code',
        ]);

        $userData = $request->only(['username', 'role', 'id_siswa', 'id_wali_kelas', 'card_code']);
        $userData['password'] = Hash::make($request->password);

        User::create($userData);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $siswa = Siswa::with('kelas.jurusan')->get();
        $waliKelas = WaliKelas::with('kelas.jurusan')->get();
        $roles = ['admin', 'guru', 'operator', 'siswa', 'ketua'];

        return view('admin.user.edit', compact('user', 'siswa', 'waliKelas', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tbl_user,username,'.$user->id_user.',id_user',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,guru,operator,siswa,ketua',
            'id_siswa' => 'nullable|exists:tbl_siswa,id_siswa',
            'id_wali_kelas' => 'nullable|exists:tbl_wali_kelas,id_wali_kelas',
            'card_code' => 'nullable|numeric|unique:tbl_user,card_code,'.$user->id_user.',id_user',
        ]);

        $userData = $request->only(['username', 'role', 'id_siswa', 'id_wali_kelas', 'card_code']);

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }
}
