@extends('layouts.toolman')

@section('title', 'Pengembalian Barang')
@section('page-title', 'Pengembalian Barang')

@section('content')

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">#</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Siswa</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Barang</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold uppercase text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($peminjamans as $index => $p)
                    <tr>
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $p->user->siswa->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->barang->nama_barang ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $p->tanggal_pinjam }}</td>
                        <td class="px-6 py-4">
                            {{-- <form action="{{ route('toolman.pengembalian.proses', $p->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menandai barang ini dikembalikan?');">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    Dikembalikan
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
