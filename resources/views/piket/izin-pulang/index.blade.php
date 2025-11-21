@extends('layouts.piket')

@section('title', 'Izin Pulang - PIKET')

@push('styles')
    <style>
        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background-color: #f8fafc;
        }
    </style>
@endpush

@section('content')
    @include('piket.partials.header', [
        'title' => 'Izin Pulang',
        'description' => 'Daftar izin pulang siswa',
    
        'action' => [
            'url' => route('piket.izin-pulang.create'),
            'text' => 'Tambah Izin Pulang',
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('piket.partials.messages')

    <!-- Izin Pulang Table -->
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:border-primary-200 transition-colors">
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
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal &
                                Waktu</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Alasan</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($izin as $i)
                        <tr class="table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-primary-700">
                                            {{ strtoupper(substr($i->siswa->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $i->siswa->nama }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $i->siswa->kelas->nama_kelas }} {{ $i->siswa->kelas->jurusan->nama }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($i->tanggal)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $i->waktu }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 line-clamp-2">
                                    {{ $i->keterangan }}
                                </p>
                            </td>
                        </tr>
                    @endforeach

                    @if ($izin->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="text-sm">Belum ada data izin pulang</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $izin->links() }}
    </div>
@endsection
