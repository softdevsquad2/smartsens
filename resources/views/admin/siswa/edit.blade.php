@extends('layouts.app')

@section('title', 'Edit Siswa - SmartSens')
@section('page-title', 'Edit Siswa')
@section('page-description', 'Edit informasi siswa')

@section('sidebar')
    <!-- Dashboard -->
    <a href="/admin/dashboard"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <!-- Absensi -->
    <a href="/admin/absensi"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-check"></i>
        <span>Absensi</span>
    </a>

    <!-- Kelola Siswa -->
    <a href="/admin/siswa" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-users"></i>
        <span>Kelola Siswa</span>
    </a>

    <!-- Kelola Kelas -->
    <a href="/admin/kelas"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chalkboard"></i>
        <span>Kelola Kelas</span>
    </a>

    <!-- Kelola Jurusan -->
    <a href="/admin/jurusan"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-graduation-cap"></i>
        <span>Kelola Jurusan</span>
    </a>

    <!-- Kelola User -->
    <a href="/admin/user"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-user-cog"></i>
        <span>Kelola User</span>
    </a>

    <!-- Pengaturan -->
    <a href="/admin/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Siswa</h1>
            <p class="mt-1 text-sm text-gray-600">Edit informasi siswa: {{ $siswa->nama }}</p>
        </div>
        <a href="{{ route('siswa.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Siswa</h3>
            <p class="mt-1 text-sm text-gray-500">Edit informasi siswa di bawah ini</p>
        </div>

        <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $baseInputClass =
                        'w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
                @endphp
                <!-- Nama Siswa -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Siswa <span class="text-red-500">*</span>
                    </label>
                    @php $inputClass = $baseInputClass . ' border-gray-300 ' . ($errors->has('nama') ? 'border-red-500' : 'border-gray-300'); @endphp
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}"
                        class="{{ $inputClass }}" placeholder="Masukkan nama lengkap siswa" required>
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NISN -->
                <div>
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                        NISN <span class="text-red-500">*</span>
                    </label>
                    @php $inputClass = $baseInputClass . ' border-gray-300 ' . ($errors->has('nisn') ? 'border-red-500' : 'border-gray-300'); @endphp
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn) }}"
                        class="{{ $inputClass }}" placeholder="Masukkan NISN siswa" required>
                    @error('nisn')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    @php $selectClass = $baseInputClass . ' border-gray-300 ' . ($errors->has('id_kelas') ? 'border-red-500' : 'border-gray-300'); @endphp
                    <select name="id_kelas" id="id_kelas" class="{{ $selectClass }}" required>
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id_kelas }}"
                                {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kelas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    @php $selectClass = $baseInputClass . ' border-gray-300 ' . ($errors->has('jenis_kelamin') ? 'border-red-500' : 'border-gray-300'); @endphp
                    <select name="jenis_kelamin" id="jenis_kelamin" class="{{ $selectClass }}" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Card Code -->
                <div>
                    <label for="card_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Card Code (Opsional)
                    </label>
                    @php $inputClass = $baseInputClass . ' border-gray-300 ' . ($errors->has('card_code') ? 'border-red-500' : 'border-gray-300'); @endphp
                    <input type="text" name="card_code" id="card_code" value="{{ old('card_code', $siswa->card_code) }}"
                        class="{{ $inputClass }}" placeholder="Masukkan card code (opsional)">
                    @error('card_code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Card code digunakan untuk sistem absensi dengan kartu</p>
                </div>

                <!-- No HP Orang Tua -->
                <div>
                    <label for="no_hp_ortu" class="block text-sm font-medium text-gray-700 mb-2">
                        No HP Orang Tua (Opsional)
                    </label>
                    @php $inputClass = $baseInputClass . ' border-gray-300 ' . ($errors->has('no_hp_ortu') ? 'border-red-500' : 'border-gray-300'); @endphp
                    <input type="text" name="no_hp_ortu" id="no_hp_ortu"
                        value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}" class="{{ $inputClass }}"
                        placeholder="Masukkan nomor HP orang tua (contoh: 08123456789)">
                    @error('no_hp_ortu')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Nomor HP orang tua untuk notifikasi WhatsApp absensi</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('siswa.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Update Siswa
                </button>
            </div>
        </form>
    </div>
@endsection
