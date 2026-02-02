@extends('layouts.app')

@section('title', 'Data Absensi - SmartSens')
@section('page-title', 'Data Absensi')
@section('page-description', 'Manajemen data absensi siswa')

@section('sidebar')
    <!-- Beranda -->
    <a href="/admin/dashboard"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Beranda</span>
    </a>

    <!-- Absensi -->
    <a href="/admin/absensi" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-calendar-check"></i>
        <span>Absensi</span>
    </a>

    <!-- Kelola Siswa -->
    <a href="/admin/siswa"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-users"></i>
        <span>Kelola Siswa</span>
    </a>

    <!-- Kelola Kelas -->
    <a href="/admin/kelas"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chalkboard"></i>
        <span>Kelola Kelas</span>
    </a>

    <!-- Kelola Jurusan -->
    <a href="/admin/jurusan"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-graduation-cap"></i>
        <span>Kelola Jurusan</span>
    </a>

    <!-- Kelola Wali Kelas -->
    <a href="/admin/walikelas"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-user-tie"></i>
        <span>Kelola Wali Kelas</span>
    </a>

    <!-- Kelola User -->
    <a href="/admin/user"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-user-cog"></i>
        <span>Kelola User</span>
    </a>

    <!-- Pengaturan -->
    <a href="/admin/settings"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>


