<!-- Alasan -->
<div class="bg-white border border-gray-200 rounded-lg p-6">
    <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">
        Alasan Izin Pulang <span class="text-red-500">*</span>
    </label>
    <div class="relative">
        <textarea name="keterangan" id="keterangan" rows="4"
            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white placeholder-gray-400"
            placeholder="Masukkan alasan izin pulang...">{{ old('keterangan') }}</textarea>
    </div>
    @error('keterangan')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>
