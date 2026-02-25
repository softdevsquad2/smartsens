@extends('layouts.app')

@section('title', 'Tambah Jurusan - SmartSens')
@section('page-title', 'Tambah Jurusan')
@section('page-description', 'Tambah jurusan baru ke dalam sistem')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Jurusan Baru</h1>
            <p class="mt-1 text-sm text-gray-600">Tambahkan jurusan baru ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.jurusan.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Jurusan</h3>
            <p class="mt-1 text-sm text-gray-500">Isi form di bawah ini untuk menambahkan jurusan baru</p>
        </div>

        <form action="{{ route('admin.jurusan.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Nama Jurusan -->
                <div>
                    <label for="nama_jurusan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Jurusan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" value="{{ old('nama_jurusan') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_jurusan') border-red-500 @enderror"
                        placeholder="Masukkan nama jurusan" required>
                    @error('nama_jurusan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.jurusan.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Jurusan
                </button>
            </div>
        </form>
    </div>
@endsection
