@extends('layouts.app')

@section('title', 'Kelola Guru - SmartSens')
@section('page-title', 'Kelola Guru')
@section('page-description', 'Kelola data guru')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Guru</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola data guru di sekolah</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.walikelas.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Tambah Guru
            </a>
            <button command="show-modal"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-upload mr-2"></i>
                Import Data
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

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

    @if (session('import_errors'))
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Peringatan Import</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach (session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Wali Kelas Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <form method="GET" class="p-4 flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <label for="search" class="text-sm text-gray-700 mr-2">Cari:</label>
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                        placeholder="Cari nama guru atau NIP..."
                        class="border border-gray-300 px-3 py-1 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-center">
                    <label for="per_page" class="text-sm text-gray-700 mr-2">Tampilkan</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()"
                        class="border border-gray-300 px-2 py-1 rounded">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data per halaman</span>
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-1 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-1"></i>Cari
                </button>
                @if ($search ?? '')
                    <a href="{{ route('admin.walikelas.index') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                @endif
            </form>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Guru
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($waliKelas as $index => $wk)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $waliKelas->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $wk->nama }}</div>
                                        @if ($wk->user)
                                            <div class="text-xs text-gray-500">User: {{ $wk->user->username }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $wk->nip ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-6 h-6 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-chalkboard text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $wk->kelas->nama_kelas ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $wk->kelas->jurusan->nama_jurusan ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($wk->user)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Belum ada user
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.walikelas.show', $wk) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.walikelas.edit', $wk) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.walikelas.destroy', $wk) }}" method="POST"
                                        class="inline delete-form"
                                        onsubmit="return confirmDelete('Yakin ingin menghapus guru ini?', 'Konfirmasi Hapus Guru')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-user-tie text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada guru</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan guru pertama</p>
                                    <a href="{{ route('admin.walikelas.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Guru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($waliKelas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $waliKelas->links() }}
            </div>
        @endif
    </div>

    {{-- Import Modal --}}
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-upload text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Import Guru</h3>
                </div>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.walikelas.import') }}" method="POST" enctype="multipart/form-data"
                id="importForm">
                @csrf

                <!-- File Upload Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Excel (.xlsx)
                    </label>
                    <div id="dropZone"
                        class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors cursor-pointer">
                        <input type="file" name="file" accept=".xlsx" id="fileInput"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <div class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600 mb-1">Klik untuk memilih file atau seret file ke sini</p>
                            <p class="text-xs text-gray-500">Format: .xlsx (Maksimal 3000 baris)</p>
                        </div>
                    </div>
                    <div id="fileInfo" class="mt-2 text-sm text-green-600 hidden">
                        <i class="fas fa-check-circle mr-1"></i>
                        File dipilih: <span id="fileName"></span>
                    </div>
                </div>

                <!-- Template Download -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-400 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Template Import</h4>
                            <p class="text-xs text-blue-700 mb-2">
                                Pastikan format file sesuai dengan template untuk menghindari kesalahan import.
                            </p>
                            <a href="{{ route('admin.walikelas.template') }}"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Download Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Warning -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-xs text-yellow-700">
                                <strong>Penting:</strong> Data yang diimport akan menimpa data yang sudah ada jika ada
                                duplikasi.
                                Pastikan file sudah benar sebelum mengupload.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelBtn"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-upload mr-2"></i>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Safe modal + file input handling for Import Wali Kelas
        (function() {
            const modal = document.getElementById('importModal');
            const openButtons = document.querySelectorAll('[command="show-modal"]');
            const closeButton = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const fileInput = document.getElementById('fileInput');
            const fileInfo = document.getElementById('fileInfo');
            const fileNameSpan = document.getElementById('fileName');
            const submitBtn = document.getElementById('submitBtn');
            const dropZone = document.getElementById('dropZone');

            if (!modal) return; // nothing to do

            // Open modal
            openButtons.forEach(btn => btn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                // reset file UI
                if (fileInput) {
                    fileInput.value = '';
                }
                if (fileInfo) fileInfo.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = true;
                // trap body scroll while modal is open
                document.body.classList.add('overflow-hidden');
            }));

            // Close modal helper
            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Close via close button
            if (closeButton) closeButton.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

            // Close when clicking overlay (but not when clicking inside dialog content)
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // File input handling
            if (fileInput) {
                // initialize submit button state
                if (submitBtn) submitBtn.disabled = true;

                fileInput.addEventListener('change', (e) => {
                    const f = e.target.files && e.target.files[0];
                    if (f) {
                        if (fileNameSpan) fileNameSpan.textContent = f.name;
                        if (fileInfo) fileInfo.classList.remove('hidden');
                        if (submitBtn) submitBtn.disabled = false;
                    } else {
                        if (fileInfo) fileInfo.classList.add('hidden');
                        if (submitBtn) submitBtn.disabled = true;
                    }
                });
            }

            // Drag & drop support for dropZone
            if (dropZone && fileInput) {
                ['dragenter', 'dragover'].forEach(evt => {
                    dropZone.addEventListener(evt, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.add('border-green-400');
                    });
                });

                ['dragleave', 'dragend', 'drop'].forEach(evt => {
                    dropZone.addEventListener(evt, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.remove('border-green-400');
                    });
                });

                dropZone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    if (dt && dt.files && dt.files.length) {
                        fileInput.files = dt.files;
                        const ev = new Event('change', {
                            bubbles: true
                        });
                        fileInput.dispatchEvent(ev);
                    }
                });
            }
        })();
    </script>
@endsection
