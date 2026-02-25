@extends('layouts.pelanggaran')
@section('page-title', 'Data Siswa')
@section('page-description', 'Riwayat Pelanggaran Siswa')
@section('content')
    {{-- halaman riwayat pelanggaran --}}
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Poin Siswa</h1>
        <div class="display:flex justify-between items-center mb-4">
            <form method="GET" action="{{ route('pelanggaran.riwayat') }}" class="flex gap-2">
                <input type="text" name="search" placeholder="Cari siswa..." value="{{ request('search') }}"
                    class="p-2 border border-gray-300 rounded w-full">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Cari</button>
            </form>
        </div>
        {{-- card siswa --}}
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach ($siswa as $s)
                <a class="bg-white shadow-md rounded-lg p-4" href="{{ route('pelanggaran.riwayat.detail', $s->nama) }}">
                    @php
                        $fotoSrc = $s->foto
                            ? asset('storage/foto/' . $s->foto)
                            : asset(
                                'storage/foto/' .
                                    ($s->nama == 'FIKRI MUAFI' ? 'fikri.jpeg' : 'siswa' . ($loop->index + 1) . '.jpg'),
                            );
                    @endphp
                    <img src="{{ $fotoSrc }}" alt="Foto Profil"
                        class="w-24 h-24 mx-auto mb-4 object-cover object-top transition-transform duration-300 hover:scale-110">
                    <h2 class="text-md font-bold mb-2">{{ $s->nama }}</h2>
                    <p class="mb-1">Kelas: {{ $s->kelas->nama_kelas ?? 'N/A' }}</p>
                    <p class="text-red-600 font-semibold">Poin: {{ $s->total_poin }}</p>
                </a>
            @endforeach
        </div>

        {{-- PAGINATION CONTROLS --}}
        <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow mt-6">
            <div class="flex items-center gap-2">
                <label for="per_page" class="text-sm font-medium text-gray-700">Tampilkan:</label>
                <select id="per_page" name="per_page" onchange="changePerPage(this.value)"
                    class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10
                    </option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span class="text-sm text-gray-600">per halaman</span>
            </div>

            <div class="text-sm text-gray-600 ">
                Menampilkan {{ $siswa->firstItem() ?? 0 }} sampai {{ $siswa->lastItem() ?? 0 }}
                dari {{ $siswa->total() }} data
            </div>
        </div>

        {{-- PAGINATION LINKS --}}
        <div class="bg-white p-4 rounded-lg shadow mt-4">
            {{ $siswa->appends(request()->query())->links() }}
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
