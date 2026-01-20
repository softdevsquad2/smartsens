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
                    <th class="px-4 py-3 border-b">Tanggal</th>
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
                    <td class="px-4 py-3 text-center">{{ $rekam->tanggal_pelanggaran }}</td>
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
@endsection

@push('scripts')
<script>
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
