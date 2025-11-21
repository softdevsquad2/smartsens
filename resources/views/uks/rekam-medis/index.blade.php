@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Rekam Medis - UKS')
@section('page-title', 'Rekam Medis')
@section('page-description', 'Daftar rekam medis siswa')

<x-sidebar></x-sidebar>

@section('content')


    @include('uks.partials.messages')

    <!-- Search and RFID Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Search Siswa (server-side) -->
            <div>
                <form method="GET" action="{{ route('uks.rekam-medis.index') }}">
                    @csrf
                    <label for="q" class="block text-sm font-semibold text-gray-800 mb-2">
                        Cari Rekam Medis Siswa
                    </label>
                    <div class="flex items-center space-x-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="q" name="q" value="{{ isset($q) ? e($q) : '' }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Ketik nama atau NISN siswa...">
                        </div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg">
                            <i class="fas fa-search mr-2"></i> Cari
                        </button>
                    </div>
                </form>

                @if (isset($q) && $q !== null)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600">Hasil pencarian untuk: <strong>{{ e($q) }}</strong></p>
                        <div class="mt-2 border border-gray-200 rounded-lg bg-white">
                            @if ($siswaResults->isEmpty())
                                <div class="p-3 text-gray-500">Tidak ada siswa ditemukan</div>
                            @else
                                @foreach ($siswaResults as $s)
                                    <a href="{{ route('uks.rekam-medis.index', ['q' => $s->nama]) }}"
                                        class="block p-3 border-b border-gray-100 hover:bg-gray-50">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $s->nama }}</div>
                                                <div class="text-xs text-gray-500">{{ $s->nisn }} -
                                                    {{ $s->kelas->nama_kelas ?? '-' }}
                                                    {{ $s->kelas->jurusan->nama_jurusan ?? '' }}</div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- RFID Tap -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">
                    Tap RFID Siswa
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-400"></i>
                    </div>
                    <input type="text" id="rfid_input" name="rfid_input"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-gray-50"
                        placeholder="Tempelkan kartu RFID siswa..." autofocus>

                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <div id="rfid_status" class="w-3 h-3 bg-gray-300 rounded-full"></div>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Status: <span id="rfid_status_text">Menunggu kartu RFID</span>
                </p>
            </div>
        </div>

        <!-- Selected Student Info -->
        <div id="selected_student" class="mt-4 p-4 bg-purple-50 border border-purple-200 rounded-lg hidden">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h4 id="student_name" class="text-sm font-semibold text-gray-900"></h4>
                        <p id="student_details" class="text-sm text-gray-600"></p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a id="view_medical_records" href="#"
                        class="inline-flex items-center px-3 py-2 border border-purple-300 text-sm font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-notes-medical mr-2"></i>
                        Lihat Rekam Medis
                    </a>
                    <a id="add_medical_record" href="#"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Rekam Medis
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                            Siswa
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keluhan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diagnosis
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rekamMedis as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $r->siswa->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $r->siswa->nisn }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($r->keluhan, 50) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($r->diagnosis ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">

                                    <form action="{{ route('uks.rekam-medis.destroy', $r->id_rekam_medis) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Hapus rekam medis ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                    <a href="{{ url('uks/rekam-medis/siswa/' . $r->siswa->id_siswa) }}" class="">
                                        Detail
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data rekam medis
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($rekamMedis->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $rekamMedis->links() }}
            </div>
        @endif
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('rfid_input');
        const statusText = document.getElementById('rfid_status_text');
        const statusDot = document.getElementById('rfid_status');

        // Fokus otomatis ke input
        input.focus();

        input.addEventListener('input', async function() {
            const rfid = input.value.trim();
            if (rfid.length >= 8) { // sesuaikan dengan panjang RFID kamu
                statusDot.classList.remove('bg-gray-300');
                statusDot.classList.add('bg-green-500');
                statusText.textContent = 'Kartu RFID terbaca';

                try {
                    // 🟣 Ganti URL ini agar sesuai dengan route yang terdaftar
                    const res = await fetch(`/uks/uks/cari-siswa-rfid/${rfid}`);

                    if (!res.ok) {
                        throw new Error('Not found');
                    }

                    const data = await res.json();

                    if (data && data.nama) {
                        document.getElementById('selected_student').classList.remove('hidden');
                        document.getElementById('student_name').textContent = data.nama;
                        document.getElementById('student_details').textContent =
                            `${data.nisn} - ${data.kelas ?? '-'} ${data.jurusan ?? ''}`;

                        // arahkan tombol lihat rekam medis
                        document.getElementById('view_medical_records').href =
                            `/uks/rekam-medis/siswa/${data.id_siswa}`;
                        document.getElementById('add_medical_record').href =
                            `/uks/rekam-medis/create?siswa=${data.id_siswa}`;

                        statusText.textContent = 'Kartu dikenali';

                    } else {
                        statusText.textContent = 'Kartu tidak terdaftar';
                        statusDot.classList.remove('bg-green-500');
                        statusDot.classList.add('bg-red-500');
                    }

                } catch (error) {
                    console.error(error);
                    statusText.textContent = 'Kartu tidak terdaftar';
                    statusDot.classList.remove('bg-green-500');
                    statusDot.classList.add('bg-red-500');
                }

                // Kosongkan input setelah 1 detik
                setTimeout(() => input.value = '', 1000);
            }
        });
    });
</script>
