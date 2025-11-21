@extends('layouts.app')

@section('title', 'Rekam Medis Siswa - UKS')
@section('page-title', 'Rekam Medis Siswa')
@section('page-description', 'Riwayat rekam medis siswa yang dipilih')

<x-sidebar></x-sidebar>

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $siswa->nama }}</h2>
                <p class="text-sm text-gray-500">
                    NISN: {{ $siswa->nisn }}<br>
                    Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }} {{ $siswa->kelas->jurusan->nama_jurusan ?? '' }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keluhan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diagnosis
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Obat Diberikan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rekamMedis as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $r->keluhan ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $r->diagnosis ?? '-' }}</td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if (isset($r->obat) && $r->obat->count())
                                    <ul class="list-disc list-inside">
                                        @foreach ($r->obat as $item)
                                            <li>
                                                {{ $item->obat->nama_obat ?? 'Obat ID: ' . ($item->id_obat ?? '-') }}
                                                x {{ $item->jumlah ?? '-' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif(is_array($r->obat_diberikan) && count($r->obat_diberikan))
                                    <ul class="list-disc list-inside">
                                        @foreach ($r->obat_diberikan as $ob)
                                            <li>
                                                @php
                                                    $name = null;
                                                    if (isset($ob['id_obat'])) {
                                                        $o = \App\Models\Obat::find($ob['id_obat']);
                                                        $name = $o ? $o->nama_obat : null;
                                                    }
                                                @endphp
                                                {{ $name ?? ($ob['nama'] ?? 'Obat ID: ' . ($ob['id_obat'] ?? '-')) }} x
                                                {{ $ob['jumlah'] ?? '-' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada rekam medis untuk siswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('uks.rekam-medis.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Rekam Medis
        </a>
    </div>
@endsection
