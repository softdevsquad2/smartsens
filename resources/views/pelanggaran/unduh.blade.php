@extends('layouts.pelanggaran')
@section('page-title', 'Unduh Laporan ')
@section('page-description', 'Unduh Laporan Siswa dalam Berbagai Format')
@section('content')
    <div class="space-y-6">

        {{-- HEADER & BUTTON EXPORT --}}


        {{-- FILTER --}}
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('pelanggaran.unduh') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                <div>
                    <label class="text-sm font-medium text-gray-600">Kelas</label>
                    <select name="kelas"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- <div>
                <label class="text-sm font-medium text-gray-600">Jenis Pelanggaran</label>
                <select name="jenis"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                    <option value="">Pilih Jenis</option>
                    @foreach ($jenisPelanggaran as $jp)
                    <option value="{{ $jp->id_pelanggaran }}" {{ request('jenis') == $jp->id_pelanggaran ? 'selected' : '' }}>{{ $jp->nama_pelanggaran }}</option>
                    @endforeach
                </select>
            </div> --}}

                <div>
                    <label class="text-sm font-medium text-gray-600">Tanggal</label>
                    <input type="date" name="tanggal"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <button
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>


                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">


                    <div class="flex gap-3">
                        <a href="{{ route('pelanggaran.unduh.pdf', request()->query()) }}"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a href="{{ route('pelanggaran.unduh.excel', request()->query()) }}"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr class="text-center font-semibold">
                        <th class="px-4 py-3 border-b">No</th>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">NIS</th>
                        <th class="px-4 py-3 border-b text-left">Nama Siswa</th>
                        <th class="px-4 py-3 border-b">Kelas</th>
                        <th class="px-4 py-3 border-b text-left">Jenis Pelanggaran</th>
                        <th class="px-4 py-3 border-b">Poin Pelanggaran</th>
                        <th class="px-4 py-3 border-b">Poin Prestasi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($dataPelanggaran as $index => $rekam)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-center">{{ $rekam->tanggal_pelanggaran }}</td>
                            <td class="px-4 py-3 text-center">{{ $rekam->siswa->nisn ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $rekam->siswa->nama ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center">{{ $rekam->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-red-600">
                                {{ $rekam->poin_diberikan ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-green-600">
                                {{ $rekam->total_poin_prestasi ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION CONTROLS --}}
        <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow mt-6">
            <div class="flex items-center gap-2">
                <label for="per_page" class="text-sm font-medium text-gray-700">Tampilkan:</label>
                <select id="per_page" name="per_page" onchange="changePerPage(this.value)"
                    class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page') == 15 || !request('per_page') ? 'selected' : '' }}>15
                    </option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span class="text-sm text-gray-600">per halaman</span>
            </div>

            <div class="text-sm text-gray-600">
                Menampilkan {{ $dataPelanggaran->firstItem() ?? 0 }} sampai {{ $dataPelanggaran->lastItem() ?? 0 }}
                dari {{ $dataPelanggaran->total() }} data
            </div>
        </div>

        {{-- PAGINATION LINKS --}}
        <div class="bg-white p-4 rounded-lg shadow">
            {{ $dataPelanggaran->appends(request()->query())->links() }}
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
    </script>
@endpush
