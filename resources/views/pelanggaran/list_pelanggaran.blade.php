@extends('layouts.pelanggaran')

@section('title', 'List Pelanggaran - SmartSens')
@section('page-title', 'List Pelanggaran Siswa')
@section('page-description', 'Daftar pelanggaran siswa dengan foto bukti')

@section('content')
    <!-- Filter Section -->
    <div class="mb-6 bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <form method="GET" action="{{ route('pelanggaran.rekam.list') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                    <input type="text" name="search" placeholder="Nama atau NISN" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('tanggal') }}">
                </div>

                <!-- Jenis Pelanggaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelanggaran</label>
                    <select name="id_pelanggaran" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPelanggaran as $jp)
                            <option value="{{ $jp->id }}" {{ request('id_pelanggaran') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama_pelanggaran }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <i class="fas fa-search mr-1"></i>Filter
                    </button>
                    <a href="{{ route('pelanggaran.rekam.list') }}" class="flex-1 px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors text-sm font-medium text-center">
                        <i class="fas fa-redo mr-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Pelanggaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $dataPelanggaran->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Siswa</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Jenis Pelanggaran</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Pelapor</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Foto</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Poin</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($dataPelanggaran as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $data->siswa->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $data->siswa->nisn }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                    {{ $data->pelanggaran->nama_pelanggaran }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($data->tanggal_pelanggaran)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $data->pelapor ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($data->foto_pelanggaran)
                                    <button class="text-blue-600 hover:text-blue-800 font-medium text-sm" onclick="showFoto('{{ asset('storage/' . $data->foto_pelanggaran) }}')">
                                        <i class="fas fa-image mr-1"></i>Lihat Foto
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">Tidak ada foto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                    {{ $data->pelanggaran->poin_pelanggaran }} poin
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick="showDetail('{{ $data->siswa->nama }}', '{{ $data->pelanggaran->nama_pelanggaran }}', '{{ $data->tanggal_pelanggaran }}', '{{ $data->pelapor }}', '{{ $data->pelanggaran->poin_pelanggaran }}')" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Tidak ada data pelanggaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $dataPelanggaran->links() }}
    </div>

    <!-- Modal Foto -->
    <div id="fotoModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold">Foto Pelanggaran</h3>
                <button onclick="closeFoto()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <img id="fotoImage" src="" alt="Foto Pelanggaran" class="w-full max-h-96 object-contain rounded-lg">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showFoto(src) {
        document.getElementById('fotoImage').src = src;
        document.getElementById('fotoModal').classList.remove('hidden');
    }

    function closeFoto() {
        document.getElementById('fotoModal').classList.add('hidden');
    }

    function showDetail(siswa, pelanggaran, tanggal, pelapor, poin) {
        const detailText = `
Siswa: ${siswa}
Pelanggaran: ${pelanggaran}
Tanggal: ${tanggal}
Pelapor: ${pelapor || '-'}
Poin: ${poin}
        `;
        alert(detailText);
    }
</script>
@endpush
