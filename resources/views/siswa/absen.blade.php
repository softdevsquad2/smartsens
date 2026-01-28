@extends('layouts.app')

@section('title', 'Absensi Siswa - SmartSens')
@section('page-title', 'Absensi Siswa')
@section('page-description', 'Lakukan absensi masuk atau pulang')

@section('sidebar')
    <!-- Beranda -->
    <a href="/siswa/dashboard"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Beranda</span>
    </a>

    <!-- Absensi -->
    <a href="/siswa/absen" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-fingerprint"></i>
        <span>Absensi</span>
    </a>

    <!-- Riwayat Absensi -->
    <a href="/siswa/riwayat-absensi"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-history"></i>
        <span>Riwayat Absensi</span>
    </a>
    <!-- Riwayat Absensi -->
    <a href="{{ url('/siswa/riwayat-sholat') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-history"></i>
        <span>Riwayat Sholat</span>
    </a>

    <!-- Pengaturan -->
    <a href="/siswa/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-app-primary">Absensi Siswa</h1>
            <p class="mt-1 text-sm text-app-primary">Lakukan absensi masuk atau pulang dengan mudah</p>
        </div>
        <a href="{{ route('siswa.dashboard') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
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

    <!-- Current Time and Location -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Current Time -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-clock mr-2 text-blue-500"></i>
                Waktu Saat Ini
            </h3>
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2" id="current-time">--:--:--</div>
                <div class="text-sm text-gray-500" id="current-date">--</div>
            </div>
        </div>

        <!-- Current Location -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                Lokasi Anda
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Latitude:</span>
                    <span class="text-sm font-mono text-gray-900" id="latitude-display">Mengecek...</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Longitude:</span>
                    <span class="text-sm font-mono text-gray-900" id="longitude-display">Mengecek...</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Status:</span>
                    <span class="text-sm font-medium" id="location-status">Mengecek lokasi...</span>
                </div>
            </div>
            <button onclick="getCurrentLocation()" id="refreshLocationBtn"
                class="w-full mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-sync-alt mr-2"></i>
                <span id="refreshBtnText">Refresh Lokasi</span>
            </button>
        </div>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-fingerprint mr-2 text-green-500"></i>
            Lakukan Absensi
        </h3>
        <p class="text-sm text-gray-600 mb-6">Pastikan Anda berada di lokasi sekolah untuk melakukan absensi.</p>

        <!-- Hidden inputs for coordinates -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <!-- Photo upload for all attendance -->
        <div id="photo-upload-section" class="mt-4 mb-2 p-6 border-2 border-dashed border-blue-400 rounded-xl"
            style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);">
            <div class="cursor-pointer" onclick="document.getElementById('photo-upload').click();">
                <div class="flex flex-col items-center justify-center py-4">
                    <div class="mb-4">
                        <div class="relative">
                            <i class="fas fa-camera text-6xl text-blue-600"></i>
                            <div class="absolute -bottom-2 -right-2 bg-green-500 rounded-full w-6 h-6 flex items-center justify-center">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-lg font-bold text-blue-900 mb-1">
                        Ambil Foto Absensi
                    </h4>
                    <p class="text-sm text-blue-700 text-center">
                        Klik di sini atau drag foto untuk upload<br/>
                        <span class="text-xs">(JPG, PNG - Max 250KB)</span>
                    </p>
                </div>
            </div>

            <input type="file" id="photo-upload" name="photo" accept="image/*" capture="environment"
                class="hidden">

            <p class="text-xs text-blue-600 mt-3 text-center font-medium">
                📸 Foto adalah bukti sah absensi Anda
            </p>

            <!-- Photo Preview -->
            <div id="photo-preview" class="mt-4" style="display: none;">
                <div class="bg-white rounded-lg p-3 border border-green-300">
                    <img id="preview-image" src="" alt="Preview"
                        class="w-full max-w-xs mx-auto rounded-lg border border-gray-300 mb-2">
                    <div class="text-center">
                        <p class="text-sm text-green-700 font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Foto berhasil dipilih
                        </p>
                        <button type="button" onclick="document.getElementById('photo-upload').value = ''; document.getElementById('photo-preview').style.display = 'none'; updateButtonStates();"
                            class="text-xs text-red-600 hover:text-red-800 mt-2">
                            Ganti foto
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Buttons -->
        <div id="normal-attendance-buttons" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Absen Masuk -->
            <button id="absenMasukBtn"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white text-lg font-medium rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <div class="text-center">
                    <i class="fas fa-sign-in-alt text-2xl mb-2"></i>
                    <div class="text-lg font-semibold">Absen Masuk</div>
                    <div class="text-sm opacity-90">Klik untuk absen masuk</div>
                </div>
            </button>

            <!-- Absen Pulang -->
            <button id="absenPulangBtn"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-lg font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <div class="text-center">
                    <i class="fas fa-sign-out-alt text-2xl mb-2"></i>
                    <div class="text-lg font-semibold">Absen Pulang</div>
                    <div class="text-sm opacity-90">Klik untuk absen pulang</div>
                </div>
            </button>
        </div>

        <!-- Sick/Permission Buttons (Hidden by default) -->
        <div id="sick-permission-buttons" class="grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
            <!-- Sakit -->
            <button id="absenSakitBtn"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white text-lg font-medium rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <div class="text-center">
                    <i class="fas fa-user-injured text-2xl mb-2"></i>
                    <div class="text-lg font-semibold">Sakit</div>
                    <div class="text-sm opacity-90">Klik untuk absen sakit</div>
                </div>
            </button>

            <!-- Izin -->
            <button id="absenIzinBtn"
                class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-orange-600 to-orange-700 text-white text-lg font-medium rounded-lg hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <div class="text-center">
                    <i class="fas fa-user-clock text-2xl mb-2"></i>
                    <div class="text-lg font-semibold">Izin</div>
                    <div class="text-sm opacity-90">Klik untuk absen izin</div>
                </div>
            </button>
        </div>

        <!-- Instructions -->
        <div class="mt-6 p-4 rounded-lg" style="background-color: #e9ecef;">
            <h4 class="text-sm font-medium text-blue-900 mb-2">
                <i class="fas fa-info-circle mr-2"></i>
                Petunjuk Absensi:
            </h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Pastikan GPS aktif di perangkat Anda</li>
                <li>• Upload foto wajib untuk semua absensi</li>
                <li>• Berada dalam radius sekolah untuk absensi normal</li>
                <li>• Di luar radius untuk absensi sakit/izin</li>
                <li>• Absen masuk sebelum jam masuk sekolah</li>
                <li>• Absen pulang setelah jam pulang sekolah</li>
                <li>• Tunggu konfirmasi sebelum menutup halaman</li>
            </ul>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;">
        <div class="bg-white rounded-lg p-6 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Memproses absensi...</p>
        </div>
    </div>

    <script>
        let currentLatitude = null;
        let currentLongitude = null;

        // Helper functions for SweetAlert
        function showError(message, title = 'Error') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        function showSuccess(message, title = 'Berhasil') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function showConfirm(message, title = 'Konfirmasi', confirmText = 'Ya', cancelText = 'Batal') {
            return Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            });
        }

        // Update time every second
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                // Disable refresh button and show loading state
                const refreshBtn = document.getElementById('refreshLocationBtn');
                const refreshBtnText = document.getElementById('refreshBtnText');
                refreshBtn.disabled = true;
                refreshBtnText.textContent = 'Mengecek lokasi...';

                document.getElementById('location-status').textContent = 'Mengecek lokasi...';
                document.getElementById('location-status').className = 'text-sm font-medium text-yellow-600';

                // Reset buttons to default state while checking location
                document.getElementById('normal-attendance-buttons').style.display = 'grid';
                document.getElementById('sick-permission-buttons').style.display = 'none';
                document.getElementById('photo-upload-section').style.display = 'none';

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentLatitude = position.coords.latitude;
                        currentLongitude = position.coords.longitude;

                        document.getElementById('latitude').value = currentLatitude;
                        document.getElementById('longitude').value = currentLongitude;
                        document.getElementById('latitude-display').textContent = currentLatitude.toFixed(6);
                        document.getElementById('longitude-display').textContent = currentLongitude.toFixed(6);

                        // Check if within school radius
                        checkSchoolRadius();

                        // Re-enable refresh button
                        refreshBtn.disabled = false;
                        refreshBtnText.textContent = 'Refresh Lokasi';
                    },
                    function(error) {
                        let errorMessage = 'Tidak dapat mengakses lokasi';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Akses lokasi ditolak';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Lokasi tidak tersedia';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Timeout mendapatkan lokasi';
                                break;
                        }

                        document.getElementById('location-status').textContent = errorMessage;
                        document.getElementById('location-status').className = 'text-sm font-medium text-red-600';

                        // Keep buttons disabled
                        document.getElementById('absenMasukBtn').disabled = true;
                        document.getElementById('absenPulangBtn').disabled = true;

                        // Re-enable refresh button
                        refreshBtn.disabled = false;
                        refreshBtnText.textContent = 'Refresh Lokasi';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                document.getElementById('location-status').textContent = 'Browser tidak mendukung geolocation';
                document.getElementById('location-status').className = 'text-sm font-medium text-red-600';

                // Re-enable refresh button
                const refreshBtn = document.getElementById('refreshLocationBtn');
                const refreshBtnText = document.getElementById('refreshBtnText');
                refreshBtn.disabled = false;
                refreshBtnText.textContent = 'Refresh Lokasi';
            }
        }

        // Show loading overlay
        function showLoading() {
            document.getElementById('loading-overlay').style.display = 'flex';
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loading-overlay').style.display = 'none';
        }

        // Check if within school radius
        function checkSchoolRadius() {

            // Get school settings from server
            fetch('/api/settings')
                .then(response => response.json())
                .then(data => {
                    const schoolLat = parseFloat(data.school_latitude);
                    const schoolLng = parseFloat(data.school_longitude);
                    const radius = parseInt(data.attendance_radius) || 100;

                    const distance = calculateDistance(currentLatitude, currentLongitude, schoolLat, schoolLng);

                    if (distance <= radius) {
                        document.getElementById('location-status').textContent =
                            'Lokasi ditemukan (Dalam radius sekolah)';
                        document.getElementById('location-status').className = 'text-sm font-medium text-green-600';
                        document.getElementById('photo-upload-section').style.display = 'block';

                        // Show normal attendance buttons
                        document.getElementById('normal-attendance-buttons').style.display = 'grid';
                        document.getElementById('sick-permission-buttons').style.display = 'none';
                    } else {
                        document.getElementById('location-status').textContent =
                            'Lokasi ditemukan (Di luar radius sekolah)';
                        document.getElementById('location-status').className = 'text-sm font-medium text-yellow-600';
                        document.getElementById('photo-upload-section').style.display = 'block';

                        // Show sick/permission buttons
                        document.getElementById('normal-attendance-buttons').style.display = 'none';
                        document.getElementById('sick-permission-buttons').style.display = 'grid';
                    }

                    // Enable buttons based on location and photo upload
                    updateButtonStates();
                })
                .catch(error => {
                    console.error('Error checking radius:', error);
                    document.getElementById('location-status').textContent = 'Lokasi ditemukan';
                    document.getElementById('location-status').className = 'text-sm font-medium text-green-600';

                    // Show normal buttons on error
                    document.getElementById('normal-attendance-buttons').style.display = 'grid';
                    document.getElementById('sick-permission-buttons').style.display = 'none';
                    document.getElementById('photo-upload-section').style.display = 'block';

                    updateButtonStates();
                });
        }

        // Calculate distance between two coordinates
        function calculateDistance(lat1, lng1, lat2, lng2) {
            const earthRadius = 6371000; // Earth radius in meters
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return earthRadius * c;
        }

        // Update button states based on location and photo upload
        function updateButtonStates() {
            const photoInput = document.getElementById('photo-upload');
            const hasPhoto = photoInput.files.length > 0;

            // Get school settings to determine radius
            fetch('/api/settings')
                .then(response => response.json())
                .then(data => {
                    const schoolLat = parseFloat(data.school_latitude);
                    const schoolLng = parseFloat(data.school_longitude);
                    const radius = parseInt(data.attendance_radius) || 100;

                    let distance = 0;
                    if (currentLatitude && currentLongitude) {
                        distance = calculateDistance(currentLatitude, currentLongitude, schoolLat, schoolLng);
                    }

                    const isWithinRadius = distance <= radius;

                    // Check if it's time for pulang
                    const now = new Date();
                    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString()
                        .padStart(2, '0');
                    const jamPulang = '{{ \App\Models\Setting::getSetting('jam_pulang') ?? '15:00' }}';
                    const isPulangTime = currentTime >= jamPulang;

                    // Enable/disable buttons based on location, photo, and time
                    if (isWithinRadius) {
                        document.getElementById('absenMasukBtn').disabled = !hasPhoto;
                        document.getElementById('absenPulangBtn').disabled = !hasPhoto || !isPulangTime;
                        document.getElementById('absenSakitBtn').disabled = true;
                        document.getElementById('absenIzinBtn').disabled = true;
                    } else {
                        document.getElementById('absenMasukBtn').disabled = true;
                        document.getElementById('absenPulangBtn').disabled = true;
                        document.getElementById('absenSakitBtn').disabled = !hasPhoto;
                        document.getElementById('absenIzinBtn').disabled = !hasPhoto;
                    }
                })
                .catch(error => {
                    console.error('Error updating button states:', error);
                    // Default to normal buttons enabled if photo is uploaded
                    const hasPhoto = photoInput.files.length > 0;
                    const now = new Date();
                    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString()
                        .padStart(2, '0');
                    const jamPulang = '{{ \App\Models\Setting::getSetting('jam_pulang') ?? '15:00' }}';
                    const isPulangTime = currentTime >= jamPulang;

                    document.getElementById('absenMasukBtn').disabled = !hasPhoto;
                    document.getElementById('absenPulangBtn').disabled = !hasPhoto || !isPulangTime;
                    document.getElementById('absenSakitBtn').disabled = true;
                    document.getElementById('absenIzinBtn').disabled = true;
                });
        }

        // Compress image if too large
        function compressImage(file, maxSizeKB, callback) {
            const maxSize = maxSizeKB * 1024; // Convert to bytes
            if (file.size <= maxSize) {
                callback(file);
                return;
            }

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = function() {
                // Calculate new dimensions (maintain aspect ratio)
                let { width, height } = img;
                const maxDimension = 1024; // Max width or height

                if (width > height) {
                    if (width > maxDimension) {
                        height = (height * maxDimension) / width;
                        width = maxDimension;
                    }
                } else {
                    if (height > maxDimension) {
                        width = (width * maxDimension) / height;
                        height = maxDimension;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                // Draw and compress
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(function(blob) {
                    // If still too large, try lower quality
                    if (blob.size > maxSize) {
                        canvas.toBlob(function(blob2) {
                            callback(blob2);
                        }, 'image/jpeg', 0.7); // 70% quality
                    } else {
                        callback(blob);
                    }
                }, 'image/jpeg', 0.9); // 90% quality
            };

            img.src = URL.createObjectURL(file);
        }

        // Photo upload event listener
        document.getElementById('photo-upload').addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 250 * 1024; // 250KB in bytes

            if (file) {
                // Compress if necessary
                compressImage(file, 250, (compressedFile) => {
                    // Create a new File object with the compressed data
                    const newFile = new File([compressedFile], file.name, {
                        type: compressedFile.type,
                        lastModified: Date.now()
                    });

                    // Replace the file in the input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(newFile);
                    this.files = dataTransfer.files;

                    // Show photo preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-image').src = e.target.result;
                        document.getElementById('photo-preview').style.display = 'block';
                    };
                    reader.readAsDataURL(newFile);
                });
            } else {
                // Hide photo preview
                document.getElementById('photo-preview').style.display = 'none';
            }

            updateButtonStates();
        });

        // Absen Masuk
        document.getElementById('absenMasukBtn').addEventListener('click', function() {
            if (!currentLatitude || !currentLongitude) {
                showError('Mohon izinkan akses lokasi dan coba lagi.');
                return;
            }

            // Check if photo is uploaded
            const photoInput = document.getElementById('photo-upload');
            if (!photoInput.files.length) {
                showError('Silakan upload foto terlebih dahulu untuk absensi.');
                return;
            }

            showConfirm('Yakin ingin melakukan absen masuk?', 'Konfirmasi Absen Masuk', 'Ya, Absen Masuk', 'Batal')
                .then((result) => {
                    if (result.isConfirmed) {
                        showLoading();

                        // Prepare form data
                        const formData = new FormData();
                        formData.append('id_siswa', {{ Auth::user()->siswa->id_siswa }});
                        formData.append('latitude', currentLatitude);
                        formData.append('longitude', currentLongitude);
                        formData.append('photo', photoInput.files[0]);

                        fetch('/api/absen-masuk', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: formData
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                console.log('Response ok:', response.ok);
                                if (!response.ok) {
                                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showSuccess(data.message, 'Absen Masuk Berhasil!');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    showError(data.message, 'Gagal Absen Masuk');
                                }
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Error:', error);
                                showError('Terjadi kesalahan saat absen masuk: ' + error.message, 'Error Sistem');
                            });
                    }
                });
        });

        // Absen Pulang
        document.getElementById('absenPulangBtn').addEventListener('click', function() {
            if (!currentLatitude || !currentLongitude) {
                showError('Mohon izinkan akses lokasi dan coba lagi.');
                return;
            }

            // Check if photo is uploaded
            const photoInput = document.getElementById('photo-upload');
            if (!photoInput.files.length) {
                showError('Silakan upload foto terlebih dahulu untuk absensi.');
                return;
            }

            showConfirm('Yakin ingin melakukan absen pulang?', 'Konfirmasi Absen Pulang', 'Ya, Absen Pulang',
                'Batal').then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    // Prepare form data
                    const formData = new FormData();
                    formData.append('id_siswa', {{ Auth::user()->siswa->id_siswa }});
                    formData.append('latitude', currentLatitude);
                    formData.append('longitude', currentLongitude);
                    formData.append('photo', photoInput.files[0]);

                    fetch('/api/absen-pulang', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response ok:', response.ok);
                            if (!response.ok) {
                                throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                showSuccess(data.message, 'Absen Pulang Berhasil!');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showError(data.message, 'Gagal Absen Pulang');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            showError('Terjadi kesalahan saat absen pulang: ' + error.message, 'Error Sistem');
                        });
                }
            });
        });

        // Absen Sakit
        document.getElementById('absenSakitBtn').addEventListener('click', function() {
            if (!currentLatitude || !currentLongitude) {
                showError('Mohon izinkan akses lokasi dan coba lagi.');
                return;
            }

            // Check if photo is uploaded
            const photoInput = document.getElementById('photo-upload');
            if (!photoInput.files.length) {
                showError('Silakan upload foto terlebih dahulu untuk absensi sakit.');
                return;
            }

            showConfirm('Yakin ingin melakukan absen sakit?', 'Konfirmasi Absen Sakit', 'Ya, Absen Sakit', 'Batal')
                .then((result) => {
                    if (result.isConfirmed) {
                        showLoading();

                        // Prepare form data
                        const formData = new FormData();
                        formData.append('id_siswa', {{ Auth::user()->siswa->id_siswa }});
                        formData.append('latitude', currentLatitude);
                        formData.append('longitude', currentLongitude);
                        formData.append('photo', photoInput.files[0]);
                        formData.append('attendance_type', 'sakit');

                        fetch('/api/absen-masuk', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: formData
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                console.log('Response ok:', response.ok);
                                if (!response.ok) {
                                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showSuccess(data.message, 'Absen Sakit Berhasil!');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    showError(data.message, 'Gagal Absen Sakit');
                                }
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Error:', error);
                                showError('Terjadi kesalahan saat absen sakit: ' + error.message, 'Error Sistem');
                            });
                    }
                });
        });

        // Absen Izin
        document.getElementById('absenIzinBtn').addEventListener('click', function() {
            if (!currentLatitude || !currentLongitude) {
                showError('Mohon izinkan akses lokasi dan coba lagi.');
                return;
            }

            // Check if photo is uploaded
            const photoInput = document.getElementById('photo-upload');
            if (!photoInput.files.length) {
                showError('Silakan upload foto terlebih dahulu untuk absensi izin.');
                return;
            }

            showConfirm('Yakin ingin melakukan absen izin?', 'Konfirmasi Absen Izin', 'Ya, Absen Izin', 'Batal')
                .then((result) => {
                    if (result.isConfirmed) {
                        showLoading();

                        // Prepare form data
                        const formData = new FormData();
                        formData.append('id_siswa', {{ Auth::user()->siswa->id_siswa }});
                        formData.append('latitude', currentLatitude);
                        formData.append('longitude', currentLongitude);
                        formData.append('photo', photoInput.files[0]);
                        formData.append('attendance_type', 'izin');

                        fetch('/api/absen-masuk', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: formData
                            })
                            .then(response => {
                                console.log('Response status:', response.status);
                                console.log('Response ok:', response.ok);
                                if (!response.ok) {
                                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showSuccess(data.message, 'Absen Izin Berhasil!');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    showError(data.message, 'Gagal Absen Izin');
                                }
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Error:', error);
                                showError('Terjadi kesalahan saat absen izin: ' + error.message, 'Error Sistem');
                            });
                    }
                });
        });

        // Check if it's time for pulang
        function checkPulangTime() {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2,
                '0');

            // Get jam pulang from server (default 15:00)
            const jamPulang = '{{ \App\Models\Setting::getSetting('jam_pulang') ?? '15:00' }}';

            const pulangBtn = document.getElementById('absenPulangBtn');

            if (currentTime >= jamPulang) {
                // Sudah jam pulang - enable tombol
                pulangBtn.disabled = false;
                pulangBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                pulangBtn.classList.add('hover:scale-105');

                // Update text
                const textElement = pulangBtn.querySelector('.text-sm');
                textElement.textContent = 'Klik untuk absen pulang';
                textElement.classList.remove('text-yellow-600');
                textElement.classList.add('opacity-90');
            } else {
                // Belum jam pulang - disable tombol
                pulangBtn.disabled = true;
                pulangBtn.classList.add('opacity-50', 'cursor-not-allowed');
                pulangBtn.classList.remove('hover:scale-105');

                // Update text
                const textElement = pulangBtn.querySelector('.text-sm');
                textElement.textContent = `Belum jam pulang (${jamPulang})`;
                textElement.classList.remove('opacity-90');
                textElement.classList.add('text-yellow-600');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);
            getCurrentLocation();
            checkPulangTime();
            setInterval(checkPulangTime, 60000); // Check every minute

            // Auto refresh location every 30 seconds
            // setInterval(function() {
            //     // Only auto refresh if location was previously obtained successfully
            //     if (currentLatitude && currentLongitude) {
            //         getCurrentLocation();
            //     }
            // }, 10000); // 10 seconds
        });
    </script>
@endsection