@endsection

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Absensi</h1>
            <p class="mt-1 text-sm text-gray-600">Manajemen data absensi siswa dalam sistem</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <div class="flex space-x-3">
                <button onclick="exportData()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Export Data
                </button>
                <button onclick="refreshData()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
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

    <!-- Search Form -->
    <form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                    placeholder="Nama siswa..."
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal ?? '' }}"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ ($status ?? '') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ ($status ?? '') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="sakit" {{ ($status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ ($status ?? '') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit_izin" {{ ($status ?? '') == 'sakit_izin' ? 'selected' : '' }}>Sakit/Izin</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-1"></i>Cari
                </button>
                @if(($search ?? '') || ($tanggal ?? '') || ($status ?? ''))
                    <a href="{{ route('admin.absensi') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Attendance Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                            Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                            Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                            Pulang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                            Pulang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absensi as $index => $absen)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $absensi->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $absen->siswa->nama ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $absen->siswa->kelas->nama_kelas ?? '' }} -
                                            {{ $absen->siswa->kelas->jurusan->nama_jurusan ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($absen->waktu_masuk)
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-green-500 mr-2"></i>
                                        {{ $absen->waktu_masuk }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($absen->status_masuk)
                                    @php
                                        $classes =
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ';
                                        switch ($absen->status_masuk) {
                                            case 'hadir':
                                                $classes .= 'bg-green-100 text-green-800';
                                                $icon = 'fa-check-circle';
                                                break;
                                            case 'terlambat':
                                                $classes .= 'bg-yellow-100 text-yellow-800';
                                                $icon = 'fa-exclamation-triangle';
                                                break;
                                            case 'sakit':
                                                $classes .= 'bg-red-100 text-red-800';
                                                $icon = 'fa-user-injured';
                                                break;
                                            case 'izin':
                                                $classes .= 'bg-orange-100 text-orange-800';
                                                $icon = 'fa-user-clock';
                                                break;
                                            case 'sakit_izin':
                                                $classes .= 'bg-purple-100 text-purple-800';
                                                $icon = 'fa-user-times';
                                                break;
                                            default:
                                                $classes .= 'bg-gray-100 text-gray-800';
                                                $icon = 'fa-times-circle';
                                                break;
                                        }
                                    @endphp
                                    <span class="{{ $classes }}">
                                        <i class="fas {{ $icon }} mr-1"></i>
                                        @if ($absen->status_masuk == 'sakit_izin')
                                            Sakit/Izin
                                        @else
                                            {{ ucfirst($absen->status_masuk) }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($absen->waktu_pulang)
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                                        {{ $absen->waktu_pulang }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($absen->status_pulang)
                                    @php
                                        $classesPulang =
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ';
                                        if ($absen->status_pulang == 'pulang') {
                                            $classesPulang .= 'bg-blue-100 text-blue-800';
                                            $iconPulang = 'fa-sign-out-alt';
                                        } else {
                                            $classesPulang .= 'bg-gray-100 text-gray-800';
                                            $iconPulang = 'fa-times-circle';
                                        }
                                    @endphp
                                    <span class="{{ $classesPulang }}">
                                        <i class="fas {{ $iconPulang }} mr-1"></i>
                                        {{ ucfirst($absen->status_pulang) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($absen->foto_masuk)
                                    <button
                                        onclick="viewPhoto('{{ $absen->foto_masuk ?? '-' }}', '{{ $absen->siswa->nama ?? '-' }}','{{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}')"
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-image mr-1"></i>
                                        Lihat Foto
                                    </button>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button onclick="viewDetails({{ $absen->id_absensi }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </button>
                                    <form id="deleteForm-{{ $absen->id_absensi }}"
                                        action="{{ route('absensi.destroy', $absen->id_absensi) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteAbsen({{ $absen->id_absensi }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    </form>


                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-check text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data absensi</h3>
                                    <p class="text-gray-500 mb-4">Belum ada data absensi yang tercatat</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($absensi->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($absensi->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $absensi->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @endif

                        @if ($absensi->hasMorePages())
                            <a href="{{ $absensi->nextPageUrl() }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @else
                            <span
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-not-allowed">
                                Selanjutnya
                            </span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $absensi->firstItem() }}</span> to <span
                                    class="font-medium">{{ $absensi->lastItem() }}</span> of <span
                                    class="font-medium">{{ $absensi->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            {{ $absensi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal untuk menampilkan foto -->
    <div id="photoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Header Modal -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Foto Absensi</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Info Siswa -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900" id="studentName">-</p>
                            <p class="text-sm text-gray-500" id="attendanceDate">-</p>
                        </div>
                    </div>
                </div>

                <!-- Foto -->
                <div class="text-center">
                    <img id="photoImage" alt="Foto Absensi" class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                        style="max-height: 400px;">
                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end">
                    <button onclick="closePhotoModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-[420px] p-6 relative">
            <button onclick="closeDetailModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>

            <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Absensi</h2>
            <p id="detailNama" class="font-medium text-gray-700"></p>
            <p id="detailTanggal" class="text-sm text-gray-500 mb-4"></p>

            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-sm text-gray-800 mb-2">Masuk</h3>
                    <img id="detailPhotoMasuk" class="w-32 h-32 object-cover rounded-md border mb-2 hidden">
                    <p><b>Waktu:</b> <span id="detailMasuk">-</span></p>
                    <p><b>Status:</b> <span id="detailStatusMasuk">-</span></p>

                </div>
                <div class="border-t border-gray-200 pt-3">
                    <h3 class="font-semibold text-sm text-gray-800 mb-2">Pulang</h3>
                    <img id="detailPhotoPulang" class="w-32 h-32 object-cover rounded-md border mb-2 hidden">
                    <p><b>Waktu:</b> <span id="detailPulang">-</span></p>
                    <p><b>Status:</b> <span id="detailStatusPulang">-</span></p>

                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDeleteAbsen(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus Absensi',
                text: 'Yakin ingin menghapus data absensi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + id).submit();
                }
            });
        }

        function viewDetails(id) {
            $.ajax({
                url: `/admin/absensi/${id}/detail`, // <- wajib ada '/admin' di depannya
                type: 'GET',
                success: function(data) {
                    $('#detailNama').text(`${data.nama_siswa} (${data.kelas} - ${data.jurusan})`);
                    $('#detailTanggal').text(data.tanggal);
                    $('#detailStatusMasuk').text(data.statusMasuk);
                    $('#detailMasuk').text(data.waktu_masuk ?? '-');
                    $('#photoImage').attr('src', data.photo_path);



                    if (data.photo_path) {
                        $('#detailPhotoMasuk').attr('src', data.photo_path).removeClass('hidden');
                    } else {
                        $('#detailPhotoMasuk').addClass('hidden');
                    }
                    if (data.photo_path_pulang) {
                        $('#detailPhotoPulang').attr('src', data.photo_path_pulang).removeClass('hidden');
                    } else {
                        $('#detailPhotoPulang').addClass('hidden');
                    }

                    $('#detailModal').removeClass('hidden').addClass('flex');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat memuat detail absensi.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }



        function closeDetailModal() {
            $('#detailModal').addClass('hidden').removeClass('flex');
        }
    </script>
    <!-- Script Konfirmasi Hapus dan Fungsi Lainnya -->
    <script>
        function exportData() {
            // Implement export functionality
            showInfo('Fitur export akan segera tersedia!', 'Coming Soon');
        }

        function refreshData() {
            window.location.reload();
        }



        function viewPhoto(photoPath, studentName, attendanceDate) {
            // Set data modal
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('attendanceDate').textContent = 'Tanggal: ' + attendanceDate;

            // Set foto
            const photoUrl = '/storage/' + photoPath;
            document.getElementById('photoImage').src = photoUrl;

            // Tampilkan modal
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePhotoModal();
            }
        });
    </script>
@endsection
