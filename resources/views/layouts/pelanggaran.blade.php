<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('SMKN2TASIK.png') }}" type="image/x-icon">

    <title>@yield('title', 'SmartSens')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Style -->
    <style>
        .loading-bars span {
    width: 8px;
    height: 16px;
    background: linear-gradient(to top, #2563eb, #60a5fa);
    border-radius: 4px;
    animation: wave 1.2s ease-in-out infinite;
    box-shadow: 0 0 8px rgba(37, 99, 235, 0.6);
}

.loading-bars span:nth-child(1) { animation-delay: 0s; }
.loading-bars span:nth-child(2) { animation-delay: 0.15s; }
.loading-bars span:nth-child(3) { animation-delay: 0.3s; }
.loading-bars span:nth-child(4) { animation-delay: 0.45s; }

@keyframes wave {
    0%, 100% {
        height: 16px;
        opacity: 0.6;
    }
    50% {
        height: 40px;
        opacity: 1;
    }
}

        .sidebar {
            background: linear-gradient(to bottom, #2c3e50, #34495e);
        }

        .logo-section {
            background-color: #34495e;
            border-bottom: 1px solid #198754;
        }

        .logout-section {
            border-top: 1px solid #198754;
        }
          #page-loading {
        transition: opacity .4s ease;
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-gray-100">
<!-- LOADING SCREEN -->
<div id="page-loading"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-white">

    <div class="flex flex-col items-center gap-4">
        <!-- Animation -->
        <div class="flex items-end gap-2 loading-bars">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

        <!-- Text -->
        <p class="text-blue-600 font-semibold tracking-wide">
            Memuat SmartSens...
        </p>
    </div>
</div>

    <!-- OVERLAY (MOBILE) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 sidebar
              transform -translate-x-full md:translate-x-0
              transition-transform duration-300 ease-in-out">

        <div class="flex flex-col h-full text-white">

            <!-- LOGO -->
            <div class="logo-section h-16 flex items-center justify-center px-4 py-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('SMKN2TASIK.png') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover">

                    <div>
                        <h1 class="font-bold text-lg">SmartSens</h1>
                        <p class="text-xs text-gray-300">Sistem Absensi GPS</p>
                    </div>
                </div>
            </div>

            <!-- NAV -->
            <nav class="flex-1 px-4 py-6 space-y-2">

                <!-- Dashboard & Laporan -->
                <a href="{{ route('pelanggaran.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg
                   {{ request()->routeIs('pelanggaran.index') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('pelanggaran.riwayat') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg
                   {{ request()->routeIs('pelanggaran.riwayat') || request()->routeIs('pelanggaran.riwayat.detail') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                  <i class="fas fa-user-graduate"></i>
<span>Data Siswa</span>

                </a>
                <a href="{{ route('pelanggaran.unduh') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg
                   {{ request()->routeIs('pelanggaran.unduh') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-download"></i>
                    <span>Unduh Laporan</span>
                </a>

                <!-- Pelanggaran Group -->
                <div>
                    <button onclick="toggleDropdown('pelanggaran-menu')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Pelanggaran</span>
                        </div>
                        <i id="pelanggaran-icon" class="fas fa-chevron-down text-sm transform transition-transform"></i>
                    </button>
                    <div id="pelanggaran-menu" class="hidden ml-4 space-y-1 mt-1">
                        <a href="{{ route('pelanggaran.pelanggaran') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                           {{ request()->routeIs('pelanggaran.pelanggaran') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                            <i class="fas fa-list"></i>
                            <span>Kelola Jenis</span>
                        </a>
                        <a href="{{ route('pelanggaran.rekam.list') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                           {{ request()->routeIs('pelanggaran.rekam.list') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                            <i class="fas fa-eye"></i>
                            <span>List Pelanggaran</span>
                        </a>
                        {{-- <a href="{{ route('guru.pelanggaran.rekam') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                           {{ request()->routeIs('guru.pelanggaran.rekam') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                            <i class="fas fa-plus"></i>
                            <span>Rekam Pelanggaran</span>
                        </a> --}}
                    </div>
                </div>

                <!-- Prestasi Group -->
                <div>
                    <button onclick="toggleDropdown('prestasi-menu')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-trophy"></i>
                            <span>Prestasi</span>
                        </div>
                        <i id="prestasi-icon" class="fas fa-chevron-down text-sm transform transition-transform"></i>
                    </button>
                    <div id="prestasi-menu" class="hidden ml-4 space-y-1 mt-1">
                        <a href="{{ route('pelanggaran.prestasi.list') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                           {{ request()->routeIs('pelanggaran.prestasi.list') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                            <i class="fas fa-eye"></i>
                            <span>List Prestasi</span>
                        </a>
                        {{-- <a href="{{ route('guru.prestasi.rekam') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                           {{ request()->routeIs('guru.prestasi.rekam') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                            <i class="fas fa-plus"></i>
                            <span>Rekam Prestasi</span>
                        </a> --}}
                        <a href="{{ route('pelanggaran.prestasi.manage') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                           {{ request()->routeIs('pelanggaran.prestasi.manage') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                            <i class="fas fa-edit"></i>
                            <span>Kelola Prestasi Siswa</span>
                        </a>

                    </div>
                </div>
 <a href="{{ route('pelanggaran.settings') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg mb-3
                   {{ request()->routeIs('pelanggaran.settings') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <!-- LOGOUT -->
            <div class="logout-section p-4">

                <form method="POST" action="/logout">
                    @csrf
                    <button
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg
                               text-red-400 hover:text-red-300 hover:bg-red-900/20">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div id="main-content" class="md:ml-64 min-h-screen">

        <!-- TOPBAR -->
        <header class="bg-white border-b shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">

                <div class="flex items-center gap-4">
                    <!-- TOGGLE (MOBILE ONLY) -->
                    <button id="sidebar-toggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <div>
                        <h1 class="text-xl font-semibold">@yield('page-title')</h1>
                        <p class="text-sm text-gray-500">@yield('page-description')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-left">
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ auth()->user()->username ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role ?? 'admin' }}</p>
                    </div>
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                </div>

            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="p-6">
            @yield('content')
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert helper functions -->
    <script>
        function showSuccess(message, title = 'Berhasil!') {
            Swal.fire({ icon: 'success', title: title, text: message, confirmButtonText: 'OK', confirmButtonColor: '#10b981' });
        }

        function showError(message, title = 'Error!') {
            Swal.fire({ icon: 'error', title: title, text: message, confirmButtonText: 'OK', confirmButtonColor: '#ef4444' });
        }

        function showWarning(message, title = 'Peringatan!') {
            Swal.fire({ icon: 'warning', title: title, text: message, confirmButtonText: 'OK', confirmButtonColor: '#f59e0b' });
        }

        function showInfo(message, title = 'Informasi') {
            Swal.fire({ icon: 'info', title: title, text: message, confirmButtonText: 'OK', confirmButtonColor: '#3b82f6' });
        }

        // Display flash messages if present
        @if (session('success'))
            showSuccess('{{ session('success') }}');
        @endif

        @if (session('error'))
            showError('{{ session('error') }}');
        @endif

        @if (session('warning'))
            showWarning('{{ session('warning') }}');
        @endif

        @if (session('info'))
            showInfo('{{ session('info') }}');
        @endif

        // Display validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showError('{{ $error }}');
            @endforeach
        @endif
    </script>

    <!-- JS -->
    <script>
    window.addEventListener('load', () => {
        const loader = document.getElementById('page-loading');
        loader.classList.add('opacity-0');
        setTimeout(() => loader.remove(), 400);
    });
</script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Dropdown menu toggle function
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(menuId.replace('-menu', '-icon'));

            menu.classList.toggle('hidden');

            if (icon) {
                icon.classList.toggle('rotate-180');
            }
        }

        // Auto-expand dropdown if any child link is active
        document.addEventListener('DOMContentLoaded', function() {
            const activeLinks = document.querySelectorAll('a.bg-blue-600');
            activeLinks.forEach(link => {
                let parent = link.closest('[id$="-menu"]');
                if (parent) {
                    parent.classList.remove('hidden');
                    const menuId = parent.id.replace('-menu', '-icon');
                    const icon = document.getElementById(menuId);
                    if (icon) {
                        icon.classList.add('rotate-180');
                    }
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
    @stack('scripts')

</body>

</html>
