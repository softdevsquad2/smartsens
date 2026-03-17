@extends('layouts.toolman')

@section('title', 'Dashboard Toolman')

@section('content')
    <div class="p-6">

        <h1 class="text-3xl font-bold mb-6">Dashboard Toolman</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Total Barang -->
            <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-blue-500">
                <h3 class="text-gray-600">Total Barang</h3>
                <p class="text-3xl font-bold">{{ $totalBarang }}</p>
            </div>

            <!-- Barang Dipinjam -->
            <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-orange-500">
                <h3 class="text-gray-600">Dipinjam</h3>
                <p class="text-3xl font-bold">{{ $dipinjam }}</p>
            </div>

            <!-- Total Siswa -->
            <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-green-500">
                <h3 class="text-gray-600">Total Siswa</h3>
                <p class="text-3xl font-bold">{{ $totalSiswa }}</p>
            </div>

            <!-- Total Toolman -->
            <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-purple-500">
                <h3 class="text-gray-600">Admin / Toolman</h3>
                <p class="text-3xl font-bold">{{ $totalUser }}</p>
            </div>

            <!-- Token Pengembalian -->
            <div class="bg-white p-5 rounded-xl shadow-md border-l-4 border-indigo-500">
                <h3 class="text-gray-600">Token Kembali (sekali pakai)</h3>
                @if ($token)
                    <p class="text-3xl font-bold">{{ $token }}</p>
                    <p class="text-sm text-gray-500 mt-1">Token aktif, berlaku 15 menit.</p>
                @else
                    <p class="text-3xl font-bold text-gray-400">-</p>
                    <p class="text-sm text-gray-500 mt-1">Klik tombol buat token di bawah.</p>
                @endif

                <form method="POST" action="{{ route('toolman.generateToken') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                        Generate Token Sekali Pakai
                    </button>
                </form>
            </div>

        </div>

        <!-- Menu Cepat -->
        <h2 class="text-xl font-bold mt-10 mb-4">Menu Cepat</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

            <a href="{{ route('toolman.barang') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-xl shadow-lg flex flex-col items-center">
                <i class="fa fa-box text-4xl mb-3"></i>
                <span class="text-lg font-semibold">Kelola Barang</span>
            </a>

            <a href="{{ route('toolman.peminjaman') }}"
                class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-xl shadow-lg flex flex-col items-center">
                <i class="fa fa-shopping-basket text-4xl mb-3"></i>
                <span class="text-lg font-semibold">Data Peminjaman</span>
            </a>



        </div>

    </div>
@endsection
