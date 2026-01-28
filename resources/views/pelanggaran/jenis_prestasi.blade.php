@extends('layouts.pelanggaran')

@section('title', 'Kelola Jenis Prestasi')
@section('page-title', 'Kelola Jenis Prestasi')
@section('page-description', 'Tambah, edit, dan hapus jenis prestasi')

@section('content')
<div class="space-y-6">
    <!-- Alert -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Tambah Jenis Prestasi -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambah Jenis Prestasi Baru</h2>
        <form action="{{ route('pelanggaran.jenis-prestasi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama_prestasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Prestasi <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama_prestasi"
                    name="nama_prestasi"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_prestasi') border-red-500 @enderror"
                    placeholder="Contoh: Juara 1 Sekolah"
                    value="{{ old('nama_prestasi') }}"
                    required
                >
                @error('nama_prestasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="poin_prestasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Poin Prestasi <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="poin_prestasi"
                    name="poin_prestasi"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('poin_prestasi') border-red-500 @enderror"
                    placeholder="Contoh: 10"
                    value="{{ old('poin_prestasi') }}"
                    min="1"
                    required
                >
                @error('poin_prestasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan (Opsional)
                </label>
                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Deskripsi singkat tentang prestasi ini..."
                >{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a
                    href="{{ route('pelanggaran.index') }}"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors font-medium"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Jenis Prestasi -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Daftar Jenis Prestasi</h2>

        @if ($jenisPrestasi->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">Belum ada jenis prestasi</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Prestasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Poin</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Keterangan</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($jenisPrestasi as $index => $jenis)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $jenis->nama_prestasi }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                        {{ $jenis->poin_prestasi }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $jenis->keterangan ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        onclick="editPrestasi({{ $jenis->id }}, '{{ addslashes($jenis->nama_prestasi) }}', {{ $jenis->poin_prestasi }}, '{{ addslashes($jenis->keterangan ?? '') }}')"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors text-sm mr-2"
                                    >
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button
                                        onclick="deletePrestasi({{ $jenis->id }})"
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors text-sm"
                                    >
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Edit Jenis Prestasi</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Prestasi
                    </label>
                    <input
                        type="text"
                        id="edit_nama"
                        name="nama_prestasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <div>
                    <label for="edit_poin" class="block text-sm font-medium text-gray-700 mb-2">
                        Poin Prestasi
                    </label>
                    <input
                        type="number"
                        id="edit_poin"
                        name="poin_prestasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        min="1"
                        required
                    >
                </div>

                <div>
                    <label for="edit_keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea
                        id="edit_keterangan"
                        name="keterangan"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex-1 px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors font-medium"
                >
                    <i class="fas fa-times mr-2"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function editPrestasi(id, nama, poin, keterangan) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_poin').value = poin;
    document.getElementById('edit_keterangan').value = keterangan;

    const form = document.getElementById('editForm');
    form.action = `/pelanggaran/jenis-prestasi/${id}`;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deletePrestasi(id) {
    Swal.fire({
        title: 'Hapus Jenis Prestasi?',
        text: 'Data akan dihapus secara permanen. Pastikan tidak ada record yang menggunakan jenis prestasi ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pelanggaran/jenis-prestasi/${id}`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
