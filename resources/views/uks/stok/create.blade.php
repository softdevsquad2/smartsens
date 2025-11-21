@extends('layouts.app')

@section('title', 'Tambah Stok - UKS')

@section('content')
    @include('uks.partials.header', [
        'title' => 'Tambah Stok Baru',
        'description' => 'Tambahkan stok obat baru ke dalam sistem UKS',
        'action' => [
            'url' => route('uks.stok.index'),
            'text' => 'Kembali ke Daftar',
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('uks.partials.messages')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Tambah Stok Baru
                </h2>
                <p class="text-green-100 text-sm mt-1">Masukkan informasi stok obat yang akan ditambahkan</p>
            </div>

            <form action="{{ route('uks.stok.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Obat -->
                        <div>
                            <label for="id_obat" class="block text-sm font-semibold text-gray-800 mb-2">
                                Pilih Obat <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-pills text-gray-400"></i>
                                </div>
                                <select name="id_obat" id="id_obat"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors appearance-none bg-white"
                                    required>
                                    <option value="">Pilih Obat</option>
                                    @foreach ($obat as $o)
                                        <option value="{{ $o->id_obat }}"
                                            {{ old('id_obat') == $o->id_obat ? 'selected' : '' }}>
                                            {{ $o->nama_obat }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('id_obat')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label for="jumlah" class="block text-sm font-semibold text-gray-800 mb-2">
                                Jumlah Stok <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400"></i>
                                </div>
                                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}"
                                    min="1"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    placeholder="Masukkan jumlah stok" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">unit</span>
                                </div>
                            </div>
                            @error('jumlah')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Tanggal Masuk -->
                        <div>
                            <label for="tanggal_masuk" class="block text-sm font-semibold text-gray-800 mb-2">
                                Tanggal Masuk <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-plus text-gray-400"></i>
                                </div>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                    value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required>
                            </div>
                            @error('tanggal_masuk')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Expired Date -->
                        <div>
                            <label for="expired_date" class="block text-sm font-semibold text-gray-800 mb-2">
                                Tanggal Kadaluarsa <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-times text-gray-400"></i>
                                </div>
                                <input type="date" name="expired_date" id="expired_date"
                                    value="{{ old('expired_date') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pastikan tanggal kadaluarsa lebih dari tanggal masuk
                            </p>
                            @error('expired_date')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('uks.stok.index') }}"
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
