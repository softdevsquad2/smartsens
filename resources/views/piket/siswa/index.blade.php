@extends('layouts.piket')

@section('title', 'Daftar Siswa - PIKET')

@push('styles')
    <style>
        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background-color: #f8fafc;
        }

        .search-box {
            transition: all 0.3s ease;
        }

        .search-box:focus {
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    @include('piket.partials.header', [
        'title' => 'Daftar Siswa',
        'description' => 'Lakukan Aksi Pada Siswa Terpilih',
    ])

    @include('piket.partials.messages')

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 hover:border-primary-200 transition-colors">
        <form action="{{ route('piket.siswa.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Pencarian -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="search-box w-full pl-10 pr-4 py-2 rounded-lg border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm"
                            placeholder="Cari nama/RFID">
                    </div>
                </div>

                <!-- Filter Kelas -->
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="kelas" id="kelas"
                        class="w-full rounded-lg pl-2 pr-4 py-2 border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Jurusan -->
                <div>
                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                    <select name="jurusan" id="jurusan"
                        class="w-full rounded-lg pl-2 pr-2 py-2 border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                        <option value="">Semua Jurusan</option>
                        @foreach ($jurusan as $j)
                            <option value="{{ $j->id_jurusan }}"
                                {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>

                    @if (request()->hasAny(['search', 'kelas', 'jurusan']))
                        <a href="{{ route('piket.siswa.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-undo mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jurusan</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-right">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($siswa as $s)
                        <tr class="table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-primary-700">
                                            {{ strtoupper(substr($s->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ strtoupper($s->nama) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $s->kelas->nama_kelas }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $jurusan = $s->kelas->jurusan->nama_jurusan ?? '-';
                                    $bgColor = match ($jurusan) {
                                        'Rekayasa Perangkat Lunak' => 'bg-blue-100 text-blue-800',
                                        'Teknik Komputer dan Jaringan' => 'bg-green-100 text-green-800',
                                        'Teknik Mesin' => 'bg-yellow-100 text-yellow-800',
                                        'Teknik Listrik' => 'bg-pink-100 text-pink-800',
                                        'Brodcasting dan Perfilman' => 'bg-purple-100 text-purple-800',
                                        'Teknik Otomotif' => 'bg-blue-100 text-blue-800',
                                        'Disain Permodelan Informasi Bangunan' => 'bg-green-100 text-green-800',
                                        'Teknik Elektronik' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp

                                <span
                                    class="px-3 py-1 inline-flex items-center text-xs font-medium rounded-full {{ $bgColor }}">
                                    {{ $jurusan }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $s->nisn }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('piket.izin-pulang.create', ['id_siswa' => $s->id_siswa]) }}"
                                    class="inline-flex items-center px-3 py-1 border border-primary-200 text-sm leading-5 font-medium rounded-lg text-primary-700 bg-primary-50 hover:text-primary-800 hover:bg-primary-100 hover:border-primary-300 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-1.5"></i>
                                    Izin Pulang
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $siswa->links() }}
    </div>
@endsection
