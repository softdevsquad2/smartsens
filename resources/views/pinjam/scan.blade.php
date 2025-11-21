@extends('layouts.pinjam')

@section('content')
    <div class="p-4">
        <h2 class="text-xl font-bold mb-4">Scan QR NISN Siswa</h2>

        <!-- Tombol Aksi -->
        <div class="flex gap-2 mb-3">
            <button id="switchCameraBtn"
                class="px-4 py-2 rounded-lg bg-blue-500 text-white font-semibold shadow hover:bg-blue-600">
                <i class="fa-solid fa-camera-rotate text-xl"></i>
            </button>

            <button id="mirrorBtn"
                class="px-4 py-2 rounded-lg hidden bg-gray-600 text-white font-semibold shadow hover:bg-gray-700">
                Mirror: ON
            </button>
        </div>

        <div id="reader" style="width: 100%;"></div>

        <form id="scanForm" method="POST" action="{{ route('pinjam.scan.process') }}">
            @csrf
            <input type="hidden" name="qr" id="qrInput">
        </form>
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
                timer: 1800,
                showConfirmButton: false
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
