@extends('layouts.app')

@section('title', 'Edit Obat - UKS')
<x-sidebar></x-sidebar>
@section('content')
    @include('uks.partials.header', [
        'title' => 'Edit Data Obat',
        'description' => 'Perbarui informasi data obat',
        'action' => [
            'url' => route('uks.obat.index'),
            'text' => 'Kembali ke Daftar',
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('uks.partials.messages')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Edit Data Obat
                </h2>
                <p class="text-orange-100 text-sm mt-1">Perbarui informasi obat yang dipilih</p>
            </div>

            <form action="{{ route('uks.obat.update', $obat->id_obat) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Nama Obat -->
                        <div>
                            <label for="nama_obat" class="block text-sm font-semibold text-gray-800 mb-2">
                                Nama Obat <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-pills text-gray-400"></i>
                                </div>
                                <input type="text" name="nama_obat" id="nama_obat"
                                    value="{{ old('nama_obat', $obat->nama_obat) }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Masukkan nama obat lengkap" required>
                            </div>
                            @error('nama_obat')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-semibold text-gray-800 mb-2">
                                Kategori Obat <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tags text-gray-400"></i>
                                </div>
                                <select name="kategori" id="kategori"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors appearance-none bg-white"
                                    required>
                                    <option value="">Pilih Kategori Obat</option>
                                    <option value="Obat Bebas"
                                        {{ old('kategori', $obat->kategori) == 'Obat Bebas' ? 'selected' : '' }}>
                                        🟢 Obat Bebas
                                    </option>
                                    <option value="Obat Bebas Terbatas"
                                        {{ old('kategori', $obat->kategori) == 'Obat Bebas Terbatas' ? 'selected' : '' }}>
                                        🟡 Obat Bebas Terbatas
                                    </option>
                                    <option value="Obat Keras"
                                        {{ old('kategori', $obat->kategori) == 'Obat Keras' ? 'selected' : '' }}>
                                        🔴 Obat Keras
                                    </option>
                                    <option value="P3K"
                                        {{ old('kategori', $obat->kategori) == 'P3K' ? 'selected' : '' }}>
                                        🩹 P3K (Pertolongan Pertama)
                                    </option>
                                    <option value="Alat Kesehatan"
                                        {{ old('kategori', $obat->kategori) == 'Alat Kesehatan' ? 'selected' : '' }}>
                                        🏥 Alat Kesehatan
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Kadaluarsa Default -->
                        <div>
                            <label for="kadaluarsa_default" class="block text-sm font-semibold text-gray-800 mb-2">
                                Kadaluarsa Default
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="number" name="kadaluarsa_default" id="kadaluarsa_default"
                                    value="{{ old('kadaluarsa_default', $obat->kadaluarsa_default ?? 365) }}" min="1"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="365">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">hari</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Digunakan sebagai default saat menambah stok baru
                            </p>
                            @error('kadaluarsa_default')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-800 mb-2">
                                Deskripsi & Keterangan
                            </label>
                            <div class="relative">
                                <textarea name="deskripsi" id="deskripsi" rows="8"
                                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"
                                    placeholder="Masukkan deskripsi lengkap obat, indikasi, efek samping, atau keterangan lainnya...">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Opsional - Berikan informasi tambahan tentang obat
                            </p>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Current Info Display -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                                Informasi Stok Saat Ini
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Stok:</span>
                                    <span class="font-medium text-gray-900">{{ $obat->stokObat->sum('jumlah') ?? 0 }}
                                        unit</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jumlah Batch:</span>
                                    <span class="font-medium text-gray-900">{{ $obat->stokObat->count() }} batch</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    @php
                                        $totalStok = $obat->stokObat->sum('jumlah') ?? 0;
                                    @endphp
                                    @if ($totalStok > 10)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                                        </span>
                                    @elseif($totalStok > 0)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Menipis
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('uks.obat.index') }}"
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-amber-600 shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
