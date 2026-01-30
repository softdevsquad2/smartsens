@extends('layouts.app')

@section('title', 'Beranda Siswa - SmartSens')
@section('page-title', 'Dashboard')
{{-- @section('page-description', 'Sistem absensi GPS untuk siswa') --}}

@section('sidebar')
    <!-- Beranda -->
    <a href="/siswa/dashboard" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
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
    {{-- <a href="/siswa/riwayat-sholat"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-history"></i>
        <span>Riwayat sholat</span>
    </a> --}}

    <!-- Pengaturan -->
    <a href="/siswa/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-app-primary">Selamat Datang, {{ Auth::user()->siswa->nama ?? 'Siswa' }}!</h1>
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

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Student Info Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
         <!-- Quick Action -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                Aksi Cepat
            </h3>
            <div class="space-y-3">
                <a href="{{ route('siswa.absen') }}"
                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-fingerprint mr-2"></i>
                    Lakukan Absensi
                </a>

                <button onclick="getCurrentLocation(true)"
                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Cek Lokasi
                </button>
            </div>
        </div>
        <!-- Student Information -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user-graduate mr-2 text-blue-500"></i>
                Informasi Siswa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->siswa->nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-id-card text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">NISN</p>
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->siswa->nisn ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chalkboard text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kelas</p>
                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->siswa->kelas->nama_kelas ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jurusan</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ Auth::user()->siswa->kelas->jurusan->nama_jurusan ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center mb-2">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Poin Pelanggaran</p>
                        <p class="text-md font-semibold text-gray-900 text-red-700">
                            {{ $jumlahPoin ?? 0 }} Poin
                        </p>
                    </div>
            </div>
                <div class="flex items-center mb-2">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-trophy text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Poin Prestasi</p>
                        <p class="text-md font-semibold text-gray-900 text-green-700">
                            {{ $jumlahPoinPrestasi ?? 0 }} Poin
                        </p>
                    </div>
            </div>
        </div>


    </div>

    <!-- Attendance Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-day mr-2 text-green-500"></i>
                Status Absensi Hari Ini
            </h3>
            @if ($absensiHariIni)
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-lg" style="background-color: #e9ecef;">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-sign-in-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Waktu Masuk</p>
                                <p class="text-sm text-gray-600">{{ $absensiHariIni->waktu_masuk ?? 'Belum Absen' }}</p>
                            </div>
                        </div>
                        <div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($absensiHariIni->status_masuk == 'hadir') bg-green-100 text-green-800
                            @elseif($absensiHariIni->status_masuk == 'terlambat') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                                <i
                                    class="fas
                                @if ($absensiHariIni->status_masuk == 'hadir') fa-check-circle
                                @elseif($absensiHariIni->status_masuk == 'terlambat') fa-exclamation-triangle
                                @else fa-times-circle @endif mr-1"></i>
                                {{ ucfirst($absensiHariIni->status_masuk ?? 'N/A') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-lg" style="background-color: #e9ecef;">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-sign-out-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Waktu Pulang</p>
                                <p class="text-sm text-gray-600">{{ $absensiHariIni->waktu_pulang ?? 'Belum Pulang' }}</p>
                            </div>
                        </div>
                        <div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if ($absensiHariIni->status_pulang == 'pulang') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                                <i
                                    class="fas
                                @if ($absensiHariIni->status_pulang == 'pulang') fa-check-circle
                                @else fa-times-circle @endif mr-1"></i>
                                {{ ucfirst($absensiHariIni->status_pulang ?? 'N/A') }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada absensi hari ini</h4>
                    <p class="text-gray-500 mb-4">Lakukan absensi masuk untuk memulai hari</p>
                    <a href="{{ route('siswa.absen') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-fingerprint mr-2"></i>
                        Absen Sekarang
                    </a>
                </div>
            @endif
        </div>

        <!-- System Info -->
        {{-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Informasi Sistem
            </h3>
            <div class="space-y-4">
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
                        <span class="text-sm font-medium text-gray-900">Sistem Absensi</span>
                    </div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">Lokasi Anda</span>
                    </div>
                    <span class="text-sm text-yellow-600 font-medium" id="location-status">Mengecek...</span>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-lg" style="background-color: #e9ecef;">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Tips Absensi:</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Pastikan GPS aktif di perangkat Anda</li>
                    <li>• Berada dalam radius sekolah untuk absensi</li>
                    <li>• Absen masuk sebelum jam masuk sekolah</li>
                    <li>• Absen pulang setelah jam pulang sekolah</li>
                </ul>
            </div>
        </div> --}}
    </div>

    <script>
        function getCurrentLocation(showAlert = false) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Hanya tampilkan SweetAlert jika diminta (saat tombol diklik)
                        if (showAlert) {
                            showInfo(`Lokasi Anda: ${lat.toFixed(6)}, ${lng.toFixed(6)}`, 'Lokasi Ditemukan');
                        }

                        document.getElementById('location-status').textContent = 'Ditemukan';
                        document.getElementById('location-status').className = 'text-sm text-green-600 font-medium';
                    },
                    function(error) {
                        // Hanya tampilkan SweetAlert jika diminta (saat tombol diklik)
                        if (showAlert) {
                            showError('Tidak dapat mengakses lokasi. Pastikan GPS aktif.');
                        }

                        document.getElementById('location-status').textContent = 'Tidak dapat diakses';
                        document.getElementById('location-status').className = 'text-sm text-red-600 font-medium';
                    }
                );
            } else {
                // Hanya tampilkan SweetAlert jika diminta (saat tombol diklik)
                if (showAlert) {
                    showError('Browser tidak mendukung geolocation.');
                }
            }
        }

        // Check location on page load
        document.addEventListener('DOMContentLoaded', function() {
            getCurrentLocation();
        });
    </script>
@endsection
