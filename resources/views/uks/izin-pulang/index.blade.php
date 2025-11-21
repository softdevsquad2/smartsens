@extends('layouts.app')

@section('title', 'Izin Pulang - UKS')
@section('page-title', 'Izin Pulang')
@section('page-description', 'Daftar izin pulang siswa')

<x-sidebar></x-sidebar>

@section('content')
    @include('uks.partials.header', [
        'title' => 'Izin Pulang',
        'description' => 'Daftar izin pulang siswa',
        'action' => [
            'url' => route('uks.izin-pulang.create'),
            'text' => 'Tambah Izin Pulang',
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('uks.partials.messages')
    @if (session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-3">
            {{ session('error') }}
        </div>
    @endif

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
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($izin as $i)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $i->siswa->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $i->siswa->nisn }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($i->tanggal)->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">

                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Diberikan
                                </span>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @if ($i->status == 'menunggu')
                                        <form action="{{ route('uks.izin-pulang.approve', $i->id_izin_pulang) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('uks.izin-pulang.reject', $i->id_izin_pulang) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i>
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">{{ ucfirst($i->status) }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data izin pulang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($izin->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $izin->links() }}
            </div>
        @endif
    </div>
@endsection
