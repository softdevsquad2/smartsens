@extends('layouts.piket')

@section('title', 'Dashboard PIKET - SmartSens')

@push('styles')
    <style>
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .action-card {
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
@endpush

@section('content')
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl mb-8 shadow-lg">
        <!-- Header Background -->
        <div class="bg-gradient-to-r from-primary-600 via-primary-700 to-primary-800 p-8 relative">
            <!-- Decorative Overlay Grid -->
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(255,255,255,0.08),_transparent_50%)]">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
                <!-- Text Section -->
                <div class="max-w-2xl  lg:text-left">
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Selamat Datang, {{ Auth::user()->username }}!
                    </h1>
                    <p class="text-primary-100 text-lg leading-relaxed">
                        Selamat datang di <span class="font-semibold text-white">Dashboard PIKET SmartSens</span>.
                        Kelola kehadiran siswa dan izin pulang dengan mudah dan efisien.
                    </p>
                </div>

                <!-- Image / Logo Section -->
                <div class="relative flex-shrink-0 w-20 lg:w-30 float-animation">
                    <div class="absolute -top-4 -right-4 w-full h-full bg-white/10 rounded-full blur-2xl"></div>
                    <img src="{{ asset('SMKN2TASIK.png') }}" alt="Logo Sekolah"
                        class="relative z-10 w-full object-contain drop-shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan animasi halus -->
    <style>
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }
    </style>


    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Siswa -->
        <div class="stat-card bg-white rounded-xl border border-gray-100 p-6 hover:border-primary-200">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-users text-2xl text-primary-600"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalSiswa) }}</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Total Siswa</p>
                </div>
            </div>
        </div>
        <!-- Izin Pulang Hari Ini -->
        <div class="stat-card bg-white rounded-xl border border-gray-100 p-6 hover:border-green-200">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-sign-out-alt text-2xl text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($izinPulangHariIni) }}</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Izin Pulang Hari Ini</p>
                </div>
            </div>
        </div>

        <!-- Siswa Alpha Hari Ini -->
        <div class="stat-card bg-white rounded-xl border border-gray-100 p-6 hover:border-red-200">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-2xl text-gray-900"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($absensiHariIni) }}</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Jumlah Siswa Hadir Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-xl border border-gray-100 p-6 hover:border-red-200">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-2xl text-gray-900"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($dzuhur) }}</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Jumlah Siswa Sholat Dzuhur</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-xl border border-gray-100 p-6 hover:border-red-200">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-2xl text-gray-900"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($ashar) }}</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Jumlah Siswa Sholat Ashar</p>
                </div>
            </div>
        </div>


    </div>

    <!-- Quick Actions -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Daftar Siswa -->
            <a href="{{ route('piket.siswa.index') }}" class="action-card block">
                <div
                    class="h-full bg-white rounded-xl border border-gray-100 p-6 flex items-center hover:border-primary-200">
                    <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-users text-xl text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                        <p class="text-sm text-gray-500 mt-1">Lihat dan kelola data siswa</p>
                    </div>
                </div>
            </a>

            <!-- Izin Pulang -->
            <a href="{{ route('piket.izin-pulang') }}" class="action-card block">
                <div
                    class="h-full bg-white rounded-xl border border-gray-100 p-6 flex items-center hover:border-primary-200">
                    <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-sign-out-alt text-xl text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Izin Pulang</h3>
                        <p class="text-sm text-gray-500 mt-1">Proses izin pulang siswa</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    {{-- table absen --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Absensi Hari Ini</h2>
        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                            Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu
                            Absen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($absensi as $key => $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data->siswa->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $data->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ date('H:i:s', strtotime($data->waktu_absen)) }}</td>
                        </tr>
                    @endforeach

                    @if ($absensi->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada data absensi hari ini
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
