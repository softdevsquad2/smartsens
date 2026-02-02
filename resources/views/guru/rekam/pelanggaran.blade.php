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

    <a href="{{ route('guru.settings') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Rekam Pelanggaran Siswa</h1>
        {{-- <p class="mt-1 text-sm text-gray-600">Kelas {{ $kelas->nama_kelas }}</p> --}}
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

    <form id="pelanggaranForm" action="{{ route('guru.pelanggaran.store') }}" method="POST" enctype="multipart/form-data"
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

            <!-- (Tanggal, Pelapor, dan Kelas disembunyikan; diisi otomatis server-side) -->

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
                <p class="text-sm text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF (Maks: 2MB). Foto akan otomatis dikompres menjadi ~250KB sebelum diupload.</p>
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
        // load SweetAlert2
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            fileInput.addEventListener('change', () => handleFileSelect());

            async function compressImage(file, maxKB = 250) {
                // returns a Blob compressed to be <= maxKB (approx) or the best achievable
                const mimeType = 'image/jpeg';

                function dataURLToBlob(dataURL) {
                    const parts = dataURL.split(',');
                    const byteString = atob(parts[1]);
                    const ab = new ArrayBuffer(byteString.length);
                    const ia = new Uint8Array(ab);
                    for (let i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);
                    return new Blob([ab], { type: mimeType });
                }

                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = new Image();
                        img.onload = async () => {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            // initial dimensions
                            let [w, h] = [img.width, img.height];
                            const maxDim = 1600; // cap for large images
                            if (w > maxDim || h > maxDim) {
                                const ratio = Math.max(w / maxDim, h / maxDim);
                                w = Math.round(w / ratio);
                                h = Math.round(h / ratio);
                            }

                            canvas.width = w;
                            canvas.height = h;
                            ctx.drawImage(img, 0, 0, w, h);

                            // try decreasing quality first
                            let quality = 0.92;
                            const minQuality = 0.45;
                            const targetBytes = maxKB * 1024;

                            async function tryExport() {
                                return new Promise(res => canvas.toBlob(res, mimeType, quality));
                            }

                            let blob = await tryExport();
                            // reduce quality until size ok or quality too low
                            while (blob && blob.size > targetBytes && quality > minQuality) {
                                quality -= 0.08;
                                blob = await tryExport();
                            }

                            // if still too big, progressively downscale canvas
                            while (blob && blob.size > targetBytes && (canvas.width > 400 || canvas.height > 400)) {
                                // reduce dimensions by 80%
                                const newW = Math.round(canvas.width * 0.8);
                                const newH = Math.round(canvas.height * 0.8);
                                const tmpCanvas = document.createElement('canvas');
                                tmpCanvas.width = newW;
                                tmpCanvas.height = newH;
                                const tctx = tmpCanvas.getContext('2d');
                                tctx.drawImage(canvas, 0, 0, newW, newH);
                                canvas.width = newW;
                                canvas.height = newH;
                                ctx.clearRect(0,0,canvas.width,canvas.height);
                                ctx.drawImage(tmpCanvas, 0,0);
                                // try export again from current quality
                                blob = await tryExport();
                                // if not enough, lower quality further a bit
                                if (blob && blob.size > targetBytes && quality > minQuality) {
                                    quality = Math.max(minQuality, quality - 0.05);
                                    blob = await tryExport();
                                }
                            }

                            // if compression failed, fallback to original file
                            if (!blob) {
                                resolve(file);
                                return;
                            }

                            if (blob.size > file.size) {
                                // compression made it bigger for some reason, keep original
                                resolve(file);
                                return;
                            }

                            // create a File from blob so it can be sent via form
                            try {
                                const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, '') + '.jpg', { type: mimeType });
                                resolve(newFile);
                            } catch (e) {
                                // older browsers fallback
                                const newBlob = blob;
                                resolve(newBlob);
                            }
                        };
                        img.onerror = () => resolve(file);
                        img.src = e.target.result;
                    };
                    reader.onerror = () => resolve(file);
                    reader.readAsDataURL(file);
                });
            }

            async function handleFileSelect() {
                if (fileInput.files && fileInput.files[0]) {
                    const originalFile = fileInput.files[0];
                    // compress to ~250KB
                    const compressed = await compressImage(originalFile, 250);

                    // put compressed file into the file input
                    const dataTransfer = new DataTransfer();
                    if (compressed instanceof Blob && !(compressed instanceof File)) {
                        const fileName = originalFile.name.replace(/\.[^/.]+$/, '') + '.jpg';
                        dataTransfer.items.add(new File([compressed], fileName, { type: 'image/jpeg' }));
                    } else {
                        dataTransfer.items.add(compressed);
                    }
                    fileInput.files = dataTransfer.files;

                    // update preview
                    const previewUrl = URL.createObjectURL(fileInput.files[0]);
                    previewImage.src = previewUrl;
                    preview.classList.remove('hidden');
                }
            }

            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                preview.classList.add('hidden');
            });

            // Confirmation before submit using SweetAlert2
            // target the specific pelanggaran form to avoid attaching to other forms (eg. logout form)
            const formEl = document.getElementById('pelanggaranForm');
            if (formEl) {
                formEl.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const confirmed = await Swal.fire({
                        title: 'Sudah benar semua?',
                        text: 'Periksa data dan foto. Apakah sudah sesuai?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    });

                    if (!confirmed.isConfirmed) return;

                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    try {
                        const fd = new FormData(formEl);

                        // show progress swal with bar
                        Swal.fire({
                            title: 'Mengunggah...',
                            html: '<div style="margin-top:8px"><div id="uploadBar" style="width:100%;height:12px;background:#eee;border-radius:6px;"><div id="uploadBarFill" style="width:0%;height:100%;background:#34d399;border-radius:6px;"></div></div><div id="uploadPercent" style="margin-top:8px;font-size:13px;color:#444">0%</div></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        await new Promise((resolve, reject) => {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', formEl.action);
                            xhr.withCredentials = true;
                            xhr.upload.onprogress = function(e) {
                                if (e.lengthComputable) {
                                    const pct = Math.round((e.loaded / e.total) * 100);
                                    const fill = document.getElementById('uploadBarFill');
                                    const pctEl = document.getElementById('uploadPercent');
                                    if (fill) fill.style.width = pct + '%';
                                    if (pctEl) pctEl.textContent = pct + '%';
                                }
                            };
                            xhr.onload = function() {
                                // try to parse server response even when status is an error to show meaningful message
                                let text = xhr.responseText || '';
                                let data = null;
                                try { data = JSON.parse(text); } catch (e) { /* ignore parse error */ }

                                if (xhr.status >= 200 && xhr.status < 300) {
                                    if (data && data.success) {
                                        Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Pelanggaran berhasil direkam.' });
                                        formEl.reset();
                                        preview.classList.add('hidden');
                                        $('#id_siswa').val(null).trigger('change');
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
                                    // non-2xx status: prefer server-provided message, otherwise show generic and log response
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
                        // already handled above
                    }
                });
            }
        });
    </script>
    {{-- Show store status via SweetAlert (flash messages) --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success'))
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error'))
                });
            });
        </script>
    @endif
@endpush
