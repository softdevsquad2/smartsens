@extends('layouts.app')

@section('title', 'Detail Guru - SmartSens')
@section('page-title', 'Detail Guru')
@section('page-description', 'Detail informasi guru')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Guru</h1>
            <p class="mt-1 text-sm text-gray-600">Informasi lengkap guru</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.walikelas.edit', $walikelas) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.walikelas.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Wali Kelas Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-tie text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $walikelas->nama }}</h3>
                    <p class="text-sm text-gray-500">Wali Kelas</p>

                    @if ($walikelas->user)
                        <div class="mt-4 p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-sm font-medium text-green-800">User Account Aktif</span>
                            </div>
                            <p class="text-xs text-green-600 mt-1">{{ $walikelas->user->username }}</p>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium text-yellow-800">Belum ada User Account</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Detail</h3>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $walikelas->nama }}</dd>
                        </div>

                        <!-- NIP -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIP</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $walikelas->nip ?? 'Tidak ada' }}
                            </dd>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kelas</dt>
                            <dd class="mt-1">
                                <div class="flex items-center">
                                    <div
                                        class="w-6 h-6 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-chalkboard text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $walikelas->kelas->nama_kelas }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $walikelas->kelas->jurusan->nama_jurusan ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </dd>
                        </div>

                        <!-- User Account -->
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">User Account</dt>
                            <dd class="mt-1">
                                @if ($walikelas->user)
                                    <div class="flex items-center">
                                        <div
                                            class="w-6 h-6 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $walikelas->user->username }}
                                            </div>
                                            <div class="text-xs text-gray-500">Role: {{ ucfirst($walikelas->user->role) }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 italic">Belum ada user account yang dikaitkan</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.walikelas.edit', $walikelas) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Guru
                    </a>

                    <form action="{{ route('admin.walikelas.destroy', $walikelas) }}" method="POST" class="inline"
                        onsubmit="return confirmDelete('Yakin ingin menghapus guru ini?', 'Konfirmasi Hapus Guru')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus
                        </button>
                    </form>
                </div>

                <div class="text-sm text-gray-500">
                    Dibuat: {{ $walikelas->created_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>
@endsection
