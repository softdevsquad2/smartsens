@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Data Obat - UKS')
@section('page-title', 'Data Obat')
@section('page-description', 'Kelola daftar obat dan informasi stok')

<x-sidebar></x-sidebar>

@section('content')
    @include('uks.partials.header', [
        'title' => 'Data Obat',
        'description' => 'Kelola daftar obat dan informasi stok',
        'action' => [
            'url' => route('uks.obat.create'),
            'text' => 'Tambah Obat',
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('uks.partials.messages')

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-pills text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Obat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalObat }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-tags text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalKategori }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Stok Menipis</p>

                    <p class="text-2xl font-bold text-gray-900">{{ $stokMenipis }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Stok Habis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stokHabis }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('uks.obat.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        Cari Obat
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama obat atau deskripsi...">
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">
                        Kategori
                    </label>
                    <select name="kategori" id="kategori"
                        class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <option value="Obat Bebas" {{ request('kategori') == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas
                        </option>
                        <option value="Obat Bebas Terbatas"
                            {{ request('kategori') == 'Obat Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas
                        </option>
                        <option value="Obat Keras" {{ request('kategori') == 'Obat Keras' ? 'selected' : '' }}>Obat Keras
                        </option>
                        <option value="P3K" {{ request('kategori') == 'P3K' ? 'selected' : '' }}>P3K</option>
                        <option value="Alat Kesehatan" {{ request('kategori') == 'Alat Kesehatan' ? 'selected' : '' }}>Alat
                            Kesehatan</option>
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div>
                    <label for="status_stok" class="block text-sm font-medium text-gray-700 mb-1">
                        Status Stok
                    </label>
                    <select name="status_stok" id="status_stok"
                        class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status_stok') == 'tersedia' ? 'selected' : '' }}>Tersedia
                            (>10)</option>
                        <option value="menipis" {{ request('status_stok') == 'menipis' ? 'selected' : '' }}>Menipis (1-10)
                        </option>
                        <option value="habis" {{ request('status_stok') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                    <a href="{{ route('uks.obat.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Obat
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stok
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($obat as $o)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-pills text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $o->nama_obat }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $o->kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $stokTotal = $o->stokObat->sum('jumlah') ?? 0;
                                @endphp
                                @if ($stokTotal > 10)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($stokTotal) }} unit
                                    </span>
                                @elseif($stokTotal > 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ number_format($stokTotal) }} unit
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($o->deskripsi, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('uks.obat.edit', $o->id_obat) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('uks.obat.destroy', $o->id_obat) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus obat ini?');">
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
                                Belum ada data obat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($obat->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $obat->links() }}
            </div>
        @endif
    </div>
@endsection
