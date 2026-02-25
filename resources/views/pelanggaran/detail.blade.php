@extends('layouts.pelanggaran')
@section('page-title', 'Detail Pelanggaran Siswa')
@section('page-description', 'Riwayat Pelanggaran Siswa secara Detail')
@section('content')
    {{-- halaman detail pelanggaran siswa --}}
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Detail Pelanggaran Siswa</h1>
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">

            {{-- Header Atas --}}
            <div class="flex justify-between items-start mb-6">

                {{-- Profil --}}
                <div class="flex items-center">
                    @php
                        $fotoSrc = $siswa->foto
                            ? asset('storage/foto/' . $siswa->foto)
                            : asset(
                                'storage/foto/' .
                                    ($siswa->nama == 'FIKRI MUAFI'
                                        ? 'fikri.jpeg'
                                        : 'siswa' . $siswa->id_siswa . '.jpg'),
                            );
                    @endphp

                    <img src="{{ $fotoSrc }}" alt="Foto Profil"
                        class="w-24 h-24 mr-4 object-cover object-top rounded-lg shadow transition-transform duration-300 hover:scale-105">

                    <div>
                        <h2 class="text-xl font-bold">{{ $siswa->nama }}</h2>
                        <p class="text-sm text-gray-600">NIS: {{ $siswa->nisn }}</p>
                        <p class="text-sm text-gray-600">Kelas: {{ $siswa->kelas->nama_kelas ?? 'N/A' }}</p>
                    </div>
                </div>
                @php
                    $skor = $siswa->total_poin;
                    $sp = $siswa->sp_tertinggi;
                @endphp
                {{-- Tombol Kembali di Pojok Kanan Atas --}}
                <div>
                    @if ($sp === 'SP1')
                        <span class="bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm font-semibold shadow">
                            SP 1
                        </span>
                    @elseif ($sp === 'SP2')
                        <span class="bg-orange-500 text-white px-3 py-2 rounded-lg text-sm font-semibold shadow">
                            SP 2
                        </span>
                    @elseif ($sp === 'SP3')
                        <span class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-semibold shadow">
                            SP 3
                        </span>
                    @else
                        <span class="bg-green-500 text-white px-3 py-2 rounded-lg text-sm font-semibold shadow">
                            Tidak Ada SP
                        </span>
                    @endif
                </div>
            </div>

            {{-- Skor & SP --}}
            <div class="border-t pt-4 flex justify-between items-center">

                {{-- Skor --}}


                <div>
                    <h3 class="text-lg font-semibold">Skor Perilaku</h3>

                    @if ($skor > 0)
                        <span class="text-green-600 text-2xl font-bold">+{{ $skor }}</span>
                    @elseif ($skor < 0)
                        <span class="text-red-600 text-2xl font-bold">{{ $skor }}</span>
                    @else
                        <span class="text-gray-600 text-2xl font-bold">0</span>
                    @endif
                </div>

                {{-- Badge SP --}}


            </div>

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
                    <label for="tanggal" class="text-sm font-medium text-gray-700 whitespace-nowrap">
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
                            <th class="py-2 px-4 text-left border-b">Pelapor</th>
                            <th class="py-2 px-4 text-left border-b">Tanggal</th>
                            <th class="py-2 px-4 text-left border-b">Bukti</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTableBody">
                        @foreach ($riwayatPelanggaran as $index => $rekam)
                            <tr class="hover:bg-gray-50 violation-row" data-date="{{ $rekam->tanggal_pelanggaran }}">
                                <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border-b">{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b">
                                    <span
                                        class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full font-semibold text-sm">
                                        -{{ $rekam->poin_diberikan ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b">{{ $rekam->petugas->waliKelas->nama ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b">{{ $rekam->tanggal_pelanggaran }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if ($rekam->foto_pelanggaran)
                                        <button class="text-blue-600 hover:text-blue-800 font-medium text-sm"
                                            onclick="showBukti('{{ asset('storage/' . $rekam->foto_pelanggaran) }}')">
                                            <i class="fas fa-image mr-1"></i>Lihat Bukti
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak ada bukti</span>
                                    @endif
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
                    <img id="buktiImage" src="" alt="Bukti Pelanggaran"
                        class="w-full max-h-96 object-contain rounded-lg">
                </div>
            </div>
        </div>

    </div>

    <script>
        function showBukti(src) {
            document.getElementById('buktiImage').src = src;
            document.getElementById('buktiModal').classList.remove('hidden');
        }

        function closeBukti() {
            document.getElementById('buktiModal').classList.add('hidden');
        }

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
