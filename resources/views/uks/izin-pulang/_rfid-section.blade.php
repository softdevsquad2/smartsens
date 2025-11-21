<!-- Siswa RFID Selection -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
    <label for="rfid_input" class="block text-sm font-bold text-blue-700 mb-2">
        Tap Kartu RFID Siswa <span class="text-red-500">*</span>
    </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-id-card text-blue-400"></i>
        </div>
        <input type="text" id="rfid_input" name="rfid_input"
            class="block w-full pl-10 pr-3 py-3 border border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white placeholder-blue-300"
            placeholder="Tempelkan kartu RFID siswa..." autocomplete="off" readonly>
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <div id="rfid_status" class="w-3 h-3 bg-gray-300 rounded-full"></div>
        </div>
    </div>
    <input type="hidden" name="id_siswa" id="id_siswa_hidden"
        value="{{ old('id_siswa', $selectedSiswa->id_siswa ?? '') }}">
    <p class="mt-2 text-xs text-blue-700 flex items-center">
        <i class="fas fa-info-circle mr-1"></i>
        Status: <span id="rfid_status_text">Menunggu kartu RFID</span>
    </p>
    <div id="selected_student_info"
        class="mt-3 {{ isset($selectedSiswa) ? 'flex items-center space-x-3' : 'hidden' }} p-3 bg-blue-100 rounded-lg border border-blue-200">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-user text-white"></i>
        </div>
        <div>
            <span id="selected_student_name" class="font-semibold text-blue-900">{{ $selectedSiswa->nama ?? '' }}</span>
            <span id="selected_student_nisn" class="text-xs text-blue-700 ml-2">{{ $selectedSiswa->nisn ?? '' }}</span>
        </div>
    </div>
    @error('id_siswa')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>
