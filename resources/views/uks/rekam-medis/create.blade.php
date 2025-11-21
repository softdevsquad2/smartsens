@extends('layouts.app')

@section('title', 'Tambah Rekam Medis - UKS')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection--single {
            height: 38px;
            border-radius: 0.5rem;
            border-color: #d1d5db;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.1);
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 0.5rem;
        }
    </style>
@endpush

<x-sidebar></x-sidebar>
@section('content')
    @include('uks.partials.header', [
        'title' => 'Tambah Rekam Medis Baru',
        'description' => 'Tambahkan data rekam medis siswa baru ke dalam sistem UKS',
        'action' => [
            'url' => route('uks.rekam-medis.index'),
            'text' => 'Kembali ke Daftar',
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('uks.partials.messages')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Tambah Rekam Medis Baru
                    </h2>
                    <p class="text-purple-100 text-sm mt-1">Masukkan informasi rekam medis siswa yang akan ditambahkan
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold">
                        <i class="fas fa-user-graduate mr-1"></i> Pilih siswa
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold">
                        <i class="fas fa-pills mr-1"></i> Pilih obat
                    </span>
                </div>
            </div>
            <form action="{{ route('uks.rekam-medis.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-8">
                        <!-- Siswa Selection -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                            <label for="siswa_dropdown" class="block text-sm font-bold text-purple-700 mb-2">
                                Cari dan Pilih Siswa <span class="text-red-500">*</span>
                            </label>


                            <input type="hidden" name="id_siswa" id="id_siswa_hidden"
                                value="{{ old('id_siswa', $selectedSiswa->id_siswa ?? '') }}">
                            <div id="selected_student_info"
                                class="mt-3 {{ isset($selectedSiswa) ? 'flex items-center space-x-3' : 'hidden' }} p-3 bg-purple-100 rounded-lg border border-purple-200">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <span id="selected_student_name"
                                        class="font-semibold text-purple-900">{{ $selectedSiswa->nama ?? '' }}</span>
                                    <span id="selected_student_nisn"
                                        class="text-xs text-purple-700 ml-2">{{ $selectedSiswa->nisn ?? '' }}</span>
                                </div>
                            </div>
                            @error('id_siswa')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>



                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-2">
                                Tanggal Kunjungan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-day text-gray-400"></i>
                                </div>
                                <input type="date" name="tanggal" id="tanggal"
                                    value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-white"
                                    required>
                            </div>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-8">
                            <!-- diagnosis -->
                            <div>
                                <label for="keluhan" class="block text-sm font-bold text-gray-700 mb-2">
                                    Diagnosis <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <textarea name="diagnosis" id="diagnosis" rows="8"
                                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none bg-white"
                                        placeholder="Jelaskan keluhan, gejala, dan kondisi siswa secara detail...">{{ old('keluhan') }}</textarea>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Hasil Diagnosis Siswa
                                </p>
                                @error('diagnosis')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>


                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Keluhan -->
                        <div>
                            <label for="keluhan" class="block text-sm font-bold text-gray-700 mb-2">
                                Keluhan & Gejala <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="keluhan" id="keluhan" rows="8"
                                    class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none bg-white"
                                    placeholder="Jelaskan keluhan, gejala, dan kondisi siswa secara detail...">{{ old('keluhan') }}</textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Berikan deskripsi lengkap tentang keluhan siswa
                            </p>
                            @error('keluhan')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Obat yang diberikan (Dynamic Form) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                Obat yang Diberikan
                            </label>

                            <div id="obat-container" class="space-y-3 mb-4">
                                <!-- Dynamic rows will be added here by JavaScript -->
                            </div>

                            <button type="button" id="add-obat-btn" onclick="addObatRow()"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Tambah Obat
                            </button>

                            <p class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tambahkan satu atau lebih jenis obat, dan isi jumlahnya.
                            </p>

                            @error('obat_diberikan')
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
                    <a href="{{ route('uks.rekam-medis.index') }}"
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')


    <script>
        const obatList = @json($obatList);

        window.addObatRow = function() {
            const container = document.getElementById('obat-container');

            // Buat elemen wrapper
            const row = document.createElement('div');
            row.classList.add('item-obat', 'flex', 'space-x-3', 'mb-3');

            // Buat select
            let select = `<select name="obat_diberikan[id_obat][]" class="form-select obat-select w-1/2" required>
            <option value="">-- Pilih Obat --</option>`;

            obatList.forEach(o => {
                select += `<option value="${o.id}">${o.nama}</option>`;
            });

            select += `</select>`;

            // Masukkan row
            row.innerHTML = `
            ${select}
            <input type="number" name="obat_diberikan[jumlah][]" class=" px-2 form-input w-1/4" placeholder="Jumlah" min="1" required>
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded" onclick="removeObatRow(this)">X</button>
        `;

            container.appendChild(row);

            // Aktifkan select2 jika dipakai
            if ($('.obat-select').select2) {
                $('.obat-select').select2();
            }
        }

        window.removeObatRow = function(el) {
            el.parentElement.remove();
        };
    </script>
@endsection
