@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi - SmartSens')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-description', 'Konfigurasi sistem absensi GPS')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Aplikasi</h1>
            <p class="mt-1 text-sm text-gray-600">Konfigurasi sistem absensi GPS dan pengaturan umum</p>
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

    <!-- Form Pengaturan -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Konfigurasi GPS dan Waktu</h3>
            <p class="mt-1 text-sm text-gray-500">Atur koordinat sekolah dan waktu absensi</p>
        </div>

        <form action="{{ route('admin.admin.settings.update') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Latitude Sekolah -->
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                        Latitude Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="latitude" id="latitude"
                        value="{{ old('latitude', $settings['latitude']) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('latitude') border-red-500 @enderror"
                        placeholder="Contoh: -6.2088" required>
                    @error('latitude')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Koordinat lintang sekolah (dalam derajat)</p>
                </div>

                <!-- Longitude Sekolah -->
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                        Longitude Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="longitude" id="longitude"
                        value="{{ old('longitude', $settings['longitude']) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('longitude') border-red-500 @enderror"
                        placeholder="Contoh: 106.8456" required>
                    @error('longitude')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Koordinat bujur sekolah (dalam derajat)</p>
                </div>

                <!-- Radius Absensi -->
                <div>
                    <label for="radius" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-circle mr-2 text-blue-500"></i>
                        Radius Absensi (meter) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="radius" id="radius" value="{{ old('radius', $settings['radius']) }}"
                        min="10" max="1000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('radius') border-red-500 @enderror"
                        placeholder="100" required>
                    @error('radius')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Jarak maksimal dari sekolah untuk absensi (10-1000 meter)</p>
                </div>

                <!-- Jam Masuk -->
                <div>
                    <label for="waktu_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-green-500"></i>
                        Jam Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="waktu_masuk" id="waktu_masuk"
                        value="{{ old('waktu_masuk', $settings['waktu_masuk']) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_masuk') border-red-500 @enderror"
                        required>
                    @error('waktu_masuk')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Waktu masuk sekolah</p>
                </div>

                <!-- Batas Jam Masuk (Terlambat) -->
                <div>
                    <label for="waktu_terlambat" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i>
                        Batas Jam Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="waktu_terlambat" id="waktu_terlambat"
                        value="{{ old('waktu_terlambat', $settings['waktu_terlambat'] ?? '07:30') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_terlambat') border-red-500 @enderror"
                        required>
                    @error('waktu_terlambat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Batas waktu masuk sebelum dianggap terlambat</p>
                </div>

                <!-- Jam Pulang -->
                <div>
                    <label for="waktu_pulang" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-orange-500"></i>
                        Jam Pulang <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="waktu_pulang" id="waktu_pulang"
                        value="{{ old('waktu_pulang', $settings['waktu_pulang']) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_pulang') border-red-500 @enderror"
                        required>
                    @error('waktu_pulang')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Waktu pulang sekolah</p>
                </div>
            </div>

            <!-- Attendance Control Settings -->
            <div class="grid grid-cols-1 gap-6 mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-check mr-2 text-green-500"></i>
                    Pengaturan Absensi Siswa
                </h3>

                <!-- Enable Student Attendance -->
                <div
                    class="bg-gradient-to-r from-green-50 via-blue-50 to-purple-50 rounded-xl border-2 border-green-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <!-- Left Section: Text -->
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 mb-2">
                                <i class="fas fa-bell mr-2 text-green-600"></i>
                                Kontrol Absensi Siswa
                            </h4>
                            <p class="text-sm text-gray-700 leading-relaxed mb-3">
                                Aktifkan atau nonaktifkan sistem absensi untuk semua siswa. Saat dinonaktifkan, siswa akan
                                melihat pemberitahuan dan tidak dapat melakukan absensi.
                            </p>
                            <div class="flex items-center gap-3 text-xs text-gray-600">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                <span>Perubahan akan langsung berlaku untuk semua pengguna</span>
                            </div>
                        </div>

                        <!-- Right Section: Toggle -->
                        <div class="flex flex-col items-center md:items-end gap-3 flex-shrink-0">
                            <!-- Status Badge -->
                            <div id="status-container" class="flex items-center gap-2">
                                <i id="status-icon" class="fas fa-check-circle text-3xl text-green-500"></i>
                                <div class="text-right">
                                    <div id="status-text" class="text-2xl font-bold text-green-600">Aktif</div>
                                    <div class="text-xs text-gray-600">Sistem Berjalan</div>
                                </div>
                            </div>

                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer mt-2">
                                <input type="checkbox" name="enable_student_attendance" id="enable_student_attendance"
                                    value="1"
                                    {{ old('enable_student_attendance', $settings['enable_student_attendance'] ?? 1) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div
                                    class="w-16 h-8 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:border after:border-gray-300 after:transition-all after:duration-300 peer-checked:bg-gradient-to-r peer-checked:from-green-500 peer-checked:to-green-600 transition-all duration-300 shadow-md">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-700 hidden"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <style>
                    #enable_student_attendance:focus {
                        outline: none;
                    }
                </style>

                <script>
                    // Initialize status
                    function updateStatusUI() {
                        const checkbox = document.getElementById('enable_student_attendance');
                        const statusText = document.getElementById('status-text');
                        const statusIcon = document.getElementById('status-icon');
                        const statusContainer = document.getElementById('status-container');

                        if (checkbox.checked) {
                            statusText.textContent = 'Aktif';
                            statusText.className = 'text-2xl font-bold text-green-600';
                            statusIcon.className = 'fas fa-check-circle text-3xl text-green-500 animate-pulse';
                            statusContainer.className = 'flex items-center gap-2 animate-in';
                        } else {
                            statusText.textContent = 'Nonaktif';
                            statusText.className = 'text-2xl font-bold text-red-600';
                            statusIcon.className = 'fas fa-times-circle text-3xl text-red-500';
                            statusContainer.className = 'flex items-center gap-2';
                        }
                    }

                    // Update when toggle changes
                    document.getElementById('enable_student_attendance').addEventListener('change', function() {
                        updateStatusUI();
                    });

                    // Set initial state
                    updateStatusUI();

                    // Add smooth animation on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        const statusContainer = document.getElementById('status-container');
                        statusContainer.style.animation = 'slideIn 0.5s ease-out';
                    });
                </script>

                <style>
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateX(20px);
                        }

                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }

                    #status-icon.animate-pulse {
                        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                    }

                    @keyframes pulse {

                        0%,
                        100% {
                            opacity: 1;
                        }

                        50% {
                            opacity: 0.7;
                        }
                    }

                    /* Improve toggle accessibility */
                    label:focus-within {
                        outline: 2px solid #10b981;
                        outline-offset: 2px;
                    }
                </style>
            </div>


            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button type="button" onclick="resetForm()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-undo mr-2"></i>
                    Reset
                </button>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Current Settings Info -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            Informasi Pengaturan Saat Ini
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 border border-blue-200">
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Koordinat Sekolah</p>
                        <p class="text-sm text-gray-600">{{ $settings['latitude'] }}, {{ $settings['longitude'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-blue-200">
                <div class="flex items-center">
                    <i class="fas fa-circle text-blue-500 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Radius Absensi</p>
                        <p class="text-sm text-gray-600">{{ $settings['radius'] }} meter</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-blue-200">
                <div class="flex items-center">
                    <i class="fas fa-clock text-green-500 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Jam Operasional</p>
                        <p class="text-sm text-gray-600">
                            Masuk: {{ $settings['waktu_masuk'] }}<br>
                            Batas: {{ $settings['waktu_terlambat'] }}<br>
                            Pulang: {{ $settings['waktu_pulang'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetForm() {
            showConfirm('Yakin ingin mereset form ke nilai default?', 'Reset Form', 'Ya, Reset', 'Batal').then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('latitude').value = '';
                    document.getElementById('longitude').value = '';
                    document.getElementById('radius').value = '100';
                    document.getElementById('waktu_masuk').value = '07:00';
                    document.getElementById('waktu_terlambat').value = '07:30';
                    document.getElementById('waktu_pulang').value = '15:00';
                    document.getElementById('enable_student_attendance').checked = true;
                }
            });
        }
    </script>
@endsection
