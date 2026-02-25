@extends('layouts.app')

@section('title', 'Edit Guru - SmartSens')
@section('page-title', 'Edit Guru')
@section('page-description', 'Edit informasi guru')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
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

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Guru</h1>
            <p class="mt-1 text-sm text-gray-600">Edit informasi guru</p>
        </div>
        <a href="{{ route('admin.walikelas.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Wali Kelas</h3>
            <p class="mt-1 text-sm text-gray-500">Edit informasi wali kelas di bawah ini</p>
        </div>

        <form action="{{ route('admin.walikelas.update', $walikelas) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Wali Kelas -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Guru <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $walikelas->nama) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap wali kelas" required>
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas (Opsional)
                    </label>
                    <select name="id_kelas" id="id_kelas"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_kelas') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id_kelas }}"
                                {{ old('id_kelas', $walikelas->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} - {{ $k->jurusan->nama_jurusan ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kelas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User (Opsional) -->
                <div class="md:col-span-2">
                    <label for="id_user" class="block text-sm font-medium text-gray-700 mb-2">
                        User Account (Opsional)
                    </label>
                    <select name="id_user" id="id_user"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_user') border-red-500 @enderror">
                        <option value="">Pilih User Account (Opsional)</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id_user }}"
                                {{ old('id_user', $walikelas->id_user) == $user->id_user ? 'selected' : '' }}>
                                {{ $user->username }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_user')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Pilih user account yang sudah ada untuk mengaitkan dengan wali
                        kelas</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.walikelas.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Update Guru
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                console.log('Guru edit form submitted');
                console.log('Form data:', new FormData(form));

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Re-enable button after 5 seconds if no response
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        });
    </script>
@endsection
