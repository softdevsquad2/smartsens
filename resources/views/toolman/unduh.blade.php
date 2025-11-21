@extends('layouts.toolman')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
    <div class="overflow-x-auto">

        <div class="flex items-center gap-3 mb-4">
            <input type="text" id="searchInput2" placeholder="Cari nama siswa..."
                class="border p-2 rounded w-1/3 focus:ring focus:ring-blue-300">

            {{-- FILTER KELAS --}}
            <select id="filterKelas" class="border p-2 rounded">
                <option value="">Semua Kelas</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            {{-- FILTER BULAN --}}
            <input type="month" id="filterBulan" value="{{ request('bulan') }}" class="border p-2 rounded">

            {{-- TOMBOL EXPORT --}}
            <a id="exportExcel" href="#" class="px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600">
                Export Excel
            </a>

            <a id="exportPdf" href="#" class="px-4 py-2 bg-red-500 text-white rounded shadow hover:bg-red-600">
                Export PDF
            </a>

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
                            @if ($p->status == 'dipinjam')
                                <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-sm">Dipinjam</span>
                            @elseif($p->status == 'dikembalikan')
                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-sm">Dikembalikan</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-sm">{{ $p->status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    <script>
        // Ketika filter berubah → reload halaman dengan query baru
        function applyFilter() {
            let kelas = document.getElementById('filterKelas').value;
            let bulan = document.getElementById('filterBulan').value;

            let url = new URL(window.location.href);
            if (kelas) url.searchParams.set('kelas', kelas);
            else url.searchParams.delete('kelas');

            if (bulan) url.searchParams.set('bulan', bulan);
            else url.searchParams.delete('bulan');

            window.location.href = url.toString();
        }

        document.getElementById('filterKelas').addEventListener('change', applyFilter);
        document.getElementById('filterBulan').addEventListener('change', applyFilter);


        // EXPORT EXCEL & PDF
        document.getElementById('exportExcel').addEventListener('click', function() {
            let kelas = document.getElementById('filterKelas').value;
            let bulan = document.getElementById('filterBulan').value;

            let url = new URL("{{ route('peminjaman.excel') }}");
            if (kelas) url.searchParams.set('kelas', kelas);
            if (bulan) url.searchParams.set('bulan', bulan);

            window.location.href = url.toString();
        });

        document.getElementById('exportPdf').addEventListener('click', function() {
            let kelas = document.getElementById('filterKelas').value;
            let bulan = document.getElementById('filterBulan').value;

            let url = new URL("{{ route('peminjaman.pdf') }}");
            if (kelas) url.searchParams.set('kelas', kelas);
            if (bulan) url.searchParams.set('bulan', bulan);

            window.location.href = url.toString();
        });
    </script>

@endsection
