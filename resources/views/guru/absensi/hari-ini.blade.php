@extends('layouts.app')

@section('title', 'Absensi Hari Ini - SmartSens')
@section('page-title', 'Absensi Hari Ini')
@section('page-description', 'Absensi siswa hari ini')

@section('sidebar')
    <!-- Dashboard -->
    <a href="{{ route('guru.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
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
        class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
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
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Absensi Hari Ini</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $today->format('l, d F Y') }} - Kelas {{ $kelas->nama_kelas }}</p>
        </div>
        <a href="{{ route('guru.dashboard') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Hadir</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $siswa->filter(fn($s) => in_array(optional($s->absensi->first())->status_masuk, ['hadir', 'terlambat']))->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Izin</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $siswa->filter(fn($s) => optional($s->absensi->first())->status_masuk === 'izin')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-medkit text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Sakit</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $siswa->filter(fn($s) => optional($s->absensi->first())->status_masuk === 'sakit')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Alpha</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $siswa->filter(fn($s) => !in_array(optional($s->absensi->first())->status_masuk, ['hadir', 'terlambat', 'izin', 'sakit']))->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bukti</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($siswa as $index => $s)
                        @php
                            $absenHariIni = $s->absensi->where('tanggal', $today->format('Y-m-d'))->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $s->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $s->nisn ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $s->nisn ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($absenHariIni && $absenHariIni->status_masuk == 'hadir')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Hadir
                                    </span>
                                @elseif($absenHariIni && $absenHariIni->status_masuk == 'terlambat')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Terlambat
                                    </span>
                                @elseif($absenHariIni && $absenHariIni->status_masuk == 'izin')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Izin
                                    </span>
                                @elseif($absenHariIni && $absenHariIni->status_masuk == 'sakit')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-medkit mr-1"></i> Sakit
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Alpha
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($absenHariIni)
                                    {{ $absenHariIni->waktu_masuk ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($absenHariIni && $absenHariIni->foto_masuk)
                                    <a href="{{ asset('storage/' . $absenHariIni->foto_masuk) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat Foto</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
