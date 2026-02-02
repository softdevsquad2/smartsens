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
     <a href="{{ route('guru.settings') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $hadirHariIni  }}</p>
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
    <div class=" mb-8">
        <!-- Absensi Bulan Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Trend Kehadiran Bulan Ini</h3>
                <p class="mt-1 text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
            <div class="p-6">
                <div class="relative" style="height: 300px; max-height: 300px;">
                    <canvas id="monthlyAttendanceChart" style="max-height: 100%; width: 100%;"></canvas>
                </div>
                <div class="mt-4 flex flex-wrap gap-4 text-center justify-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">Hadir</span>
                    </div>
                </div>
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
<!-- Floating Button Rekam Data -->
<div class="fixed bottom-4 right-4 z-50">
    <a href="{{ route('guru.rekam.pilih') }}"
        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-full w-16 h-16 shadow-lg transition-all duration-200 transform hover:scale-110"
        title="Rekam Data Siswa">
        <i class="fas fa-plus text-xl"></i>
    </a>
</div>
@endsection





@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize monthly attendance chart
        const ctx = document.getElementById('monthlyAttendanceChart').getContext('2d');
        const dailyData = @json($dailyAttendanceData);

        const monthlyAttendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => item.date),
                datasets: [
                    {
                        label: 'Hadir',
                        data: dailyData.map(item => item.hadir),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Siswa Hadir'
                        }
                    },
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                }
            }
        });
    });
</script>
@endpush

