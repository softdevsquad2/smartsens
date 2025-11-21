@extends('layouts.app')

@section('title', 'Dashboard UKS - SmartSens')

@push('styles')
    <style>
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .pulse-on-hover:hover {
            animation: pulse 2s infinite;
        }
    </style>
@endpush

@section('page-title', 'Dashboard UKS')
@section('page-description', 'Unit Kesehatan Sekolah - Overview kesehatan siswa')

<x-sidebar></x-sidebar>

@section('content')
    <!-- Welcome Banner -->
    <div
        class="relative overflow-hidden rounded-2xl mb-8 shadow-lg bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 p-8">

        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-15">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 400 400">
                <defs>
                    <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="white" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)" />
            </svg>
        </div>

        <!-- Content -->
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h1
                    class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-white via-green-200 to-green-400 bg-clip-text text-transparent drop-shadow-sm mb-2">
                    <span class="text-white">Selamat Datang di</span> <span class="text-yellow-300">UKS</span> <span
                        class="text-white">!</span>
                </h1>

                <p class="text-lg md:text-xl text-blue-100">
                    Kelola kesehatan siswa dengan mudah dan efisien.
                </p>
            </div>

            <!-- Logo -->
            <div class="flex-shrink-0">
                <div
                    class="bg-white/20 p-3 rounded-full shadow-lg ring-2 ring-white/30 backdrop-blur-md hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('logo_uks.jpg') }}" alt="Logo UKS"
                        class="w-20 h-20 md:w-24 md:h-24 rounded-full border-2 border-white shadow-inner object-cover">
                </div>
            </div>
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

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ url('uks/siswa') }}"
                class="flex items-center p-4 bg-white rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-plus text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Aksi Siswa</p>
                    <p class="text-sm text-gray-500">Pilih Siswa</p>
                </div>
            </a>
            <a href="{{ route('uks.izin-pulang') }}"
                class="flex items-center p-4 bg-white rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-home text-purple-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Izin Pulang</p>
                    <p class="text-sm text-gray-500">Proses izin pulang siswa</p>
                </div>
            </a>
            <a href="{{ route('uks.rekam-medis.index') }}"
                class="flex items-center p-4 bg-white rounded-xl border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-notes-medical text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Rekam Medis</p>
                    <p class="text-sm text-gray-500">Buat rekam medis baru</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Kunjungan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hospital-user text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Kunjungan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalKunjungan) }}</p>
                </div>
            </div>
        </div>



        <!-- Total Obat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pills text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Jenis Obat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalObat }}</p>
                </div>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow info-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Stok Obat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalStok }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Stok Menipis</p>

                    <p class="text-2xl font-bold text-gray-900">{{ $stokMenipis }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Stok Habis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stokHabis }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <!-- Obat Menipis -->
        <div class="bg-white rounded-xl shadow-sm border  border-gray-200 p-6 info-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stok Obat Sedikit</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama Obat</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @forelse ($daftarStokObat as $index => $data)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 font-medium">{{ $data->nama_obat }}</td>
                                <td class="px-4 py-2">{{ $data->kategori ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if ($data->total_stok == 0)
                                        <span class="text-red-600 font-bold">Habis</span>
                                    @elseif($data->total_stok < 20)
                                        <span class="text-yellow-600 font-bold">{{ $data->total_stok }} (Menipis)</span>
                                    @else
                                        {{ $data->total_stok }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada obat yang stoknya menipis atau habis
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
        <!-- Kunjungan Terakhir -->
        <div class="bg-white rounded-xl shadow-sm border  border-gray-200 p-6 info-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Kunjungan Terakhir</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama Siswa</th>
                            <th class="px-4 py-3 text-left">Kelas</th>
                            <th class="px-4 py-3 text-left">status</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @forelse ($kunjunganTerbaru as $index => $kunjungan)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 font-medium">{{ $kunjungan->siswa->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $kunjungan->siswa->kelas->nama_kelas ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $kunjungan->jenis_kunjungan }}</td>
                                <td class="px-4 py-2">{{ $kunjungan->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                    Belum ada kunjungan terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
