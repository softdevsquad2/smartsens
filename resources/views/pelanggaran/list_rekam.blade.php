@extends('layouts.pelanggaran')
@section('page-title', 'List Rekam Pelanggaran')
@section('page-description', 'Daftar Rekam Pelanggaran Siswa')

@section('content')
<div class="p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">List Rekam Pelanggaran</h1>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr class="text-center font-semibold">
                    <th class="px-4 py-3 border-b">No</th>
                    <th class="px-4 py-3 border-b text-left">Nama Siswa</th>
                    <th class="px-4 py-3 border-b text-left">Jenis Pelanggaran</th>
                    <th class="px-4 py-3 border-b">Poin</th>
                    <th class="px-4 py-3 border-b">Pelapor</th>
                    <th class="px-4 py-3 border-b">Tanggal</th>
                    <th class="px-4 py-3 border-b">Bukti</th>
                    <th class="px-4 py-3 border-b">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($rekamPelanggaran as $index => $rekam)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $rekam->siswa->nama ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-center font-semibold text-red-600">{{ $rekam->pelanggaran->poin_pelanggaran ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-center">{{ $rekam->petugas->waliKelas->nama ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-center">{{ $rekam->tanggal_pelanggaran }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($rekam->foto_pelanggaran)
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-sm" onclick="showBukti('{{ asset('storage/' . $rekam->foto_pelanggaran) }}')">
                                <i class="fas fa-image mr-1"></i>Lihat Bukti
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">Tidak ada bukti</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="hapusRekam({{ $rekam->id }})"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- Modal Bukti --}}
<div id="buktiModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-bold">Bukti Pelanggaran</h3>
            <button onclick="closeBukti()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <img id="buktiImage" src="" alt="Bukti Pelanggaran" class="w-full max-h-96 object-contain rounded-lg">
        </div>
    </div>
</div>
<div>
    {{ $rekamPelanggaran->links() }}
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

function hapusRekam(id) {
    Swal.fire({
        title: 'Yakin?',
        text: 'Data rekam pelanggaran akan dihapus',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Hapus'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('pelanggaran/rekam') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
            });
        }
    });
}
</script>
@endpush
