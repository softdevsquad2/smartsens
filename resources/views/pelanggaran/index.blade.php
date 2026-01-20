@extends('layouts.pelanggaran')

@section('page-title', 'Dashboard Pelanggaran')
@section('page-description', 'Rangkuman Pelanggaran Umum')

@section('content')
    {{-- ================= DASHBOARD ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">

        {{-- Grafik Pelanggaran per Bulan (LEBAR) --}}
        <div class="bg-white shadow rounded-lg p-6 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 justify-center flex items-center">
                Grafik Pelanggaran Siswa per Bulan
            </h2>
            <div class="relative h-56 w-full justify-center flex items-center">

                <canvas id="grafikPelanggaran" class="w-full h-48"></canvas>
            </div>
        </div>

        {{-- Diagram Jenis Pelanggaran (LEBIH KECIL) --}}
        <div class="bg-white shadow rounded-lg p-6 md:col-span-1">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 justify-center flex items-center">
                Grafik Jenis Pelanggaran
            </h2>
            <div class="relative h-56 justify-center flex items-center">
                <canvas id="diagramJenisPelanggaran"></canvas>
            </div>
        </div>

    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-3">
        <div class="bg-white shadow rounded-lg p-3 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 justify-center flex items-center">
                Data Terbaru Pelanggaran Siswa
            </h2>
            <table class="text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr class="text-center font-semibold">
                        <th class="px-2 py-3 border-b">No</th>
                        <th class="px-4 py-3 border-b text-left">NIS</th>
                        <th class="px-4 py-3 border-b text-left">Nama Siswa</th>
                        <th class="px-4 py-3 border-b text-left">Kelas</th>
                        <th class="px-4 py-3 border-b text-left">Jenis Pelanggaran</th>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">Poin</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($terbaruPelanggaran as $index => $rekam)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-2 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $rekam->siswa->nisn ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $rekam->siswa->nama ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $rekam->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $rekam->pelanggaran->nama_pelanggaran ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $rekam->tanggal_pelanggaran }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-red-600">{{ $rekam->pelanggaran->poin_pelanggaran ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white shadow rounded-lg p-6 md:col-span-1">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 justify-center flex items-center">
                Poin Tertinggi Siswa
            </h2>
            <table class="text-sm text-gray-700 w-full">
                <thead class="bg-gray-100">
                    <tr class="text-center font-semibold">
                        <th class="px-2 py-3 border-b">No</th>
                        <th class="px-4 py-3 border-b text-left">Nama Siswa</th>
                        <th class="px-4 py-3 border-b">Poin</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @php $no = 1 @endphp
                    @foreach($poinTertinggi as $index => $data)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-2 py-3 text-center">{{ $no++ }}</td>
                        <td class="px-4 py-3">{{ $data['siswa']->nama }} ({{ $data['siswa']->kelas->nama_kelas ?? 'N/A' }})</td>
                        <td class="px-4 py-3 text-center font-semibold text-red-600">{{ $data['total_poin'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ================= PIE CHART ================= */
        const pieCtx = document
            .getElementById('diagramJenisPelanggaran')
            .getContext('2d');

        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json($jenisPelanggaran->pluck('nama')),
                datasets: [{
                    data: @json($jenisPelanggaran->pluck('jumlah')),
                    backgroundColor: [
                        '#2563eb',
                        '#dc2626',
                        '#f59e0b',
                        '#16a34a',
                        '#6b7280',
                        '#8b5cf6',
                        '#ef4444'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        /* ================= BAR CHART ================= */
        const barCtx = document
            .getElementById('grafikPelanggaran')
            .getContext('2d');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ],
                datasets: [{
                    label: 'Jumlah Pelanggar',
                    data: @json(array_values($pelanggaranPerBulan)),
                    backgroundColor: 'rgba(37, 99, 235, 0.7)',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,

                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    </script>
@endpush
