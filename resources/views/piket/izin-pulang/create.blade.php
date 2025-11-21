@extends('layouts.piket')

@section('title', 'Tambah Izin Pulang - PIKET')

@push('styles')
    <style>
        .form-control {
            transition: all 0.3s ease;
        }

        .form-control:focus {
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    @include('piket.partials.header', [
        'title' => 'Tambah Izin Pulang',
        'description' => 'Buat izin pulang baru untuk siswa',
        'icon' => 'fas fa-sign-out-alt',
        'action' => [
            'url' => route('piket.izin-pulang'),
            'text' => 'Kembali ke Daftar',
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('piket.partials.messages')

    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:border-primary-200 transition-colors">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-file-signature text-primary-600 mr-2"></i>
                    Form Izin Pulang
                </h2>
            </div>

            <form action="{{ route('piket.izin-pulang.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <!-- Siswa Selection -->
                <div>
                    <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih Siswa <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="id_siswa" id="id_siswa" required
                            class="form-control w-full rounded-lg border-gray-200 shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 @error('id_siswa') border-red-300 @enderror">
                            <option value="">Pilih Siswa...</option>
                            @foreach ($daftarSiswa as $siswa)
                                <option value="{{ $siswa->id_siswa }}"
                                    {{ (old('id_siswa') ?? ($selectedSiswa ?? '')) == $siswa->id_siswa ? 'selected' : '' }}>
                                    {{ $siswa->nama }}
                                    @if ($siswa->kelas)
                                        - {{ $siswa->kelas->nama_kelas }}
                                        @if ($siswa->kelas->jurusan)
                                            {{ $siswa->kelas->jurusan->nama_jurusan }}
                                        @endif
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('id_siswa')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Pilih siswa yang akan diberikan izin pulang</p>
                </div>

                <!-- Alasan -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                        Alasan Izin Pulang <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="4" required
                        class="form-control w-full rounded-lg border-gray-200 shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 @error('keterangan') border-red-300 @enderror"
                        placeholder="Contoh: Sakit, ada keperluan keluarga, dll">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jelaskan alasan siswa meminta izin pulang</p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('piket.izin-pulang') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Izin Pulang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
