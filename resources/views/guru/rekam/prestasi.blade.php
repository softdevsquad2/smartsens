@extends('layouts.app')

@section('title', 'Rekam Prestasi - SmartSens')
@section('page-title', 'Rekam Prestasi Siswa')
@section('page-description', 'Form pencatatan prestasi siswa')

@section('sidebar')
    <!-- Dashboard -->
    <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <!-- Daftar Siswa -->
    <a href="{{ route('guru.siswa.index') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-users"></i>
        <span>Daftar Siswa</span>
    </a>

    <!-- Absensi Hari Ini -->
    <a href="{{ route('guru.absensi.hari-ini') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-day"></i>
        <span>Absensi Hari Ini</span>
    </a>

    <!-- Laporan Absensi -->
    <a href="{{ route('guru.absensi.laporan') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chart-bar"></i>
        <span>Laporan Absensi</span>
    </a>

    <!-- Logout -->
    <a href="{{ route('logout') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors mt-auto">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Rekam Prestasi Siswa</h1>
        <p class="mt-1 text-sm text-gray-600">Kelas {{ $kelas->nama_kelas }}</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-red-800 mb-2">Terjadi Kesalahan:</h3>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.prestasi.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
        @csrf

        <!-- Pilih Siswa -->
        <div class="mb-6">
            <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa <span
                    class="text-red-600">*</span></label>
            <select id="id_siswa" name="id_siswa" class="searchable-select w-full" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                        {{ $s->nama }} ({{ $s->nisn }})
                    </option>
                @endforeach
            </select>
            @error('id_siswa')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis Prestasi -->
        <div class="mb-6">
            <label for="id_jenis_prestasi" class="block text-sm font-medium text-gray-700 mb-2">Jenis Prestasi <span
                    class="text-red-600">*</span></label>
            <select id="id_jenis_prestasi" name="id_jenis_prestasi" class="searchable-select w-full" required>
                <option value="">-- Pilih Jenis Prestasi --</option>
                @foreach ($jenisPrestasi as $jp)
                    <option value="{{ $jp->id }}" {{ old('id_jenis_prestasi') == $jp->id ? 'selected' : '' }}>
                        {{ $jp->nama_prestasi }} ({{ $jp->poin_prestasi }} poin)
                    </option>
                @endforeach
            </select>
            @error('id_jenis_prestasi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Prestasi -->
        <div class="mb-6">
            <label for="tanggal_prestasi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Prestasi <span
                    class="text-red-600">*</span></label>
            <input type="date" id="tanggal_prestasi" name="tanggal_prestasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                value="{{ old('tanggal_prestasi', date('Y-m-d')) }}" required>
            @error('tanggal_prestasi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pembimbing -->
        <div class="mb-6">
            <label for="pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Pembimbing <span
                    class="text-red-600">*</span></label>
            <input type="text" id="pembimbing" name="pembimbing" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Nama guru/pembimbing yang mencatat prestasi" value="{{ old('pembimbing') }}" required>
            @error('pembimbing')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bukti Prestasi (Opsional) -->
        <div class="mb-6">
            <label for="bukti_prestasi" class="block text-sm font-medium text-gray-700 mb-2">Bukti Prestasi (Opsional)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-500 transition-colors"
                id="buktiDropZone">
                <input type="file" id="bukti_prestasi" name="bukti_prestasi" class="hidden" accept="image/*">
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600 font-medium">Klik atau seret file ke sini</p>
                <p class="text-sm text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF (Maks: 2MB)</p>
                <div id="buktiPreview" class="mt-4 hidden">
                    <img id="previewImageBukti" src="" alt="Preview" class="mx-auto max-h-48 rounded-lg">
                    <button type="button" id="removeBukti"
                        class="mt-2 px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">Hapus
                        File</button>
                </div>
            </div>
            @error('bukti_prestasi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Keterangan -->
        <div class="mb-6">
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
            <textarea id="keterangan" name="keterangan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan keterangan prestasi...">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('guru.rekam.pilih') }}"
                class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors text-center font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button type="submit"
                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                <i class="fas fa-save mr-2"></i>Simpan Prestasi
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#id_siswa, #id_jenis_prestasi').select2({
                placeholder: 'Pilih opsi...',
                allowClear: true,
                width: '100%'
            });

            // Handle file drop and click for Bukti
            const buktiDropZone = document.getElementById('buktiDropZone');
            const buktiFileInput = document.getElementById('bukti_prestasi');
            const buktiPreview = document.getElementById('buktiPreview');
            const previewImageBukti = document.getElementById('previewImageBukti');
            const removeBtn = document.getElementById('removeBukti');

            buktiDropZone.addEventListener('click', () => buktiFileInput.click());

            buktiDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                buktiDropZone.classList.add('border-green-500', 'bg-green-50');
            });

            buktiDropZone.addEventListener('dragleave', () => {
                buktiDropZone.classList.remove('border-green-500', 'bg-green-50');
            });

            buktiDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                buktiDropZone.classList.remove('border-green-500', 'bg-green-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    buktiFileInput.files = files;
                    handleFileSelect();
                }
            });

            buktiFileInput.addEventListener('change', handleFileSelect);

            function handleFileSelect() {
                if (buktiFileInput.files && buktiFileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImageBukti.src = e.target.result;
                        buktiPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(buktiFileInput.files[0]);
                }
            }

            removeBtn.addEventListener('click', () => {
                buktiFileInput.value = '';
                buktiPreview.classList.add('hidden');
            });
        });
    </script>
@endpush
