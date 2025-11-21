@extends('layouts.app')

@section('title', 'Tambah Izin Pulang - UKS')
<x-sidebar></x-sidebar>

@section('content')
    @include('uks.partials.header', [
        'title' => 'Izin Pulang Siswa',
        'description' => 'Scan kartu RFID siswa yang akan diberikan izin pulang',
        'action' => [
            'url' => route('uks.izin-pulang'),
            'text' => 'Kembali ke Daftar',
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('uks.partials.messages')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Izin Pulang Siswa
                </h2>
                <p class="text-blue-100 text-sm mt-1">Tempelkan kartu RFID siswa untuk memberikan izin pulang.</p>
            </div>

            <form action="{{ route('uks.izin-pulang.store') }}" method="POST" class="p-6 space-y-6" id="izin-pulang-form">
                @csrf




                <!-- Alasan Izin Pulang -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Izin Pulang <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="4"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white placeholder-gray-400"
                        placeholder="Masukkan alasan izin pulang...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                <!-- Scan RFID -->
                <div>
                    <label for="rfid_input" class="block text-sm font-semibold text-gray-700 mb-2">
                        Scan Kartu RFID Siswa
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" id="rfid_input" name="rfid_input"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            placeholder="Tempelkan kartu RFID siswa...">

                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <div id="rfid_status" class="w-3 h-3 bg-gray-300 rounded-full"></div>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Status: <span id="rfid_status_text">Menunggu kartu RFID</span>
                    </p>

                    <!-- Hidden Input for ID Siswa -->
                    <input type="hidden" name="id_siswa" id="id_siswa">
                </div>
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('uks.izin-pulang') }}"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                        Buat Izin Pulang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rfidInput = document.getElementById('rfid_input');
            const statusText = document.getElementById('rfid_status_text');
            const statusDot = document.getElementById('rfid_status');
            const idSiswaInput = document.getElementById('id_siswa');
            const infoBox = document.getElementById('selected_student_info');
            const namaSpan = document.getElementById('selected_student_name');
            const nisnSpan = document.getElementById('selected_student_nisn');
            const kelasSpan = document.getElementById('selected_student_kelas');

            rfidInput.focus();

            rfidInput.addEventListener('input', async function() {
                const rfid = rfidInput.value.trim();
                if (rfid.length >= 8) {
                    statusDot.classList.remove('bg-gray-300');
                    statusDot.classList.add('bg-yellow-400');
                    statusText.textContent = 'Memproses...';

                    try {
                        const res = await fetch(`/uks/uks/cari-siswa-rfid/${rfid}`);
                        if (!res.ok) throw new Error('Kartu tidak dikenali');

                        const data = await res.json();

                        if (data && data.nama) {
                            idSiswaInput.value = data.id_siswa;
                            namaSpan.textContent = data.nama;
                            nisnSpan.textContent = data.nisn;
                            kelasSpan.textContent = `${data.kelas ?? '-'} ${data.jurusan ?? ''}`;

                            infoBox.classList.remove('hidden');
                            statusDot.classList.remove('bg-yellow-400', 'bg-red-500');
                            statusDot.classList.add('bg-green-500');
                            statusText.textContent = 'Kartu dikenali';
                        } else {
                            throw new Error('Data siswa tidak ditemukan');
                        }
                    } catch (err) {
                        idSiswaInput.value = '';
                        infoBox.classList.add('hidden');
                        statusDot.classList.remove('bg-yellow-400', 'bg-green-500');
                        statusDot.classList.add('bg-red-500');
                        statusText.textContent = 'Kartu tidak terdaftar';
                    }

                    // kosongkan input setelah 1 detik
                    setTimeout(() => rfidInput.value = '', 1000);
                }
            });
        });
        document.getElementById('izin-pulang-form').addEventListener('submit', function(e) {
            const idSiswa = document.getElementById('rfid_input').value;
            if (!idSiswa) {
                e.preventDefault();
                alert('Silakan scan kartu RFID siswa terlebih dahulu sebelum membuat izin pulang.');
            }
        });
    </script>
    @error('id_siswa')
        <p class="mt-2 text-sm text-red-600">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror

@endpush
