@extends('layouts.app')

@section('title', 'Kelola User - SmartSens')
@section('page-title', 'Kelola User')
@section('page-description', 'Manajemen data user')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola User</h1>
            <p class="mt-1 text-sm text-gray-600">Manajemen data user dalam sistem</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.user.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Tambah User
            </a>
        </div>
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

    <!-- Users Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <form method="GET" class="p-4 flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <label for="search" class="text-sm text-gray-700 mr-2">Cari:</label>
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                        placeholder="Cari username atau role..."
                        class="border border-gray-300 px-3 py-1 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-center">
                    <label for="per_page" class="text-sm text-gray-700 mr-2">Tampilkan</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="border border-gray-300 px-2 py-1 rounded">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data per halaman</span>
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-1 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-1"></i>Cari
                </button>
                @if ($search ?? '')
                    <a href="{{ route('admin.user.index') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                @endif
            </form>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas/Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Card Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->username }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($user->role == 'admin') bg-red-100 text-red-800
                                @elseif($user->role == 'guru') bg-blue-100 text-blue-800
                                @elseif($user->role == 'operator') bg-indigo-100 text-indigo-800
                                @elseif($user->role == 'siswa') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                    <i
                                        class="fas
                                    @if ($user->role == 'admin') fa-crown
                                    @elseif($user->role == 'guru') fa-chalkboard-teacher
                                    @elseif($user->role == 'operator') fa-user-cog
                                    @elseif($user->role == 'siswa') fa-user-graduate
                                    @else fa-user @endif mr-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($user->role == 'siswa' && $user->siswa)
                                    <div class="flex items-center">
                                        <i class="fas fa-user-graduate text-green-500 mr-2"></i>
                                        {{ $user->siswa->nama }}
                                    </div>
                                @elseif($user->role == 'guru' && $user->waliKelas)
                                    <div class="flex items-center">
                                        <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i>
                                        {{ $user->waliKelas->nama }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($user->role == 'siswa' && $user->siswa && $user->siswa->kelas)
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium">{{ $user->siswa->kelas->nama_kelas }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $user->siswa->kelas->jurusan->nama_jurusan ?? '' }}</span>
                                    </div>
                                @elseif($user->role == 'guru' && $user->waliKelas && $user->waliKelas->kelas)
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium">{{ $user->waliKelas->kelas->nama_kelas }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $user->waliKelas->kelas->jurusan->nama_jurusan ?? '' }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($user->card_code)
                                    <span
                                        class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $user->card_code }}</span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.user.edit', $user->id_user) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.user.destroy', $user->id_user) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirmDelete('Yakin ingin menghapus user ini?', 'Konfirmasi Hapus User')">
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
                                    <i class="fas fa-user-cog text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data user</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan user pertama</p>
                                    <a href="{{ route('admin.user.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah User
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($users->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span
                                    class="font-medium">{{ $users->lastItem() }}</span> of <span
                                    class="font-medium">{{ $users->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
