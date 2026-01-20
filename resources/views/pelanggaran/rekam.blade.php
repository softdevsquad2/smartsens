@extends('layouts.pelanggaran')
@section('page-title', 'Rekam Pelanggaran')
@section('page-description', 'Catat Pelanggaran Siswa')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Rekam Pelanggaran Siswa</h1>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 max-w-2xl">
        <form id="formPelanggaran">
            @csrf
            <div class="mb-4">
                <label for="id_siswa" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                <select id="id_siswa" name="id_siswa" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id_siswa }}">{{ $s->nama }} ({{ $s->nisn }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pelanggaran</label>
                @foreach($pelanggaran as $p)
                    <div class="flex items-center">
                        <input type="checkbox" id="pelanggaran{{ $p->id }}" name="pelanggaran[]" value="{{ $p->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="pelanggaran{{ $p->id }}" class="ml-2 block text-sm text-gray-900">
                            {{ $p->nama_pelanggaran }} ({{ $p->poin_pelanggaran }} poin)
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-end space-x-2">
                <button type="submit" id="btnKirim" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Kirim</button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-4 rounded-md shadow-lg">
        <div class="flex items-center space-x-2">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span>Menyimpan...</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('formPelanggaran').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!document.getElementById('id_siswa').value) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Siswa',
            text: 'Silakan pilih siswa terlebih dahulu.',
        });
        return;
    }

    const checkboxes = document.querySelectorAll('input[name="pelanggaran[]"]:checked');
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Pelanggaran',
            text: 'Silakan pilih setidaknya satu pelanggaran.',
        });
        return;
    }

    Swal.fire({
        title: 'Apakah anda yakin?',
        text: 'Ingin mencatat pelanggaran siswa ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Catat!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('loading').classList.remove('hidden');

            const formData = new FormData(this);

            fetch('{{ route("guru.pelanggaran.rekam.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').classList.add('hidden');
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pelanggaran berhasil direkam.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        document.getElementById('formPelanggaran').reset();
                        // Reload page or update UI
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan.',
                    });
                }
            })
            .catch(error => {
                document.getElementById('loading').classList.add('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan.',
                });
            });
        }
    });
});
</script>
@endpush
