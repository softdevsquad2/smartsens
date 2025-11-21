@extends('layouts.piket')

@section('page-title', 'Unduh Laporan Absensi')
@section('page-description', 'Filter dan unduh laporan absensi siswa')

@section('content')
    @include('piket.partials.header', [
        'title' => 'Laporan Absensi',
        'description' => 'Unduh Laporan Absensi Siswa',
    ])

    @include('piket.partials.messages')
    <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
        <form method="GET" action="{{ route('piket.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Nama Siswa -->
            <div class="flex flex-col">
                <label for="nama" class="text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                <input type="text" name="nama" id="nama" value="{{ $request->nama ?? '' }}"
                    placeholder="Masukkan nama siswa"
                    class="rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm px-3 py-2">
            </div>

            <!-- Kelas -->
            <div class="flex flex-col">
                <label for="kelas" class="text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas" id="kelas"
                    class="rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm px-3 py-2">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id_kelas }}" @if ($request->kelas == $k->id_kelas) selected @endif>
                            {{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jurusan -->
            <div class="flex flex-col">
                <label for="jurusan" class="text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                <select name="jurusan" id="jurusan"
                    class="rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm px-3 py-2">
                    <option value="">Semua Jurusan</option>
                    @foreach ($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}" @if ($request->jurusan == $j->id_jurusan) selected @endif>
                            {{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal -->
            <div class="flex flex-col">
                <label for="tanggal" class="text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <input type="month" name="tanggal" id="tanggal" value="{{ $request->tanggal ?? '' }}"
                    class="rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm px-3 py-2">
            </div>

            <!-- Tombol Filter & Unduh -->
            <div class="md:col-span-4 flex justify-start md:justify-end space-x-2 mt-4">
                <button type="submit"
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('piket.laporan.export', request()->all()) }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Unduh Excel
                </a>
            </div>
        </form>
    </div>


    <div class="bg-white p-4 rounded-lg shadow-sm overflow-x-auto">
        <form method="GET" class="p-4">
            <label for="per_page" class="text-sm text-gray-700 mr-2">Tampilkan</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()"
                class="border border-gray-300 px-2 py-1 rounded">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-700 ml-2">data per halaman</span>
        </form>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Masuk
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pulang
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pulang
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($absensi as $index => $absen)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $absensi->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $absen->siswa->nama ?? '-' }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $absen->siswa->kelas->nama_kelas ?? '-' }} -
                                {{ $absen->siswa->kelas->jurusan->nama_jurusan ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absen->waktu_masuk ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $status = $absen->status_masuk ?? '-';
                                $statusClasses = 'inline-flex px-2 py-0.5 rounded-full text-xs font-medium ';
                                switch ($status) {
                                    case 'hadir':
                                        $statusClasses .= 'bg-green-100 text-green-800';
                                        $statusText = 'Hadir';
                                        break;
                                    case 'terlambat':
                                        $statusClasses .= 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Terlambat';
                                        break;
                                    case 'sakit':
                                        $statusClasses .= 'bg-red-100 text-red-800';
                                        $statusText = 'Sakit';
                                        break;
                                    case 'izin':
                                        $statusClasses .= 'bg-orange-100 text-orange-800';
                                        $statusText = 'Izin';
                                        break;
                                    case 'sakit_izin':
                                        $statusClasses .= 'bg-purple-100 text-purple-800';
                                        $statusText = 'Sakit/Izin';
                                        break;
                                    default:
                                        $statusClasses .= 'bg-gray-100 text-gray-800';
                                        $statusText = '-';
                                        break;
                                }
                            @endphp
                            <span class="{{ $statusClasses }}">{{ $statusText }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absen->waktu_pulang ?? '-' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $statusPulang = $absen->status_pulang ?? '-';
                                $statusClassesPulang = 'inline-flex px-2 py-0.5 rounded-full text-xs font-medium ';
                                switch ($statusPulang) {
                                    case 'pulang':
                                        $statusClassesPulang .= 'bg-blue-100 text-blue-800';
                                        $statusTextPulang = 'Pulang';
                                        break;
                                    case 'pulang_sakit':
                                        $statusClassesPulang .= 'bg-orange-100 text-orange-800';
                                        $statusTextPulang = 'Izin Pulang';
                                        break;
                                    case 'pulang_sakit':
                                        $statusClassesPulang .= 'bg-orange-100 text-orange-800';
                                        $statusTextPulang = 'Izin Sakit';
                                        break;
                                    case 'izin_pulang':
                                        $statusClassesPulang .= 'bg-orange-100 text-orange-800';
                                        $statusTextPulang = 'Izin Pulang';
                                        break;
                                    default:
                                        $statusClassesPulang .= 'bg-gray-100 text-gray-800';
                                        $statusTextPulang = '-';
                                        break;
                                }
                            @endphp
                            <span class="{{ $statusClassesPulang }}">{{ $statusTextPulang }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $absensi->links() }}
        </div>
    </div>
@endsection
