@extends('layouts.app')

@section('title', 'Pilih Jenis Rekam - SmartSens')
@section('page-title', 'Pilih Jenis Rekam')
@section('page-description', 'Pilih jenis pencatatan yang ingin dilakukan')

@section('sidebar')
    @if(Auth::user()->waliKelas && !Auth::user()->waliKelas->id_kelas)
        <!-- Logout -->
        <a href="{{ route('guru.rekam.pilih') }}"
            class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors mt-auto">
            <i class="fas fa-clipboard-list"></i>

            <span>Rekam Siswa</span>
        </a>
    @else
        <!-- Dashboard -->
        <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
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


    @endif
@endsection

@section('content')
    <div class="mb-8 flex items-center justify-between">
       <div>
         <h1 class="text-2xl font-bold text-gray-900">Pilih Jenis Pencatatan</h1>
        <p class="mt-1 text-sm text-gray-600">Silakan pilih jenis pencatatan yang ingin Anda lakukan</p>
       </div>
        <div class="mt-8">
        {{-- <a href="{{ route('guru.dashboard') }}"
            class="inline-block px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a> --}}
    </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Rekam Pelanggaran -->
        <a href="{{ route('guru.pelanggaran.form') }}"
            class="block group bg-white shadow-md rounded-xl border border-gray-200 p-8 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-exclamation-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Rekam Pelanggaran</h3>
                <p class="text-gray-600 text-sm mb-4">Catat pelanggaran siswa dengan foto bukti pelanggaran</p>
                <div class="mt-4 pt-4 border-t border-gray-200 w-full">
                    <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-camera mr-2"></i>Dengan Foto
                    </span>
                </div>
            </div>
        </a>

        <!-- Rekam Prestasi -->
        <a href="{{ route('guru.prestasi.form') }}"
            class="block group bg-white shadow-md rounded-xl border border-gray-200 p-8 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-award text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Rekam Prestasi</h3>
                <p class="text-gray-600 text-sm mb-4">Catat prestasi siswa dengan point sesuai jenis prestasi</p>
                <div class="mt-4 pt-4 border-t border-gray-200 w-full">
                    <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-2"></i>Dengan Point
                    </span>
                </div>
            </div>
        </a>
    </div>

    <!-- Tombol Kembali -->

@endsection
@push('scripts')
    <script>
        // Tambahkan skrip JavaScript jika diperlukan
    </script>
@endpush
