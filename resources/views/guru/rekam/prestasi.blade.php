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

  
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Rekam Prestasi Siswa</h1>
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

    <form id="prestasiForm" action="{{ route('guru.prestasi.store') }}" method="POST" enctype="multipart/form-data"
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            // attach handler to the specific prestasi form
            const formEl = document.getElementById('prestasiForm');
            if (!formEl) return;

            formEl.addEventListener('submit', async function(e) {
                e.preventDefault();
                const confirmed = await Swal.fire({
                    title: 'Sudah benar semua?',
                    text: 'Periksa data dan bukti. Apakah sudah sesuai?',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, simpan',
                    reverseButtons: true
                });

                if (!confirmed.isConfirmed) return;

                // show progress swal with bar
                Swal.fire({
                    title: 'Mengunggah...',
                    html: '<div style="margin-top:8px"><div id="uploadBarP" style="width:100%;height:12px;background:#eee;border-radius:6px;"><div id="uploadBarFillP" style="width:0%;height:100%;background:#34d399;border-radius:6px;"></div></div><div id="uploadPercentP" style="margin-top:8px;font-size:13px;color:#444">0%</div></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const fd = new FormData(formEl);

                    await new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', formEl.action);
                        xhr.withCredentials = true;
                        xhr.upload.onprogress = function(e) {
                            if (e.lengthComputable) {
                                const pct = Math.round((e.loaded / e.total) * 100);
                                const fill = document.getElementById('uploadBarFillP');
                                const pctEl = document.getElementById('uploadPercentP');
                                if (fill) fill.style.width = pct + '%';
                                if (pctEl) pctEl.textContent = pct + '%';
                            }
                        };
                        xhr.onload = function() {
                            let text = xhr.responseText || '';
                            let data = null;
                            try { data = JSON.parse(text); } catch (e) { /* ignore */ }

                            if (xhr.status >= 200 && xhr.status < 300) {
                                if (data && data.success) {
                                    Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Prestasi berhasil direkam.' });
                                    formEl.reset();
                                    document.getElementById('buktiPreview').classList.add('hidden');
                                    $('#id_siswa, #id_jenis_prestasi').val(null).trigger('change');
                                    resolve();
                                } else if (xhr.status === 422 && data && data.errors) {
                                    const msgs = Object.values(data.errors).flat().join('\n');
                                    Swal.fire({ icon: 'error', title: 'Validasi', text: msgs });
                                    reject(new Error('validation'));
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal', text: data && data.message ? data.message : 'Terjadi kesalahan saat menyimpan.' });
                                    reject(new Error('server'));
                                }
                            } else {
                                const msg = (data && data.message) ? data.message : (text || 'Terjadi kesalahan server.');
                                console.error('Server error response:', xhr.status, text);
                                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                                reject(new Error('http'));
                            }
                        };
                        xhr.onerror = function() { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan jaringan.' }); reject(new Error('network')); };
                        xhr.send(fd);
                    });
                } catch (err) {
                    // handled above
                }
            });

            // Flash fallback using session (if redirect used)
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')) });
                @endif
                @if(session('error'))
                    Swal.fire({ icon: 'error', title: 'Gagal', text: @json(session('error')) });
                @endif
            });
        })();
    </script>
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

        <!-- (Tanggal dan Pembimbing diisi otomatis) -->

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

                buktiFileInput.addEventListener('change', () => handleFileSelect());

            async function compressImage(file, maxKB = 250) {
                const mimeType = 'image/jpeg';
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = new Image();
                        img.onload = async () => {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            let [w, h] = [img.width, img.height];
                            const maxDim = 1600;
                            if (w > maxDim || h > maxDim) {
                                const ratio = Math.max(w / maxDim, h / maxDim);
                                w = Math.round(w / ratio);
                                h = Math.round(h / ratio);
                            }
                            canvas.width = w;
                            canvas.height = h;
                            ctx.drawImage(img, 0, 0, w, h);
                            let quality = 0.92;
                            const minQuality = 0.45;
                            const targetBytes = maxKB * 1024;
                            async function tryExport() { return new Promise(res => canvas.toBlob(res, mimeType, quality)); }
                            let blob = await tryExport();
                            while (blob && blob.size > targetBytes && quality > minQuality) {
                                quality -= 0.08;
                                blob = await tryExport();
                            }
                            while (blob && blob.size > targetBytes && (canvas.width > 400 || canvas.height > 400)) {
                                const newW = Math.round(canvas.width * 0.8);
                                const newH = Math.round(canvas.height * 0.8);
                                const tmpCanvas = document.createElement('canvas');
                                tmpCanvas.width = newW; tmpCanvas.height = newH;
                                const tctx = tmpCanvas.getContext('2d');
                                tctx.drawImage(canvas, 0, 0, newW, newH);
                                canvas.width = newW; canvas.height = newH;
                                ctx.clearRect(0,0,canvas.width,canvas.height);
                                ctx.drawImage(tmpCanvas, 0,0);
                                blob = await tryExport();
                                if (blob && blob.size > targetBytes && quality > minQuality) {
                                    quality = Math.max(minQuality, quality - 0.05);
                                    blob = await tryExport();
                                }
                            }
                            if (!blob) { resolve(file); return; }
                            if (blob.size > file.size) { resolve(file); return; }
                            try { const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, '') + '.jpg', { type: mimeType }); resolve(newFile); }
                            catch (e) { resolve(blob); }
                        };
                        img.onerror = () => resolve(file);
                        img.src = e.target.result;
                    };
                    reader.onerror = () => resolve(file);
                    reader.readAsDataURL(file);
                });
            }

            async function handleFileSelect() {
                if (buktiFileInput.files && buktiFileInput.files[0]) {
                    const originalFile = buktiFileInput.files[0];
                    const compressed = await compressImage(originalFile, 250);
                    const dt = new DataTransfer();
                    if (compressed instanceof Blob && !(compressed instanceof File)) {
                        const fileName = originalFile.name.replace(/\.[^/.]+$/, '') + '.jpg';
                        dt.items.add(new File([compressed], fileName, { type: 'image/jpeg' }));
                    } else { dt.items.add(compressed); }
                    buktiFileInput.files = dt.files;
                    const previewUrl = URL.createObjectURL(buktiFileInput.files[0]);
                    previewImageBukti.src = previewUrl;
                    buktiPreview.classList.remove('hidden');
                }
            }

            removeBtn.addEventListener('click', () => {
                buktiFileInput.value = '';
                buktiPreview.classList.add('hidden');
            });
        });
    </script>
@endpush
