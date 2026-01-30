@extends('layouts.app')
{{-- @section('title', 'Rekam Medis - UKS') --}}
@section('page-title', 'Daftar Obat Keluar')
@section('page-description', 'Daftar Obat Keluar')
@section('title', 'Daftar Obat Keluar - UKS')
<x-sidebar></x-sidebar>

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Daftar Obat Keluar</h2>
            <a href="{{ route('uks.export.obat-keluar') }}"
                class="bg-blue-500 hover:bg-blue-600 text-dark px-4 py-2 rounded-lg">
                Download Excel
            </a>
        </div>

        <table class="min-w-full border-collapse border border-gray-300 ">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-center align-middle">Tanggal</th>
<th class="border px-4 py-2 text-center align-middle">Nama Siswa</th>
<th class="border px-4 py-2 text-center align-middle">NISN</th>
<th class="border px-4 py-2 text-center align-middle">Obat Diberikan</th>
<th class="border px-4 py-2 text-center align-middle">Diagnosis</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                       <td class="border px-4 py-2 text-center align-middle">{{ $item->tanggal }}</td>
<td class="border px-4 py-2 text-center align-middle">{{ $item->siswa->nama }}</td>
<td class="border px-4 py-2 text-center align-middle">{{ $item->siswa->nisn }}</td>
<td class="border px-4 py-2 text-center align-middle">

                            @php
                                $obatData = json_decode($item->obat_diberikan, true);
                            @endphp

                            @if (is_array($obatData))
                                <ul class="list-disc ml-4">
                                    @foreach ($obatData as $ob)
                                        <li>
                                            {{-- Tampilkan nama obat, bukan id --}}
                                            @php
                                                $namaObat = \App\Models\Obat::where('id_obat', $ob['id_obat'])->value(
                                                    'nama_obat',
                                                );
                                            @endphp

                                            {{ $namaObat ?? 'Tidak diketahui' }} — jumlah: {{ $ob['jumlah'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td class="border px-4 py-2">{{ $item->diagnosis }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-gray-500">Belum ada data obat keluar</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>
@endsection
