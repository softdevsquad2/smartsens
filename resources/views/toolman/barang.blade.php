@extends('layouts.toolman')

@section('title', 'Daftar Barang')
@section('page-title', 'Daftar Barang')

@section('content')
    <div class="overflow-x-auto">
        <div class="flex justify-between items-center mb-4">


            <input type="text" id="searchInput" placeholder="Cari barang..."
                class="border p-2 rounded w-1/3 focus:ring focus:ring-blue-300">

            <button class="btn bg-blue-500 border border-blue-200 p-2 text-white shadow-lg rounded-xl hover:bg-blue-700"
                onclick="openModal()">
                Tambah Barang
            </button>
        </div>


        @if (session('success'))
            <div class="mb-4 p-4 rounded bg-green-100 border border-green-400 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded bg-red-100 border border-red-400 text-red-700">
                {{ session('error') }}
            </div>
        @endif
        <div class="flex items-center space-x-2 py-3">
            <label class="text-sm text-gray-700">Tampilkan</label>
            <select id="perPageSelect" class="border p-2 rounded">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-700">data</span>
        </div>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">#</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Gambar</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Satuan</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Jenis</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Stok</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($barangs as $index => $barang)
                    <tr>
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            @if ($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                                    class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-400">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $barang->kode_barang }}</td>
                        <td class="px-6 py-4">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4">{{ $barang->satuan }}</td>
                        <td class="px-6 py-4">{{ $barang->jenis }}</td>
                        <td class="px-6 py-4">{{ $barang->stok }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <button onclick="openEditModal({{ $barang->id_barang }})"
                                class="text-blue-600 hover:underline">Edit</button>

                            <form id="deleteForm-{{ $barang->id_barang }}"
                                action="{{ route('toolman.barang.delete', $barang->id_barang) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="deleteBarang({{ $barang->id_barang }})"
                                    class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="m-2">
            {{ $barangs->links() }}
        </div>
    </div>
    <!-- Modal Edit Barang -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg w-1/3 p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Edit Barang</h2>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id">

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" id="edit_nama_barang" name="nama_barang" class="w-full border rounded p-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Kode Barang</label>
                    <input type="text" id="edit_kode_barang" name="kode_barang" class="w-full border rounded p-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Satuan</label>
                    <input type="text" id="edit_satuan" name="satuan" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Stok</label>
                    <input type="number" id="edit_stok" name="stok" class="w-full border rounded p-2" required>
                </div>
                

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Jenis</label>
                    <select id="edit_jenis" name="jenis" class="w-full border rounded p-2" required>
                        <option value="Barang">Barang</option>
                        <option value="Bahan">Bahan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Gambar (Opsional)</label>
                    <input type="file" accept=".png" name="gambar" class="w-full border rounded p-2">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div id="tambahModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg w-1/3 p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Tambah Barang</h2>
            <form id="tambahForm" action="{{ route('toolman.barang.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="nama_barang" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Kode Barang</label>
                    <input type="text" name="kode_barang" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Satuan</label>
                    <input type="text" name="satuan" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Jenis</label>
                    <select name="jenis" class="w-full border rounded p-2" required>
                        <option value="Barang">Barang</option>
                        <option value="Bahan">Bahan</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stok" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Gambar</label>
                    <input type="file" name="gambar" class="w-full border rounded p-2" accept=".png">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // ============================
        // SWEETALERT LOADING
        // ============================
        function showSwalLoading(message = "Memproses data...") {
            Swal.fire({
                title: message,
                html: '<div class="flex justify-center"><div class="loader"></div></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // =============================
        // Notifikasi Berhasil (SweetAlert)
        // =============================
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 1800,
                showConfirmButton: false
            });
        @endif

        // =============================
        // OPEN TAMBAH MODAL
        // =============================
        function openModal() {
            document.getElementById('tambahModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('tambahModal').classList.add('hidden');
        }

        // =============================
        // OPEN EDIT MODAL
        // =============================
        function openEditModal(id) {
            fetch(`/toolman/barang/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_nama_barang').value = data.nama_barang;
                    document.getElementById('edit_kode_barang').value = data.kode_barang;
                    document.getElementById('edit_satuan').value = data.satuan;
                    document.getElementById('edit_stok').value = data.stok;

                    document.getElementById('editForm').action = `/toolman/barang/${id}`;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // =============================
        // HAPUS BARANG (SweetAlert)
        // =============================
        function deleteBarang(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data barang tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm-${id}`).submit();
                    showSwalLoading("Mengupdate barang...");
                }
            });
        }



        // =============================
        // SEARCH BAR
        // =============================
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let namaBarang = row.cells[3].innerText.toLowerCase();
                let kodeBarang = row.cells[2].innerText.toLowerCase();

                row.style.display = (namaBarang.includes(filter) || kodeBarang.includes(filter)) ? "" :
                    "none";
            });
        });

        // =============================
        // PAGINASI PER PAGE
        // =============================
        document.getElementById('perPageSelect').addEventListener('change', function() {
            let perPage = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });
    </script>
    <script>
        // ============================
        // SWEETALERT LOADING
        // ============================
        function showSwalLoading(message = "Memproses data...") {
            Swal.fire({
                title: message,
                html: '<div class="flex justify-center"><div class="loader"></div></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // ============================
        // LOADING SAAT SUBMIT FORM TAMBAH & EDIT
        // ============================
        document.getElementById('tambahForm').addEventListener('submit', function() {
            showSwalLoading("Menyimpan barang...");
        });

        document.getElementById('editForm').addEventListener('submit', function() {
            showSwalLoading("Mengupdate barang...");
        });

        // ============================
        // DELETE BARANG
        // ============================
        function deleteBarang(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data barang tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    showSwalLoading("Menghapus barang...");
                    document.getElementById(`deleteForm-${id}`).submit();
                }
            });
        }
    </script>

@endsection
