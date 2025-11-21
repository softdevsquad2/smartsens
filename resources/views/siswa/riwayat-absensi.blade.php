@extends('layouts.app')

@section('title', 'Riwayat Absensi - SmartSens')
@section('page-title', 'Riwayat Absensi')
@section('page-description', 'Riwayat presensi siswa')

@section('sidebar')
    <!-- Beranda -->
    <a href="/siswa/dashboard"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Beranda</span>
    </a>

    <!-- Absensi -->
    <a href="/siswa/absen"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-fingerprint"></i>
        <span>Absensi</span>
    </a>

    <!-- Riwayat Absensi -->
    <a href="/siswa/riwayat-absensi" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-history"></i>
        <span>Riwayat Absensi</span>
    </a>
    <a href="/siswa/riwayat-sholat"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-history"></i>
        <span>Riwayat sholat</span>
    </a>

    <!-- Pengaturan -->
    <a href="/siswa/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-app-primary">Riwayat Absensi</h1>
        <p class="mt-2 text-lg text-app-primary">Lihat riwayat presensi Anda</p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Bulan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter mr-2 text-blue-500"></i>
            Filter Bulan
        </h3>
        <form method="GET" action="{{ route('siswa.riwayat-absensi') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-0">
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" id="bulan"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($daftarBulan as $key => $namaBulan)
                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $namaBulan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-0">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" id="tahun"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Statistik Absensi -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $statistik['hadir'] }}</div>
            <div class="text-sm text-gray-600">Hadir</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $statistik['terlambat'] }}</div>
            <div class="text-sm text-gray-600">Terlambat</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $statistik['sakit'] }}</div>
            <div class="text-sm text-gray-600">Sakit</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $statistik['izin'] }}</div>
            <div class="text-sm text-gray-600">Izin</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-gray-600">{{ $statistik['alfa'] }}</div>
            <div class="text-sm text-gray-600">Alfa</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $statistik['total'] }}</div>
            <div class="text-sm text-gray-600">Total</div>
        </div>
    </div>

    <!-- Riwayat Absensi Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($absensi as $absen)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden cursor-pointer hover:shadow-md transition-shadow"
                onclick="showDetailModal(this)" data-id="{{ $absen->id_absensi }}" data-tanggal="{{ $absen->tanggal }}"
                data-waktu-masuk="{{ $absen->waktu_masuk }}" data-waktu-pulang="{{ $absen->waktu_pulang }}"
                data-status-masuk="{{ $absen->status_masuk }}" data-status-pulang="{{ $absen->status_pulang }}"
                data-foto-masuk="{{ $absen->foto_masuk ? '/storage/' . $absen->foto_masuk : '' }}"
                data-foto-pulang="{{ $absen->foto_pulang ? '/storage/' . $absen->foto_pulang : '' }}">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($absen->tanggal)->format('l, d M Y') }}
                        </div>
                        @php
                            $statusClass = 'bg-gray-100 text-gray-800';
                            $statusIcon = 'fa-times-circle';
                            switch ($absen->status_masuk) {
                                case 'hadir':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusIcon = 'fa-check-circle';
                                    break;
                                case 'terlambat':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusIcon = 'fa-exclamation-triangle';
                                    break;
                                case 'sakit':
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusIcon = 'fa-user-injured';
                                    break;
                                case 'izin':
                                    $statusClass = 'bg-orange-100 text-orange-800';
                                    $statusIcon = 'fa-user-clock';
                                    break;
                                case 'sakit_izin':
                                    $statusClass = 'bg-purple-100 text-purple-800';
                                    $statusIcon = 'fa-user-times';
                                    break;
                                case 'alfa':
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusIcon = 'fa-times-circle';
                                    break;
                            }
                        @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }} mr-1"></i>
                            @if ($absen->status_masuk == 'sakit_izin')
                                Sakit/Izin
                            @else
                                {{ ucfirst($absen->status_masuk ?? 'N/A') }}
                            @endif
                        </span>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-sign-in-alt text-green-500 mr-2 w-4"></i>
                            <span class="text-gray-600">Masuk:</span>
                            <span class="ml-1 font-medium">{{ $absen->waktu_masuk ?? '-' }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-sign-out-alt text-blue-500 mr-2 w-4"></i>
                            <span class="text-gray-600">Pulang:</span>
                            <span class="ml-1 font-medium">{{ $absen->waktu_pulang ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data absensi</h3>
                    <p class="text-gray-500">Belum ada data absensi untuk bulan {{ $daftarBulan[$bulan] }}
                        {{ $tahun }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($absensi->hasPages())
        <div class="mt-8">
            {{ $absensi->links() }}
        </div>
    @endif

    <!-- Modal Detail Absensi -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <!-- Header Modal -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Absensi</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Info Tanggal -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900" id="detailDate">-</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->siswa->nama }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Absensi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Absen Masuk -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-900 mb-2">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            Absen Masuk
                        </h4>
                        <div class="space-y-1">
                            <p class="text-sm text-green-800">
                                <span class="font-medium">Waktu:</span> <span id="detailWaktuMasuk">-</span>
                            </p>
                            <p class="text-sm text-green-800">
                                <span class="font-medium">Status:</span> <span id="detailStatusMasuk">-</span>
                            </p>
                        </div>
                    </div>

                    <!-- Absen Pulang -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Absen Pulang
                        </h4>
                        <div class="space-y-1">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">Waktu:</span> <span id="detailWaktuPulang">-</span>
                            </p>
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">Status:</span> <span id="detailStatusPulang">-</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Foto Absensi -->
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">
                        <i class="fas fa-camera mr-1"></i>
                        Foto Absensi
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Foto Masuk -->
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-700 mb-2">Foto Masuk</p>
                            <div id="fotoMasukContainer" class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                <img id="fotoMasuk" src="" alt="Foto Masuk"
                                    class="max-w-full h-auto rounded-lg shadow-lg mx-auto hidden"
                                    style="max-height: 200px;">
                                <div id="noFotoMasuk" class="text-gray-500">
                                    <i class="fas fa-image text-2xl mb-2"></i>
                                    <p class="text-sm">Tidak ada foto</p>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Pulang -->
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-700 mb-2">Foto Pulang</p>
                            <div id="fotoPulangContainer" class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                <img id="fotoPulang" src="" alt="Foto Pulang"
                                    class="max-w-full h-auto rounded-lg shadow-lg mx-auto hidden"
                                    style="max-height: 200px;">
                                <div id="noFotoPulang" class="text-gray-500">
                                    <i class="fas fa-image text-2xl mb-2"></i>
                                    <p class="text-sm">Tidak ada foto</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end">
                    <button onclick="closeDetailModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(el) {
            const id = el.dataset.id;
            const tanggal = el.dataset.tanggal;
            const waktuMasuk = el.dataset.waktuMasuk;
            const waktuPulang = el.dataset.waktuPulang;
            const statusMasuk = el.dataset.statusMasuk;
            const statusPulang = el.dataset.statusPulang;
            const fotoMasuk = el.dataset.fotoMasuk;
            const fotoPulang = el.dataset.fotoPulang;

            // Set tanggal
            const date = new Date(tanggal);
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('detailDate').textContent = date.toLocaleDateString('id-ID', options);

            // Set detail waktu dan status
            document.getElementById('detailWaktuMasuk').textContent = waktuMasuk || '-';
            document.getElementById('detailWaktuPulang').textContent = waktuPulang || '-';
            document.getElementById('detailStatusMasuk').textContent = statusMasuk ? (statusMasuk === 'sakit_izin' ?
                'Sakit/Izin' : statusMasuk.charAt(0).toUpperCase() + statusMasuk.slice(1)) : '-';
            document.getElementById('detailStatusPulang').textContent = statusPulang ? statusPulang.charAt(0)
                .toUpperCase() + statusPulang.slice(1) : '-';

            // Handle foto masuk
            const fotoMasukEl = document.getElementById('fotoMasuk');
            const noFotoMasukEl = document.getElementById('noFotoMasuk');
            if (fotoMasuk) {
                fotoMasukEl.src = fotoMasuk;
                fotoMasukEl.classList.remove('hidden');
                noFotoMasukEl.classList.add('hidden');
            } else {
                fotoMasukEl.classList.add('hidden');
                noFotoMasukEl.classList.remove('hidden');
            }

            // Handle foto pulang
            const fotoPulangEl = document.getElementById('fotoPulang');
            const noFotoPulangEl = document.getElementById('noFotoPulang');
            if (fotoPulang) {
                fotoPulangEl.src = fotoPulang;
                fotoPulangEl.classList.remove('hidden');
                noFotoPulangEl.classList.add('hidden');
            } else {
                fotoPulangEl.classList.add('hidden');
                noFotoPulangEl.classList.remove('hidden');
            }

            // Tampilkan modal
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>

@endsection
