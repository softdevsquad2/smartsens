@extends('layouts.pelanggaran')
@section('page-title', 'Detail Pelanggaran Siswa')
@section('page-description', 'Riwayat Pelanggaran Siswa secara Detail')
@section('content')
{{-- halaman detail pelanggaran siswa --}}
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Detail Pelanggaran Siswa</h1>
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <div class="flex items
-center mb-4">
            <img src="{{ asset('storage/foto/' . ($siswa->nama == 'FIKRI MUAFI' ? 'fikri.jpeg' : 'siswa' . $siswa->id_siswa . '.jpg')) }}" alt="Foto Profil"
         class="w-24 h-24  mr-4
                object-cover object-top
                transition-transform duration-300 hover:scale-110">
            <div>
                <h2 class="text-xl font-bold"> {{ $siswa->nama }}</h2>
                <p>NIS: {{ $siswa->nisn }}</p>
                <p>Kelas: {{ $siswa->kelas->nama_kelas ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-lg font-bold mb-2">Total Poin Pelanggaran: <span class="text-red-500">{{ $totalPoin }}</span></h3>
            <p class="text-gray-700">Catatan: Siswa ini memiliki total poin pelanggaran {{ $totalPoin }}.</p>
        </div>
        {{-- cari tanggal --}}

    </div>
 <div class="bg-white shadow-md rounded-lg p-4">

    <!-- Header: Judul kiri, Filter kanan -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">

        <!-- Judul -->
        <h3 class="text-lg font-bold">
            Riwayat Pelanggaran
        </h3>

        <!-- Filter Tanggal -->
        <div class="flex items-center gap-2">
            <label for="tanggal"
                class="text-sm font-medium text-gray-700 whitespace-nowrap">
                Filter Tanggal:
            </label>

            <input type="date" id="tanggal" name="tanggal"
                class="border border-gray-300 rounded-md p-2
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left border-b">No</th>
                    <th class="py-2 px-4 text-left border-b">Jenis Pelanggaran</th>
                    <th class="py-2 px-4 text-left border-b">Poin</th>
                    <th class="py-2 px-4 text-left border-b">Tanggal</th>
                </tr>
            </thead>
            <tbody id="riwayatTableBody">
                @foreach($riwayatPelanggaran as $index => $rekam)
                <tr class="hover:bg-gray-50 violation-row" data-date="{{ $rekam->tanggal_pelanggaran }}">
                    <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                    <td class="py-2 px-4 border-b">{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $rekam->pelanggaran->poin_pelanggaran ?? 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $rekam->tanggal_pelanggaran }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

</div>

<script>
document.getElementById('tanggal').addEventListener('change', function() {
    const selectedDate = this.value;
    const rows = document.querySelectorAll('.violation-row');

    rows.forEach(row => {
        const rowDate = row.getAttribute('data-date');
        if (selectedDate === '' || rowDate === selectedDate) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Update row numbers after filtering
    updateRowNumbers();
});

function updateRowNumbers() {
    const visibleRows = document.querySelectorAll('.violation-row:not([style*="display: none"])');
    visibleRows.forEach((row, index) => {
        const noCell = row.querySelector('td:first-child');
        if (noCell) {
            noCell.textContent = index + 1;
        }
    });
}
</script>
@endsection
