@extends('layouts.app')

@section('title', 'Pengaturan - SmartSens')
@section('page-title', 'Pengaturan')
@section('page-description', 'Kelola pengaturan akun siswa')

@section('sidebar')
    <!-- Beranda -->
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
    <a href="/siswa/riwayat-sholat"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-history"></i>
        <span>Riwayat sholat</span>
    </a>

    <!-- Pengaturan -->
    <a href="/siswa/settings" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <!-- Header -->


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

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Informasi Akun -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-user mr-2 text-blue-500"></i>
            Informasi Akun
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->nama }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-id-card text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">NISN</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->nisn }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chalkboard text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Kelas</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->kelas->nama_kelas }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-graduation-cap text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Jurusan</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->kelas->jurusan->nama_jurusan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pengaturan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">


        <form action="{{ route('siswa.settings.update') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Nomor HP Orang Tua -->
                <div>
                    <label for="no_hp_ortu" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-green-500"></i>
                        Nomor HP Orang Tua
                    </label>
                    <input type="text" name="no_hp_ortu" id="no_hp_ortu" disabled
                        value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_hp_ortu') border-red-500 @enderror"
                        placeholder="Contoh: 081234567890">
                    @error('no_hp_ortu')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Nomor HP orang tua untuk menerima notifikasi absensi via WhatsApp
                    </p>
                </div>
            </div>


        </form>
    </div>

    <!-- Informasi Sistem -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            Informasi Sistem
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-bell text-blue-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Notifikasi WhatsApp</span>
                </div>
                <span class="text-sm text-blue-600 font-medium">Aktif</span>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Keamanan Data</span>
                </div>
                <span class="text-sm text-green-600 font-medium">Terlindungi</span>
            </div>
        </div>

        <div class="mt-4 p-4 rounded-lg bg-white border border-blue-200">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Catatan:</h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Nomor HP orang tua digunakan untuk notifikasi absensi otomatis</li>
                <li>• Pastikan nomor HP yang dimasukkan aktif dan dapat menerima pesan WhatsApp</li>
                <li>• Data Anda akan disimpan dengan aman dan hanya digunakan untuk keperluan absensi</li>
            </ul>
        </div>
    </div>

    <script>
        function resetForm() {
            showConfirm('Yakin ingin mereset form ke nilai sebelumnya?', 'Reset Form', 'Ya, Reset', 'Batal').then((
                result) => {
                if (result.isConfirmed) {
                    document.getElementById('no_hp_ortu').value = '{{ $siswa->no_hp_ortu }}';
                }
            });
        }
    </script>
@endsection
