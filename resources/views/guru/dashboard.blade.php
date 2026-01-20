@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas - SmartSens')
@section('page-title', 'Dashboard Wali Kelas')
@section('page-description', 'Dashboard untuk wali kelas')

@section('sidebar')
    <!-- Dashboard -->
    <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <!-- Daftar Siswa -->
    <a href="{{ route('guru.siswa.index') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-users"></i>
        <span>Daftar Siswa</span>
    </a>

    <!-- Absensi Hari Ini -->
    <a href="{{ route('guru.absensi.hari-ini') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-day"></i>
        <span>Absensi Hari Ini</span>
    </a>

    <!-- Laporan Absensi -->
    <a href="{{ route('guru.absensi.laporan') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chart-bar"></i>
        <span>Laporan Absensi</span>
    </a>

    <!-- Logout -->
    <a href="{{ route('logout') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors mt-auto">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
@endsection

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ $waliKelas->nama }}</h1>
        <p class="mt-1 text-sm text-gray-600">Wali Kelas {{ $kelas->nama_kelas }} -
            {{ $kelas->jurusan->nama_jurusan ?? '' }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>

        <!-- Kehadiran Hari Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $hadirHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Izin Hari Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Izin Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $izinHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Alpha Hari Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Alpha Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $alphaHariIni }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Absensi Bulan Ini -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Absensi Bulan Ini</h3>
                <p class="mt-1 text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Hadir</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $hadirBulanIni }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Izin</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $izinBulanIni }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-medkit text-blue-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Sakit</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $sakitBulanIni }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Alpha</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $alphaBulanIni }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Persentase Kehadiran -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Persentase Kehadiran</h3>
                <p class="mt-1 text-sm text-gray-500">Bulan {{ \Carbon\Carbon::now()->format('F Y') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#E5E7EB" stroke-width="2" />
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#10B981" stroke-width="2"
                                stroke-dasharray="{{ $persentaseKehadiran }}, 100" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $persentaseKehadiran }}%</span>
                        </div>
                    </div>
                </div>
                <p class="text-center text-sm text-gray-600 mt-4">Persentase kehadiran siswa</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('guru.siswa.index') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Daftar Siswa</h4>
                        <p class="text-xs text-gray-600">Lihat semua siswa di kelas</p>
                    </div>
                </a>

                <a href="{{ route('guru.absensi.hari-ini') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-200">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-day text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Absensi Hari Ini</h4>
                        <p class="text-xs text-gray-600">Lihat absensi hari ini</p>
                    </div>
                </a>

                <a href="{{ route('guru.absensi.laporan') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-200">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Laporan Absensi</h4>
                        <p class="text-xs text-gray-600">Lihat laporan absensi</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Modal Rekam Pelanggaran -->
<div id="modalPelanggaran" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rekam Pelanggaran Siswa</h3>
            <form id="formPelanggaran">
                @csrf
                <div class="mb-4">
                    <label for="siswa" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                    <select id="siswa" name="id_siswa" class="searchable-select mt-1 block w-full" required>
                        <option value="">Pilih Siswa</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->id_siswa }}">
                                {{ $s->nama }} ({{ $s->nisn }})
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-1 text-xs text-gray-500">Total siswa: {{ $siswa ? $siswa->count() : 0 }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pelanggaran</label>
                    @foreach ($pelanggaran as $p)
                        <div class="flex items-center">
                            <input type="checkbox" id="pelanggaran{{ $p->id }}" name="pelanggaran[]"
                                value="{{ $p->id }}"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="pelanggaran{{ $p->id }}" class="ml-2 block text-sm text-gray-900">
                                {{ $p->nama_pelanggaran }} ({{ $p->poin_pelanggaran }} poin)
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="btnBatal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
                    <button type="submit" id="btnKirim"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Kirim</button>
                </div>
            </form>
        </div>
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

<!-- Floating Button Rekam Pelanggaran -->
<div class="fixed bottom-4 right-4 z-50">
    <button id="btnPelanggaran"
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110"
        title="Rekam Pelanggaran">
        <i class="fas fa-plus text-xl"></i>
    </button>
</div>
@endsection





@push('scripts')
  <script>
    document.getElementById('btnPelanggaran').addEventListener('click', function() {
        document.getElementById('modalPelanggaran').classList.remove('hidden');
    });

    document.getElementById('btnBatal').addEventListener('click', function() {
        document.getElementById('modalPelanggaran').classList.add('hidden');
        document.getElementById('formPelanggaran').reset();
        // Reset select2
        $('#siswa').val(null).trigger('change');
    });

    document.getElementById('formPelanggaran').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah anda yakin ingin mencatat pelanggaran siswa?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Catat',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('loading').classList.remove('hidden');

                const formData = new FormData(this);

                fetch('{{ route('guru.pelanggaran.rekam.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('loading').classList.add('hidden');
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Pelanggaran berhasil direkam.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                document.getElementById('modalPelanggaran').classList.add(
                                    'hidden');
                                document.getElementById('formPelanggaran').reset();
                                // Reset select2
                                $('#siswa').val(null).trigger('change');
                                // Reload page or update UI
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal: ' + (data.message || 'Terjadi kesalahan.'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        document.getElementById('loading').classList.add('hidden');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal: Terjadi kesalahan jaringan.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#siswa').select2({
            placeholder: 'Cari nama atau NISN siswa...',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalPelanggaran') // WAJIB untuk modal
        });
    });
</script>
@endpush

