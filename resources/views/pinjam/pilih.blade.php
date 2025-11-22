@extends('layouts.pinjam')

@section('content')
    <div class="p-4">

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-5 text-center">Pilih Metode Verifikasi</h2>

        <!-- CARD MENU -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-xl mx-auto">

            <!-- Scan Kartu -->
            <a href="{{ route('pinjam.scan.card') }}"
                class="p-6 bg-white rounded-2xl shadow-lg border hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fas fa-id-card text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-1">Scan Kartu Siswa</h3>
                <p class="text-sm text-gray-600 text-center">Gunakan kartu RFID siswa</p>
            </a>

            <!-- Scan QR -->
            <a href="{{ route('pinjam.checkout') }}"
                class="p-6 bg-white rounded-2xl shadow-lg border hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                    <i class="fas fa-qrcode text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-1">Scan QR / NISN</h3>
                <p class="text-sm text-gray-600 text-center">Gunakan QR Code siswa</p>
            </a>

        </div>

    </div>
@endsection
