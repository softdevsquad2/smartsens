@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp


@section('title', 'Kunjungan UKS')
@section('page-title', 'Kunjungan UKS')
@section('page-description', 'Daftar kunjungan siswa ke unit kesehatan sekolah')

<x-sidebar></x-sidebar>

@section('content')
    @include('uks.partials.header', [
        'title' => 'Kunjungan UKS',
        'description' => 'Daftar kunjungan siswa ke unit kesehatan sekolah',
    ])

    @include('uks.partials.messages')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Kunjungan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rekam Medis
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($kunjungan as $k)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $k->siswa->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $k->siswa->nisn }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $k->waktu }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($k->jenis_kunjungan == 'sakit') bg-red-100 text-red-800
                                    @elseif($k->jenis_kunjungan == 'cedera') bg-orange-100 text-orange-800
                                    @elseif($k->jenis_kunjungan == 'pemeriksaan_rutin') bg-blue-100 text-blue-800
                                    @elseif($k->jenis_kunjungan == 'konsultasi') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $k->jenis_kunjungan)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $k->keterangan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if ($k->rekamMedis)
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">
                                            {{ Str::limit($k->rekamMedis->keluhan, 30) }}</div>
                                        @if ($k->rekamMedis->diagnosis)
                                            <div class="text-gray-500">{{ Str::limit($k->rekamMedis->diagnosis, 30) }}
                                            </div>
                                        @endif
                                        @if ($k->rekamMedis->obat_diberikan && count($k->rekamMedis->obat_diberikan) > 0)
                                            <div class="text-xs text-blue-600 mt-1">
                                                <i class="fas fa-pills mr-1"></i>
                                                {{ count($k->rekamMedis->obat_diberikan) }} obat
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Tidak ada rekam medis</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('uks.kunjungan.edit', $k->id_kunjungan) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('uks.kunjungan.destroy', $k->id_kunjungan) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus kunjungan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data kunjungan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($kunjungan->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $kunjungan->links() }}
            </div>
        @endif
    </div>
@endsection
