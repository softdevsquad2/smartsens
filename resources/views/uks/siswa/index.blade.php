@extends('layouts.app')

<x-sidebar></x-sidebar>
@section('page-title', 'Data Siswa')
@section('page-description', 'Cari dan lakukan aksi pada siswa')

@section('content')
    @include('uks.partials.header', [
        'title' => 'Data Siswa',
        'description' => 'Cari dan lakukan aksi pada siswa',
    ])

    @include('uks.partials.messages')
    <div class="p-6 bg-white rounded-lg shadow-md">

        {{-- Filter dan Pencarian --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
            <form method="GET" action="{{ route('uks.siswa.index') }}"
                class="flex flex-col md:flex-row gap-3 w-full items-center">
                <div class="w-full flex justify-between gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NISN/RFID"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
                    <select name="kelas"
                        class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    <select name="jurusan"
                        class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300 focus:outline-none">
                        <option value="">Semua Jurusan</option>
                        @foreach ($jurusan as $j)
                            <option value="{{ $j->id_jurusan }}"
                                {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg transition">
                            Filter
                        </button>

                        <a href="{{ route('uks.siswa.index') }}"
                            class="border border-gray-300 hover:bg-red-500 hover:text-white text-gray-700 px-4 py-2 rounded-lg transition shadow-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel daftar siswa --}}
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600 text-sm uppercase">
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">NISN</th>
                        <th class="px-4 py-3 text-left">Kelas</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="studentTable">
                    @forelse ($siswa as $index => $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 font-medium text-gray-900">{{ strtoupper($row->nama) }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $row->nisn }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $row->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">
                                <button
                                    onclick="showActionModal('{{ $row->id_siswa }}', '{{ $row->nama }}', '{{ $row->nisn }}')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded shadow-sm transition">
                                    Aksi
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data siswa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>


    {{-- MODAL AKSI --}}
    <div id="actionModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999] transition-opacity duration-200">

        <div class="bg-white  rounded-lg shadow-xl w-96 p-6 relative animate-fadeIn">
            <!-- Close Button -->
            <button onclick="closeActionModal()"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700  transition">
                <i class="fas fa-times"></i>
            </button>

            <!-- Modal Title -->
            <h3 class="text-lg font-semibold text-gray-800  mb-4 text-center">
                Pilih Aksi
            </h3>

            <!-- Student Info -->
            <div class="text-center mb-4">
                <p id="modalStudentName" class="font-bold text-gray-900  text-lg"></p>
                <p id="modalStudentNISN" class="text-gray-600  text-sm"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3">
                <a id="rekamMedisBtn" href="#"
                    class="bg-blue-600 hover:bg-blue-700 text-white !important py-2 rounded-lg text-center font-medium shadow-sm transition">
                    Tambah Rekam Medis
                </a>

                <a id="izinPulangBtn" href="#"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg text-center font-medium shadow-sm transition">
                    Izin Pulang
                </a>
                <button onclick="closeActionModal()"
                    class="bg-gray-200  hover:bg-gray-300  text-gray-800  py-2 rounded-lg w-full font-medium transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("actionModal");
        const rfidForm = document.getElementById("rfidForm");
        const rfidInput = document.getElementById("rfidInput");

        window.showActionModal = function(studentId, studentName, studentNISN) {
            document.getElementById("modalStudentName").textContent = studentName;
            document.getElementById("modalStudentNISN").textContent = "NISN: " + studentNISN;
            document.getElementById("rekamMedisBtn").href =
                "{{ route('uks.rekam-medis.create') }}?siswa=" + studentId;
            document.getElementById("izinPulangBtn").href =
                "{{ route('uks.izin-pulang.create') }}?siswa=" + studentId;

            modal.classList.remove("hidden");
            modal.classList.add("flex");
        };

        window.closeActionModal = function() {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        };

        window.onclick = function(e) {
            if (e.target === modal) closeActionModal();
        };

        // Cari siswa via RFID/Nama/NISN
        rfidForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const input = rfidInput.value.trim();
            if (!input) return;

            try {
                const res = await fetch(`/uks/siswa/cari?query=${encodeURIComponent(input)}`);
                const result = await res.json();

                if (result.success) {
                    showActionModal(result.data.id, result.data.nama, result.data.nisn);
                } else {
                    alert("Siswa tidak ditemukan!");
                }
            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan saat mencari data.");
            }
            rfidInput.value = "";
        });
    });
</script>
