@extends('layouts.pelanggaran')

@section('title', 'Kelola Prestasi Siswa')
@section('page-title', 'Kelola Prestasi Siswa')
@section('page-description', 'Edit dan hapus data prestasi siswa')

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

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Filter Data</h2>
        <form method="GET" action="{{ route('pelanggaran.prestasi.manage') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Cari Siswa
                </label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nama atau NISN..."
                    value="{{ request('search') }}"
                >
            </div>

            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal
                </label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ request('tanggal') }}"
                >
            </div>

            <div>
                <label for="id_jenis_prestasi" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Prestasi
                </label>
                <select id="id_jenis_prestasi" name="id_jenis_prestasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    @foreach ($jenisPrestasi as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('id_jenis_prestasi') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_prestasi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">No</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Siswa</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">NISN</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Jenis Prestasi</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Poin</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($prestasi as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ ($prestasi->currentPage() - 1) * $prestasi->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $item->siswa->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->siswa->nisn ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->jenisPrestasi->nama_prestasi ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                {{ $item->jenisPrestasi->poin_prestasi ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->tanggal_prestasi }}</td>
                        <td class="px-4 py-3 text-center">
                            <button
                                onclick="editPrestasi({{ $item->id }}, '{{ addslashes($item->siswa->nama ?? '') }}', {{ $item->id_siswa }}, {{ $item->id_jenis_prestasi }}, '{{ $item->tanggal_prestasi }}', '{{ addslashes($item->keterangan ?? '') }}')"
                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors text-sm mr-2"
                            >
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button
                                onclick="deletePrestasi({{ $item->id }})"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors text-sm"
                            >
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">Tidak ada data prestasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($prestasi->hasPages())
        <div class="bg-white rounded-lg shadow-md p-4">
            {{ $prestasi->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-96 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">Edit Prestasi</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="edit_siswa" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Siswa
                    </label>
                    <input
                        type="text"
                        id="edit_siswa"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                        readonly
                    >
                </div>

                <div>
                    <label for="edit_jenis_prestasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Prestasi <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="edit_jenis_prestasi"
                        name="id_jenis_prestasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Jenis Prestasi</option>
                        @foreach ($jenisPrestasi as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama_prestasi }} ({{ $jenis->poin_prestasi }} poin)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Prestasi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="edit_tanggal"
                        name="tanggal_prestasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
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
function editPrestasi(id, namaSiswa, idSiswa, idJenisPrestasi, tanggalPrestasi, keterangan) {
    document.getElementById('edit_siswa').value = namaSiswa;
    document.getElementById('edit_jenis_prestasi').value = idJenisPrestasi;
    document.getElementById('edit_tanggal').value = tanggalPrestasi;
    document.getElementById('edit_keterangan').value = keterangan;

    const form = document.getElementById('editForm');
    form.action = `/pelanggaran/prestasi/${id}`;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deletePrestasi(id) {
    Swal.fire({
        title: 'Hapus Prestasi?',
        text: 'Data prestasi akan dihapus secara permanen.',
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
            form.action = `/pelanggaran/prestasi/${id}`;

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
