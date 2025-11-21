@extends('layouts.app')

@section('title', 'Riwayat Sholat Hari Ini - SmartSens')
@section('page-title', 'Riwayat Sholat Hari Ini')
@section('page-description', 'Lihat catatan sholat Anda hari ini')

@section('sidebar')
    <a href="/siswa/dashboard"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Beranda</span>
    </a>

    <!-- Absensi -->
    <a href="/siswa/absen"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-fingerprint"></i>
        <span>Absensi</span>
    </a>

    <!-- Riwayat Absensi -->
    <a href="/siswa/riwayat-absensi"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-history"></i>
        <span>Riwayat Absensi</span>
    </a>
    <a href="/siswa/riwayat-sholat" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-pray"></i>
        <span>Riwayat Sholat</span>
    </a>
    <a href="/siswa/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-app-primary">Riwayat Sholat Hari Ini</h1>
        <p class="mt-2 text-lg text-app-primary">
            {{ \Carbon\Carbon::parse($tanggalHariIni)->translatedFormat('l, d F Y') }}
        </p>
    </div>

    @if ($riwayatHariIni)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Sholat Dzuhur -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center text-white text-xl">
                        <i class="fas fa-sun"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sholat Dzuhur</h3>
                        <p class="text-sm text-gray-500">Waktu: {{ $riwayatHariIni->dzuhur_masuk ?? '-' }}</p>
                    </div>
                </div>
                @if ($riwayatHariIni->status_dzuhur === 'sholat')
                    <div class="flex items-center text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Sudah Sholat
                    </div>
                @else
                    <div class="flex items-center text-red-600 font-semibold">
                        <i class="fas fa-times-circle mr-2"></i> Belum Sholat
                    </div>
                @endif
            </div>

            <!-- Sholat Ashar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white text-xl">
                        <i class="fas fa-cloud-sun"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Sholat Ashar</h3>
                        <p class="text-sm text-gray-500">Waktu: {{ $riwayatHariIni->ashar_masuk ?? '-' }}</p>
                    </div>
                </div>
                @if ($riwayatHariIni->status_ashar === 'sholat')
                    <div class="flex items-center text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Sudah Sholat
                    </div>
                @else
                    <div class="flex items-center text-red-600 font-semibold">
                        <i class="fas fa-times-circle mr-2"></i> Belum Sholat
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-pray text-gray-400 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Sholat Hari Ini</h3>
            <p class="text-gray-500">Data sholat hari ini belum tercatat.</p>
        </div>
    @endif
@endsection
