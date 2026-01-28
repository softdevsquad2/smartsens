@extends('layouts.app')

@section('title', 'Rekam Pelanggaran - SmartSens')
@section('page-title', 'Rekam Pelanggaran Siswa')
@section('page-description', 'Form pencatatan pelanggaran siswa dengan foto')

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
        <h1 class="text-2xl font-bold text-gray-900">Rekam Pelanggaran Siswa</h1>
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

    <form action="{{ route('guru.pelanggaran.store') }}" method="POST" enctype="multipart/form-data"
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

        <!-- Tanggal Pelanggaran -->
        <div class="mb-6">
            <label for="tanggal_pelanggaran" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pelanggaran <span
                    class="text-red-600">*</span></label>
            <input type="date" id="tanggal_pelanggaran" name="tanggal_pelanggaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('tanggal_pelanggaran', date('Y-m-d')) }}" required>
            @error('tanggal_pelanggaran')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pelapor -->
        <div class="mb-6">
            <label for="pelapor" class="block text-sm font-medium text-gray-700 mb-2">Pelapor <span
                    class="text-red-600">*</span></label>
            <input type="text" id="pelapor" name="pelapor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nama atau identitas yang melaporkan" value="{{ old('pelapor') }}" required>
            @error('pelapor')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Foto Pelanggaran -->
        <div class="mb-6">
            <label for="foto_pelanggaran" class="block text-sm font-medium text-gray-700 mb-2">Foto Pelanggaran <span
                    class="text-red-600">*</span></label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition-colors"
                id="fotoDropZone">
                <input type="file" id="foto_pelanggaran" name="foto_pelanggaran" class="hidden" accept="image/*"
                    required>
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600 font-medium">Klik atau seret foto ke sini</p>
                <p class="text-sm text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF (Maks: 2MB)</p>
                <div id="fotoPreview" class="mt-4 hidden">
                    <img id="previewImage" src="" alt="Preview" class="mx-auto max-h-48 rounded-lg">
                    <button type="button" id="removeFoto"
                        class="mt-2 px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">Hapus
                        Foto</button>
                </div>
            </div>
            @error('foto_pelanggaran')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis Pelanggaran -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Pelanggaran <span
                    class="text-red-600">*</span></label>
            <div class="space-y-3 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                @forelse ($pelanggaran as $p)
                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="pelanggaran[]" value="{{ $p->id }}"
                            {{ in_array($p->id, old('pelanggaran', [])) ? 'checked' : '' }}
                            class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-900">{{ $p->nama_pelanggaran }}</p>
                            <p class="text-sm text-gray-600">{{ $p->poin_pelanggaran }} poin</p>
                        </div>
                    </label>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada data pelanggaran</p>
                @endforelse
            </div>
            @error('pelanggaran')
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
                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                <i class="fas fa-save mr-2"></i>Simpan Pelanggaran
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#id_siswa').select2({
                placeholder: 'Cari nama atau NISN siswa...',
                allowClear: true,
                width: '100%'
            });

            // Handle file drop and click
            const dropZone = document.getElementById('fotoDropZone');
            const fileInput = document.getElementById('foto_pelanggaran');
            const preview = document.getElementById('fotoPreview');
            const previewImage = document.getElementById('previewImage');
            const removeBtn = document.getElementById('removeFoto');

            dropZone.addEventListener('click', () => fileInput.click());

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect();
                }
            });

            fileInput.addEventListener('change', handleFileSelect);

            function handleFileSelect() {
                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImage.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }

            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                preview.classList.add('hidden');
            });
        });
    </script>
@endpush
