@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas - SmartSens')
@section('page-title', 'Dashboard Wali Kelas')
@section('page-description', 'Dashboard untuk wali kelas')

@section('sidebar')
    <!-- Dashboard -->
    <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <!-- Daftar Siswa -->
    <a href="{{ route('guru.siswa.index') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-users"></i>
        <span>Daftar Siswa</span>
    </a>

    <!-- Absensi Hari Ini -->
    <a href="{{ route('guru.absensi.hari-ini') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-day"></i>
        <span>Absensi Hari Ini</span>
    </a>

    <!-- Laporan Absensi -->
    <a href="{{ route('guru.absensi.laporan') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chart-bar"></i>
        <span>Laporan Absensi</span>
    </a>

    <!-- Logout -->
    <a href="{{ route('logout') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors mt-auto">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
@endsection

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ $waliKelas->nama }}</h1>
        <p class="mt-1 text-sm text-gray-600">Wali Kelas {{ $kelas->nama_kelas }} -
            {{ $kelas->jurusan->nama_jurusan ?? '' }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Siswa -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>

        <!-- Kehadiran Hari Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $hadirHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Izin Hari Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Izin Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $izinHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Alpha Hari Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Alpha Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $alphaHariIni }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Absensi Bulan Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Absensi Bulan Ini</h3>
                <p class="mt-1 text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Hadir</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $hadirBulanIni }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Izin</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $izinBulanIni }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-medkit text-blue-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Sakit</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $sakitBulanIni }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Alpha</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $alphaBulanIni }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Persentase Kehadiran -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Persentase Kehadiran</h3>
                <p class="mt-1 text-sm text-gray-500">Bulan {{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#E5E7EB" stroke-width="2" />
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#10B981" stroke-width="2"
                                stroke-dasharray="{{ $persentaseKehadiran }}, 100" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $persentaseKehadiran }}%</span>
                        </div>
                    </div>
                </div>
                <p class="text-center text-sm text-gray-600 mt-4">Persentase kehadiran siswa</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('guru.siswa.index') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Daftar Siswa</h4>
                        <p class="text-xs text-gray-600">Lihat semua siswa di kelas</p>
                    </div>
                </a>

                <a href="{{ route('guru.absensi.hari-ini') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-200">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-day text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Absensi Hari Ini</h4>
                        <p class="text-xs text-gray-600">Lihat absensi hari ini</p>
                    </div>
                </a>

                <a href="{{ route('guru.absensi.laporan') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-200">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Laporan Absensi</h4>
                        <p class="text-xs text-gray-600">Lihat laporan absensi</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
