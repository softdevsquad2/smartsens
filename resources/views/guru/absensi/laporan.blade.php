@extends('layouts.app')

@section('title', 'Laporan Absensi - SmartSens')
@section('page-title', 'Laporan Absensi')
@section('page-description', 'Laporan absensi siswa per bulan')

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
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-day"></i>
        <span>Absensi Hari Ini</span>
    </a>

    <!-- Laporan Absensi -->
    <a href="{{ route('guru.absensi.laporan') }}"
        class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
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
            <h1 class="text-2xl font-bold text-gray-900">Laporan Absensi</h1>
            <p class="mt-1 text-sm text-gray-600">Kelas {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan ?? '' }}
            </p>
        </div>
        <a href="{{ route('guru.dashboard') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('guru.absensi.laporan') }}" class="flex items-end space-x-4">
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Pilih Bulan</label>
                <input type="month" name="bulan" id="bulan" value="{{ $bulan }}"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-search mr-2"></i>
                    Tampilkan
                </button>
            </div>
            <div>
                <a href="{{ route('guru.absensi.laporan.export', ['bulan' => $bulan]) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-file-excel mr-2 text-green-600"></i>
                    Download XLSX
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
            $totalHadir = 0;
            $totalTerlambat = 0;
            $totalIzin = 0;
            $totalSakit = 0;
            $totalAlpha = 0;
            foreach ($absensi as $tanggal => $data) {
                // absensi table uses status_masuk/status_pulang
                $totalHadir += $data->where('status_masuk', 'hadir')->count();
                $totalTerlambat += $data->where('status_masuk', 'terlambat')->count();
                $totalIzin += $data->where('status_masuk', 'izin')->count();
                $totalSakit += $data->where('status_masuk', 'sakit')->count();
                $totalAlpha += $data->where('status_masuk', 'alpha')->count();
            }
        @endphp

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Hadir</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalHadir + $totalTerlambat }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Izin</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalIzin }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-medkit text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Sakit</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSakit }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Alpha</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAlpha }}</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu Masuk</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absensi as $tanggal => $dataAbsensi)
                        @php
                            $tanggalFormatted = \Carbon\Carbon::parse($tanggal)->format('d/m/Y (l)');
                            $rowspan = $dataAbsensi->count();
                        @endphp
                        @foreach ($dataAbsensi as $index => $a)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @if ($index == 0)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        rowspan="{{ $rowspan }}">
                                        {{ $tanggalFormatted }}
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $a->siswa->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $a->siswa->nisn ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $a->siswa->nisn ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $status = $a->status_masuk ?? null; @endphp
                                    @if ($status == 'hadir')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Hadir
                                        </span>
                                    @elseif($status == 'terlambat')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Terlambat
                                        </span>
                                    @elseif($status == 'izin')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Izin
                                        </span>
                                    @elseif($status == 'sakit')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-medkit mr-1"></i>
                                            Sakit
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Alpha
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $a->waktu_masuk ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-chart-bar text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data absensi</h3>
                                    <p class="text-gray-500">Tidak ada data absensi untuk bulan yang dipilih</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
