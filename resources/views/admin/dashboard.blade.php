@extends('layouts.app')

@section('title', 'Beranda Admin - SmartSens')
@section('page-title', 'Beranda Admin')
@section('page-description', 'Overview sistem absensi GPS')

@section('sidebar')
    <!-- Beranda -->
    <a href="/admin/dashboard" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-tachometer-alt"></i>
        <span>Beranda</span>
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
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-app-primary">Selamat Datang, Admin!</h1>
        <p class="mt-2 text-lg text-app-primary">Kelola sistem absensi GPS dengan mudah dan efisien</p>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Siswa -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Guru</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalGuru }}</p>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Kelas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalKelas }}</p>
                </div>
            </div>
        </div>

        <!-- Siswa Hadir Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Siswa Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $siswaHadirHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Siswa Terlambat Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Siswa Terlambat Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $siswaTerlambatHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Total Jurusan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Jurusan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalJurusan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 info-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('siswa.create') }}"
                    class="flex items-center p-4 rounded-lg transition-colors quick-action">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Tambah Siswa</p>
                        <p class="text-sm text-gray-500">Tambah siswa baru</p>
                    </div>
                </a>

                <a href="{{ route('kelas.create') }}"
                    class="flex items-center p-4 rounded-lg transition-colors quick-action">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chalkboard text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Tambah Kelas</p>
                        <p class="text-sm text-gray-500">Tambah kelas baru</p>
                    </div>
                </a>

                <a href="{{ route('user.create') }}"
                    class="flex items-center p-4 rounded-lg transition-colors quick-action">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-cog text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Tambah User</p>
                        <p class="text-sm text-gray-500">Tambah user baru</p>
                    </div>
                </a>

                <a href="/admin/settings" class="flex items-center p-4 rounded-lg transition-colors quick-action">
                    <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Pengaturan</p>
                        <p class="text-sm text-gray-500">Atur sistem</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- System Status & Database Management -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">

            <!-- Status Sistem -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Sistem</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Database</span>
                        </div>
                        <span class="text-sm text-green-600 font-medium">Aktif</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">GPS Tracking</span>
                        </div>
                        <span class="text-sm text-green-600 font-medium">Aktif</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">Absensi System</span>
                        </div>
                        <span class="text-sm text-green-600 font-medium">Aktif</span>
                    </div>


                </div>
            </div>

            <!-- Manajemen Database -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Manajemen Database</h3>
                <p class="text-sm text-gray-600 mb-5">
                    Lakukan backup data sistem secara manual untuk menjaga keamanan data absensi.
                </p>

                <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                    <a href="{{ route('admin.backup') }}"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-download mr-2"></i> Backup Database
                    </a>

                </div>
            </div>

        </div>

    </div>
@endsection
