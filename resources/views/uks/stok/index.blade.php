@extends('layouts.app')

@section('title', 'Stok Obat - UKS')
@section('page-title', 'Stok Obat')
@section('page-description', 'Kelola stok obat dan tanggal kadaluarsa')

<x-sidebar></x-sidebar>

@section('content')
    @include('uks.partials.header', [
        'title' => 'Stok Obat',
        'description' => 'Kelola stok obat dan tanggal kadaluarsa',
        'action' => [
            'url' => route('uks.stok.create'),
            'text' => 'Tambah Stok',
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('uks.partials.messages')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="relative overflow-x-auto">
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
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Obat
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Masuk
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Expired
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($stok as $s)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-pills text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $s->obat->nama_obat }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ number_format($s->jumlah) }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($s->tanggal_masuk)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($s->expired_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('uks.stok.edit', $s->id_stok) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('uks.stok.destroy', $s->id_stok) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus stok ini?');">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data stok obat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($stok->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $stok->links() }}
            </div>
        @endif
    </div>
@endsection
