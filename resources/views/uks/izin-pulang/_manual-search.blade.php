<!-- Manual Search -->
<div class="bg-white border border-gray-200 rounded-lg p-6">
    <label for="search_siswa" class="block text-sm font-bold text-gray-700 mb-2">
        Atau Pilih Siswa Manual <span class="text-red-500">*</span>
    </label>
    <select id="search_siswa" name="id_siswa"
        class="select2-siswa block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Pilih siswa...</option>
        @foreach ($daftarSiswa as $siswa)
            <option value="{{ $siswa->id_siswa }}" data-nama="{{ $siswa->nama }}" data-nisn="{{ $siswa->nisn }}"
                data-kelas="{{ $siswa->kelas->nama_kelas }}"
                {{ old('id_siswa') == $siswa->id_siswa ? 'selected' : '' }}>
                {{ $siswa->nama }} ({{ $siswa->nisn }}) - {{ $siswa->kelas->nama_kelas }}
            </option>
        @endforeach
    </select>
</div>
