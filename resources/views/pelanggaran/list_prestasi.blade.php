@extends('layouts.pelanggaran')

@section('title', 'List Prestasi - SmartSens')
@section('page-title', 'List Prestasi Siswa')
@section('page-description', 'Daftar prestasi siswa dengan bukti dan point')

@section('content')
    <!-- Filter Section -->
    <div class="mb-6 bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <form method="GET" action="{{ route('pelanggaran.prestasi.list') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                    <input type="text" name="search" placeholder="Nama atau NISN" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" value="{{ request('search') }}">
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" value="{{ request('tanggal') }}">
                </div>

                <!-- Jenis Prestasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Prestasi</label>
                    <select name="id_jenis_prestasi" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPrestasi as $jp)
                            <option value="{{ $jp->id }}" {{ request('id_jenis_prestasi') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama_prestasi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        <i class="fas fa-search mr-1"></i>Filter
                    </button>
                    <a href="{{ route('pelanggaran.prestasi.list') }}" class="flex-1 px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors text-sm font-medium text-center">
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
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-star text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Prestasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $dataPrestasi->total() }}</p>
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
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Jenis Prestasi</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Pembimbing</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Bukti</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Point</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($dataPrestasi as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $data->siswa->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $data->siswa->nisn }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    {{ $data->jenisPrestasi->nama_prestasi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($data->tanggal_prestasi)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $data->petugas->waliKelas->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($data->bukti_prestasi)
                                    <button class="text-blue-600 hover:text-blue-800 font-medium text-sm" onclick="showBukti('{{ asset('storage/' . $data->bukti_prestasi) }}')">
                                        <i class="fas fa-image mr-1"></i>Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">Tidak ada bukti</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $data->jenisPrestasi->poin_prestasi }} poin
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick="showDetail('{{ $data->siswa->nama }}', '{{ $data->jenisPrestasi->nama_prestasi }}', '{{ $data->tanggal_prestasi }}', '{{ $data->pembimbing }}', '{{ $data->jenisPrestasi->poin_prestasi }}', '{{ $data->keterangan }}')" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Tidak ada data prestasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $dataPrestasi->links() }}
    </div>

    <!-- Modal Bukti -->
    <div id="buktiModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold">Bukti Prestasi</h3>
                <button onclick="closeBukti()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <img id="buktiImage" src="" alt="Bukti Prestasi" class="w-full max-h-96 object-contain rounded-lg">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showBukti(src) {
        document.getElementById('buktiImage').src = src;
        document.getElementById('buktiModal').classList.remove('hidden');
    }

    function closeBukti() {
        document.getElementById('buktiModal').classList.add('hidden');
    }

    function showDetail(siswa, prestasi, tanggal, pembimbing, poin, keterangan) {
        const detailText = `
Siswa: ${siswa}
Prestasi: ${prestasi}
Tanggal: ${tanggal}
Pembimbing: ${pembimbing || '-'}
Point: ${poin}
${keterangan ? 'Keterangan: ' + keterangan : ''}
        `;
        alert(detailText);
    }
</script>
@endpush
