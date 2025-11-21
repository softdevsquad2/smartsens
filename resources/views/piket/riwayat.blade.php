@extends('layouts.piket')

@section('title', 'Riwayat Absen - SmartSens')

@push('styles')
    <style>
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .action-card {
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
@endpush
{{-- @extends('layouts.app') --}}

{{-- @section('title', 'Riwayat Absensi') --}}

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Riwayat Absensi Siswa</h2>

        <!-- Search & Filter -->
        <form method="GET" class="mb-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="text" name="q" placeholder="Cari nama / NISN" value="{{ request('q') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <select name="kelas"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                <select name="jurusan"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Semua Jurusan</option>
                    @foreach ($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all">Filter</button>
            </div>
        </form>

        <!-- Riwayat Table -->
        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Masuk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pulang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($absensi as $key => $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $absensi->firstItem() + $key }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $data->siswa->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $data->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700"> {{ date('d M Y', strtotime($data->tanggal)) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $data->waktu_masuk ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $data->waktu_pulang ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($data->waktu_pulang)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Hadir</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Belum Pulang</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data absensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $absensi->links() }}
        </div>
    </div>
@endsection
