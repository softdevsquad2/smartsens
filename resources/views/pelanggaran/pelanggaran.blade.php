@extends('layouts.pelanggaran')
@section('page-title', 'Data Pelanggaran')
@section('page-description', 'Kelola Jenis Pelanggaran Siswa')

@section('content')
<div class="p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Data Pelanggaran</h1>

        <button
    onclick="openTambahModal()"
    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">
    + Tambah Pelanggaran
</button>

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr class="text-center font-semibold">
                    <th class="px-4 py-3 border-b">No</th>
                    <th class="px-4 py-3 border-b text-left">Jenis Pelanggaran</th>
                    <th class="px-4 py-3 border-b">Poin</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($pelanggaran as $index => $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $p->nama_pelanggaran }}</td>
                    <td class="px-4 py-3 text-center font-semibold text-red-600">{{ $p->poin_pelanggaran }}</td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button
                            onclick="openEditModal('{{ $p->nama_pelanggaran }}', {{ $p->poin_pelanggaran }}, {{ $p->id }})"
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md transition">
                            Edit
                        </button>
                        <button onclick="hapusPelanggaran({{ $p->id }})"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION CONTROLS --}}
    <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow">
        <div class="flex items-center gap-2">
            <label for="per_page" class="text-sm font-medium text-gray-700">Tampilkan:</label>
            <select id="per_page" name="per_page" onchange="changePerPage(this.value)"
                class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <span class="text-sm text-gray-600">per halaman</span>
        </div>

        <div class="text-sm text-gray-600">
            Menampilkan {{ $pelanggaran->firstItem() ?? 0 }} sampai {{ $pelanggaran->lastItem() ?? 0 }}
            dari {{ $pelanggaran->total() }} data
        </div>
    </div>

    {{-- PAGINATION LINKS --}}
    <div class="bg-white p-4 rounded-lg shadow">
        {{ $pelanggaran->appends(request()->query())->links() }}
    </div>

</div>
<!-- MODAL TAMBAH -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4">Tambah Pelanggaran</h2>

        <form action="{{ route('pelanggaran.pelanggaran.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-medium">Jenis Pelanggaran</label>
                <input type="text" name="nama_pelanggaran"
                    class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div>
                <label class="text-sm font-medium">Poin</label>
                <input type="number" name="poin_pelanggaran"
                    class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeTambahModal()"
                    class="px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>


<!-- MODAL EDIT -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-bold mb-4">Edit Pelanggaran</h2>

        <form action="#" method="POST" class="space-y-4" id="editForm">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-medium">Jenis Pelanggaran</label>
                <input type="text" id="editJenis" name="nama_pelanggaran"
                    class="w-full border rounded-md px-3 py-2" required>
            </div>

            <div>
                <label class="text-sm font-medium">Poin</label>
                <input type="number" id="editPoin" name="poin_pelanggaran"
                    class="w-full border rounded-md px-3 py-2" required>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 rounded-md bg-gray-300">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-yellow-500 text-white hover:bg-yellow-600">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to page 1 when changing per_page
        window.location.href = url.toString();
    }

    function hapusPelanggaran(id) {
        Swal.fire({
            title: 'Yakin?',
            text: 'Data pelanggaran akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pelanggaran/pelanggaran/${id}`;

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';

                form.appendChild(methodField);
                form.appendChild(csrfField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function openTambahModal() {
        document.getElementById('modalTambah').classList.remove('hidden');
        document.getElementById('modalTambah').classList.add('flex');
    }

    function closeTambahModal() {
        document.getElementById('modalTambah').classList.add('hidden');
    }

    function openEditModal(jenis, poin, id) {
        document.getElementById('editJenis').value = jenis;
        document.getElementById('editPoin').value = poin;
        document.getElementById('editForm').action = `/pelanggaran/pelanggaran/${id}`;

        document.getElementById('modalEdit').classList.remove('hidden');
        document.getElementById('modalEdit').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }
</script>
@endpush
