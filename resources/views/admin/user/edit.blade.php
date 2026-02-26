@extends('layouts.app')

@section('title', 'Edit User - SmartSens')
@section('page-title', 'Edit User')
@section('page-description', 'Edit informasi user')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="mt-1 text-sm text-gray-600">Edit informasi user: {{ $user->username }}</p>
        </div>
        <a href="{{ route('admin.user.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi User</h3>
            <p class="mt-1 text-sm text-gray-500">Edit informasi user di bawah ini</p>
        </div>

        <form action="{{ route('admin.user.update', $user->id_user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror"
                        placeholder="Masukkan username" required>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password (Biarkan kosong jika tidak ingin mengubah)
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password baru (opsional)">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Siswa (Jika Role Siswa) -->
                <div id="siswa-field" style="display: none;">
                    <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-2">
                        Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="id_siswa" id="id_siswa"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_siswa') border-red-500 @enderror">
                        <option value="">Pilih Siswa</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->id_siswa }}"
                                {{ old('id_siswa', $user->id_siswa) == $s->id_siswa ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->kelas->nama_kelas ?? '' }} -
                                {{ $s->kelas->jurusan->nama_jurusan ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_siswa')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Wali Kelas (Jika Role Guru) -->
                <div id="wali-kelas-field" style="display: none;">
                    <label for="id_wali_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Guru <span class="text-red-500">*</span>
                    </label>
                    <select name="id_wali_kelas" id="id_wali_kelas"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_wali_kelas') border-red-500 @enderror">
                        <option value="">Pilih Guru</option>
                        @foreach ($waliKelas as $wk)
                            <option value="{{ $wk->id_wali_kelas }}"
                                {{ old('id_wali_kelas', $user->id_wali_kelas) == $wk->id_wali_kelas ? 'selected' : '' }}>
                                {{ $wk->nama }} ({{ $wk->kelas->nama_kelas ?? '' }} -
                                {{ $wk->kelas->jurusan->nama_jurusan ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_wali_kelas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Card Code -->
                <div>
                    <label for="card_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Card Code (Opsional)
                    </label>
                    <input type="text" name="card_code" id="card_code" value="{{ old('card_code', $user->card_code) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('card_code') border-red-500 @enderror"
                        placeholder="Masukkan card code (opsional)">
                    @error('card_code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Card code digunakan untuk sistem absensi dengan kartu</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.user.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const siswaField = document.getElementById('siswa-field');
            const waliKelasField = document.getElementById('wali-kelas-field');
            const idSiswa = document.getElementById('id_siswa');
            const idWaliKelas = document.getElementById('id_wali_kelas');

            // Hide all fields first
            siswaField.style.display = 'none';
            waliKelasField.style.display = 'none';
            idSiswa.required = false;
            idWaliKelas.required = false;

            // Show relevant field based on role
            if (role === 'siswa') {
                siswaField.style.display = 'block';
                idSiswa.required = true;
            } else if (role === 'guru') {
                waliKelasField.style.display = 'block';
                idWaliKelas.required = true;
            }
        });

        // Initialize fields based on current role
        document.addEventListener('DOMContentLoaded', function() {
            const role = document.getElementById('role').value;
            const siswaField = document.getElementById('siswa-field');
            const waliKelasField = document.getElementById('wali-kelas-field');
            const idSiswa = document.getElementById('id_siswa');
            const idWaliKelas = document.getElementById('id_wali_kelas');

            if (role === 'siswa') {
                siswaField.style.display = 'block';
                idSiswa.required = true;
            } else if (role === 'guru') {
                waliKelasField.style.display = 'block';
                idWaliKelas.required = true;
            }
        });
    </script>
@endsection
