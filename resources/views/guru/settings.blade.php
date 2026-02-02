@extends('layouts.app')

@section('title', 'Pengaturan - SmartSens')
@section('page-title', 'Pengaturan')
@section('page-description', 'Kelola pengaturan akun guru')

@section('sidebar')
    <!-- Dashboard -->
    <a href="{{ route('guru.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <!-- Daftar Siswa -->
    <a href="{{ route('guru.siswa.index') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-users"></i>
        <span>Daftar Siswa</span>
    </a>

    <!-- Absensi Hari Ini -->
    <a href="{{ route('guru.absensi.hari-ini') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-calendar-day"></i>
        <span>Absensi Hari Ini</span>
    </a>

    <!-- Laporan Absensi -->
    <a href="{{ route('guru.absensi.laporan') }}"
        class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
        <i class="fas fa-chart-bar"></i>
        <span>Laporan Absensi</span>
    </a>

    <!-- Pengaturan -->
    <a href="{{ route('guru.settings') }}" class="flex items-center space-x-3 px-4 py-3 text-white bg-blue-600 rounded-lg">
        <i class="fas fa-cog"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Akun</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola informasi akun Anda</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Informasi Akun -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-user mr-2 text-blue-500"></i>
            Informasi Akun
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Username</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $user->username }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-shield-alt text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Role</p>
                    <p class="text-sm font-semibold text-gray-900">{{ ucfirst($user->role) }}</p>
                </div>
            </div>

            @if($waliKelas)
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $waliKelas->nama }}</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-school text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Kelas</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $waliKelas->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Form Pengaturan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-user-cog mr-2 text-blue-500"></i>
            Ubah Username & Password
        </h3>

        <form id="credential-form" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username Saat Ini</label>
                <p class="text-sm font-semibold text-gray-900 px-4 py-2 bg-gray-50 rounded-lg">{{ auth()->user()->username }}</p>
            </div>

            <div>
                <label for="username_baru" class="block text-sm font-medium text-gray-700 mb-2">Username Baru</label>
                <input id="username_baru" name="username_baru" type="text" placeholder="Masukkan username baru (opsional)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password_lama" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini <span class="text-red-500">*</span></label>
                <input id="password_lama" name="password_lama" type="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password_baru" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input id="password_baru" name="password_baru" type="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*?&).</p>
            </div>

            <div>
                <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <input id="password_confirm" name="password_confirm" type="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div id="credential-errors" class="text-sm text-red-600"></div>

            <div class="flex items-center justify-end space-x-3 pt-4">
                <button type="submit" id="btn-credential-submit"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const form = document.getElementById('credential-form');
            const btn = document.getElementById('btn-credential-submit');
            const errorsEl = document.getElementById('credential-errors');

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                errorsEl.textContent = '';

                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

                const url = "{{ route('guru.profile.update-credentials', ['userId' => auth()->id()]) }}";
                const formData = new FormData(form);

                try {
                    const resp = await fetch(url, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await resp.json();

                    if (data.success) {
                        if (data.redirect_url) {
                            showSuccess(data.message || 'Kredensial berhasil diperbarui. Silakan login kembali.', 'Berhasil');
                            setTimeout(() => { window.location.href = data.redirect_url; }, 1500);
                            return;
                        }

                        showSuccess(data.message || 'Kredensial berhasil diperbarui.');
                        // Reset form
                        form.reset();
                    } else {
                        if (data.errors) {
                            const msgs = [];
                            for (const k in data.errors) {
                                msgs.push(...data.errors[k]);
                            }
                            errorsEl.innerHTML = msgs.map(m => `<div class="mb-2">• ${m}</div>`).join('');
                        } else {
                            errorsEl.textContent = data.message || 'Gagal memperbarui kredensial.';
                        }
                        showError(data.message || 'Gagal memperbarui kredensial.');
                    }
                } catch (err) {
                    console.error(err);
                    showError('Terjadi kesalahan jaringan atau server.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        })();
    </script>
@endsection
