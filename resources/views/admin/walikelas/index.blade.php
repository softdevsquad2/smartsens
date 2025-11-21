@extends('layouts.app')

@section('title', 'Kelola Wali Kelas - SmartSens')
@section('page-title', 'Kelola Wali Kelas')
@section('page-description', 'Kelola data wali kelas')

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
    <a href="/admin/siswa"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-users"></i>
        <span>Kelola Siswa</span>
    </a>

    <!-- Kelola Kelas -->
    <a href="/admin/kelas"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chalkboard"></i>
        <span>Kelola Kelas</span>
    </a>

    <!-- Kelola Wali Kelas -->
    <a href="/admin/walikelas" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-user-tie"></i>
        <span>Kelola Wali Kelas</span>
    </a>

    <!-- Kelola Jurusan -->
    <a href="/admin/jurusan"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-graduation-cap"></i>
        <span>Kelola Jurusan</span>
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
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Wali Kelas</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola data wali kelas di sekolah</p>
        </div>
        <a href="{{ route('walikelas.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>
            Tambah Wali Kelas
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Wali Kelas Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <form method="GET" class="p-4">
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
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Wali
                            Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($waliKelas as $index => $wk)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $waliKelas->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $wk->nama }}</div>
                                        @if ($wk->user)
                                            <div class="text-xs text-gray-500">User: {{ $wk->user->username }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $wk->nip ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-6 h-6 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-chalkboard text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $wk->kelas->nama_kelas }}</div>
                                        <div class="text-xs text-gray-500">{{ $wk->kelas->jurusan->nama_jurusan ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($wk->user)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Belum ada user
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('walikelas.show', $wk) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                    <a href="{{ route('walikelas.edit', $wk) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('walikelas.destroy', $wk) }}" method="POST" class="inline"
                                        onsubmit="return confirmDelete('Yakin ingin menghapus wali kelas ini?', 'Konfirmasi Hapus Wali Kelas')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-user-tie text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada wali kelas</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan wali kelas pertama</p>
                                    <a href="{{ route('walikelas.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Wali Kelas
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($waliKelas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $waliKelas->links() }}
            </div>
        @endif
    </div>
@endsection
