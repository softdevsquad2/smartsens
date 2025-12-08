@extends('layouts.pinjam')

@section('content')
    <div class="p-4">

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-5 text-center">Pilih Metode Verifikasi Untuk Cek Barang Dipinjam</h2>

        <!-- CARD MENU -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-xl mx-auto">

            <!-- Scan Kartu -->
            <a href="{{ route('kembali.scan.card') }}"
                class="p-6 bg-white rounded-2xl shadow-lg border hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fas fa-id-card text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-1">Scan Kartu Siswa</h3>
                <p class="text-sm text-gray-600 text-center">Gunakan kartu RFID siswa</p>
            </a>

            <!-- Scan QR -->
            <a href="{{ route('kembali.scan') }}"
                class="p-6 bg-white rounded-2xl shadow-lg border hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                    <i class="fas fa-qrcode text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-1">Scan QR / NISN</h3>
                <p class="text-sm text-gray-600 text-center">Gunakan QR Code siswa</p>
            </a>
            <!-- Cari Siswa -->
            <a href="{{ route('kembali.cari.siswa') }}"
                class="p-6 bg-white rounded-2xl shadow-lg border hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mb-4">
                    <i class="fas fa-search text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-1">Cari Siswa</h3>
                <p class="text-sm text-gray-600 text-center">Cari berdasarkan Nama atau NISN</p>
            </a>


        </div>

    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        /* alert siswa tidka di temuka */

        /* Mirror default aktif */
        #reader video,
        #reader canvas {
            transform: scaleX(-1);
        }
    </style>

    <script>
        // alert siswa
        @if (session('success'))
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "{{ session('success') }}",
                timer: 1500,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "{{ session('error') }}",
                // timer: 1800,
                showConfirmButton: true
            });
        @endif
        const html5QrCode = new Html5Qrcode("reader");

        let hasScanned = false;

        // DEFAULT → Kamera depan + mirror aktif
        let currentCamera = "user";
        let mirrorEnabled = true;

        function applyMirror() {
            const video = document.querySelector("#reader video");
            const canvas = document.querySelector("#reader canvas");

            if (!video) return;

            if (mirrorEnabled) {
                video.style.transform = "scaleX(-1)";
                if (canvas) canvas.style.transform = "scaleX(-1)";
            } else {
                video.style.transform = "scaleX(1)";
                if (canvas) canvas.style.transform = "scaleX(1)";
            }
        }

        function startCamera() {
            html5QrCode.start({
                    facingMode: currentCamera
                }, {
                    fps: 10,
                    qrbox: 500
                },
                onScanSuccess
            ).then(() => {
                setTimeout(applyMirror, 300);
            });
        }

        function onScanSuccess(decodedText) {
            if (hasScanned) return;
            hasScanned = true;

            html5QrCode.stop();

            Swal.fire({
                title: "Memproses...",
                text: "Mohon tunggu sebentar",
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            document.getElementById('qrInput').value = decodedText;

            setTimeout(() => {
                document.getElementById('scanForm').submit();
            }, 600);
        }

        // Jalankan langsung kamera depan + mirror
        startCamera();

        // Tombol ganti kamera
        document.getElementById('switchCameraBtn').addEventListener('click', async () => {
            try {
                await html5QrCode.stop();

                // Toggle antara front/back
                currentCamera = (currentCamera === "user") ? "environment" : "user";

                startCamera();
            } catch (err) {
                console.error("Error switch camera:", err);
            }
        });

        // Tombol mirror
        document.getElementById('mirrorBtn').addEventListener('click', () => {
            mirrorEnabled = !mirrorEnabled;
            document.getElementById('mirrorBtn').textContent =
                mirrorEnabled ? "Mirror: ON" : "Mirror: OFF";

            applyMirror();
        });
    </script>
@endsection
