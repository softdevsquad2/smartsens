@extends('layouts.toolman')

@section('title', 'Dashboard Toolman')

@section('content')
    <div class="p-6">

        <h1 class="text-3xl font-bold mb-6">Dashboard Toolman</h1>

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
