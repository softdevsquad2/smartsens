@extends('layouts.app')

@section('title', 'Kelola Siswa - SmartSens')
@section('page-title', 'Kelola Siswa')
@section('page-description', 'Manajemen data siswa')

@section('sidebar')
    <!-- Dashboard -->
    <a href="/admin/dashboard"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <!-- Absensi -->
    <a href="/admin/absensi"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-check"></i>
        <span>Absensi</span>
    </a>

    <!-- Kelola Siswa -->
    <a href="/admin/siswa" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-users"></i>
        <span>Kelola Siswa</span>
    </a>

    <!-- Kelola Kelas -->
    <a href="/admin/kelas"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chalkboard"></i>
        <span>Kelola Kelas</span>
    </a>

    <!-- Kelola Jurusan -->
    <a href="/admin/jurusan"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-graduation-cap"></i>
        <span>Kelola Jurusan</span>
    </a>

    <!-- Kelola Wali Kelas -->
    <a href="/admin/walikelas"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-user-tie"></i>
        <span>Kelola Wali Kelas</span>
    </a>

    <!-- Kelola User -->
    <a href="/admin/user"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-user-cog"></i>
        <span>Kelola User</span>
    </a>

    <!-- Pengaturan -->
    <a href="/admin/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection


@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">

        <!-- 🔍 Search & Filter -->
        <form method="GET" action="{{ route('siswa.index') }}" class="w-full sm:w-auto mb-6 sm:mb-0">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-6">

                <!-- Search -->
                <input type="text" name="q" placeholder="Cari nama / NISN..." value="{{ request('q') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <!-- Filter Kelas -->
                <select name="kelas"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                <!-- Filter Jurusan -->
                <select name="jurusan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Semua Jurusan</option>
                    @foreach ($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>

                <!-- Tombol Filter -->
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-2 py-2 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('siswa.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Tambah
                </a>
                <button command="show-modal" commandfor="dialog"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm font-semibold rounded-lg transition-all duration-150 text-gray-700 flex items-center">
                    <i class="fas fa-file-import mr-2 text-gray-500"></i> Import
                </button>
            </div>

            @if (request('q') || request('kelas') || request('jurusan'))
                <a href="{{ route('siswa.index') }}"
                    class="inline-block mt-2 text-sm text-red-500 underline hover:text-red-700 transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <!-- ➕ Action Buttons -->

        <!-- Tambah Siswa -->


        <!-- Import Data -->



    </div>



    {{-- ✅ ALERT SUCCESS --}}
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <i class="fas fa-check-circle text-green-400"></i>
                <p class="ml-3 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif


    {{-- ✅ ALERT ERROR IMPORT --}}
    @if (session('import_errors'))
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="text-sm font-medium text-yellow-800">Beberapa baris gagal diimpor:</div>
            <ul class="mt-2 list-disc list-inside text-sm text-yellow-700">
                @foreach (session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    {{-- ✅ TABLE --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">
            <form method="GET" class="p-4">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                <input type="hidden" name="jurusan" value="{{ request('jurusan') }}">

                <label for="per_page" class="text-sm text-gray-700 mr-2">Tampilkan</label>

                <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="border border-gray-300 px-2 py-1 rounded">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>

                <span class="text-sm text-gray-700 ml-2">data per halaman</span>
            </form>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kelamin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse ($siswa as $index => $s)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $siswa->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>
                                    {{ strtoupper($s->nama) }}

                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 font-mono">
                                {{ $s->nisn }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <span class="px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                    {{ $s->kelas->nama_kelas ?? 'N/A' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <span class="px-2.5 py-0.5 rounded-full text-xs bg-purple-100 text-purple-800">
                                    {{ $s->kelas->jurusan->nama_jurusan ?? 'N/A' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $s->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    <i class="fas {{ $s->jenis_kelamin == 'L' ? 'fa-male' : 'fa-female' }} mr-1"></i>
                                    {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('siswa.edit', $s->id_siswa) }}"
                                        class="px-3 py-1.5 text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>

                                    <form action="{{ route('siswa.destroy', $s->id_siswa) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data siswa</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan siswa pertama</p>
                                    <a href="{{ route('siswa.create') }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                                        <i class="fas fa-plus mr-1"></i> Tambah Siswa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>


        <!-- Pagination -->
        @if ($siswa->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">

                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $siswa->firstItem() }}</span> -
                        <span class="font-medium">{{ $siswa->lastItem() }}</span>
                        dari
                        <span class="font-medium">{{ $siswa->total() }}</span>
                        siswa
                    </p>

                    {{ $siswa->links() }}
                </div>
            </div>
        @endif
    </div>


    {{-- ✅ Modal Import --}}
    <el-dialog>
        <dialog id="dialog" class="fixed inset-0 overflow-y-auto bg-transparent hidden">
            <el-dialog-backdrop class="fixed inset-0 bg-white/70"></el-dialog-backdrop>

            <div class="min-h-full flex items-center justify-center p-4">
                <el-dialog-panel class="relative bg-white rounded-lg shadow-xl max-w-lg w-full transition">
                    <div class="p-6">
                        <h3 id="dialog-title" class="text-base font-semibold text-gray-900 mb-4">
                            Import Siswa
                        </h3>

                        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="file" name="file" accept=".xlsx" class="form-input border w-full"
                                required>

                            <div class="mt-2 text-sm text-gray-500">
                                <a href="{{ route('siswa.template') }}" class="text-blue-500 underline">
                                    Download template Excel (.xlsx)
                                </a>
                            </div>

                            <p class="mt-2 text-xs text-gray-400">
                                Pastikan format sesuai template
                            </p>

                            <div class="mt-5 flex justify-end space-x-2">
                                <button command="close" commandfor="dialog"
                                    class="px-4 py-2 bg-white border rounded-md text-sm">
                                    Cancel
                                </button>

                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>


    <script>
        // Ambil modal
        const dialog = document.getElementById('dialog');

        // Tombol "Import Data" untuk buka modal
        document.querySelectorAll('[command="show-modal"]').forEach(btn => {
            btn.addEventListener('click', () => {
                dialog.classList.remove('hidden'); // tampilkan modal
            });
        });

        // Tombol "Cancel" untuk tutup modal
        document.querySelectorAll('[command="close"]').forEach(btn => {
            btn.addEventListener('click', () => {
                dialog.classList.add('hidden'); // sembunyikan modal
            });
        });
    </script>
@endsection
