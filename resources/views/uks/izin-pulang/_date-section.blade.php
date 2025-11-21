<!-- Tanggal -->
<div class="bg-white border border-gray-200 rounded-lg p-6">
    <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-2">
        Tanggal <span class="text-red-500">*</span>
    </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-calendar text-gray-400"></i>
        </div>
        <input type="date" name="tanggal" id="tanggal"
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
            value="{{ old('tanggal', date('Y-m-d')) }}" required>
    </div>
    @error('tanggal')
        <p class="mt-1 text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
        </p>
    @enderror
</div>
