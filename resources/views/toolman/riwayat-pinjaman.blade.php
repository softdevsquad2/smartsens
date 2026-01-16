@extends('layouts.toolman')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
    <div class="overflow-x-auto">
        <input type="text" id="searchInput2" placeholder="Cari nama siswa..."
            class="border p-2 rounded w-1/3 focus:ring focus:ring-blue-300">
        <div class="flex items-center space-x-2 py-4">
            <label class="text-sm text-gray-700">Tampilkan</label>
            <select id="perPageSelect2" class="border p-2 rounded">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-700">data</span>
        </div>
        <table class="min-w-full bg-white mt-3 border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">#</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Siswa</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Kelas</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Barang</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Tujuan</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($peminjamans as $index => $p)
                    <tr>
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $p->user->siswa->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->user->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->barang->nama_barang ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->tujuan ?? '-' }}</td>
                        <td class="px-6 py-4">{{ date('d-m-Y H:i:s', strtotime($p->tanggal_pinjam)) }}</td>
                        <td class="px-6 py-4">
                            {{ $p->tanggal_kembali == null ? '-' : date('d-m-Y H:i:s', strtotime($p->tanggal_kembali)) }}

                        </td>
                        <td class="px-6 py-4">
                            @if ($p->barang->jenis == 'Bahan')
                                <span class="px-2 py-1 bg-green-200 text-gray-800 rounded-full text-sm">Digunakan</span>
                            @elseif($p->status == 'dikembalikan')
                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-sm">Dikembalikan</span>
                            @elseif($p->status == 'dipinjam')
                                <span
                                    class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-sm">{{ $p->status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="m-2">
            {{ $peminjamans->links() }}
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('perPageSelect2').addEventListener('change', function() {
            let perPage = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });
        document.getElementById('searchInput2').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let namaBarang = row.cells[1].innerText.toLowerCase();
                let kodeBarang = row.cells[2].innerText.toLowerCase();

                row.style.display = (namaBarang.includes(filter) || kodeBarang.includes(filter)) ? "" :
                    "none";
            });
        });
    </script>
@endsection
