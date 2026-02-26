<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('SMKN2TASIK.png') }}" type="image/x-icon">
    <title>@yield('title', 'SmartSens')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-theme.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        dark: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-open {
            transform: translateX(0);
        }

        .sidebar-closed {
            transform: translateX(-100%);
        }

        /* Main content adjustment */
        .main-content-expanded {
            margin-left: 0;
        }

        .main-content-collapsed {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            .main-content-expanded {
                margin-left: 256px;
                /* 64 * 4 = 256px (w-64) */
            }

            .main-content-collapsed {
                margin-left: 0;
            }
        }

        /* Sidebar styling with new theme */
        .sidebar {
            background: linear-gradient(to bottom, #2c3e50, #34495e) !important;
        }

        .sidebar .nav-link {
            color: #e9ecef !important;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: #198754 !important;
            color: white !important;
        }

        .sidebar .nav-link.active {
            background-color: #198754 !important;
            color: white !important;
        }

        .sidebar .logo-section {
            background-color: #34495e !important;
            border-bottom: 1px solid #198754;
        }

        .sidebar .logout-section {
            border-top: 1px solid #198754;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<body class="bg-gray-50" style="background-color: #f8f9fa;">
    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 sidebar sidebar-transition sidebar-closed">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 logo-section">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">SmartSens</h1>
                        <p class="text-gray-300 text-xs">Sistem Absensi GPS</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                @yield('sidebar')
            </nav>

            <!-- Logout Button -->
            <div class="p-4 logout-section">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center space-x-3 px-4 py-3 text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="transition-all duration-300">
        <!-- Top Bar -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center space-x-4">
                    <!-- Menu Button (Mobile & Desktop) -->
                    <button id="sidebar-toggle"
                        class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <!-- Page Title -->
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">@yield('page-title')</h1>
                        <p class="text-sm text-gray-500">@yield('page-description')</p>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        @php
                            $user = auth()->user();
                            $displayName =
                                optional($user->siswa)->nama ??
                                (optional($user->waliKelas)->nama ?? ($user->username ?? 'Admin'));
                        @endphp
                        <p class="text-sm font-medium text-gray-900">{{ $displayName }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $user->role ?? 'admin' }}</p>
                    </div>
                    {{-- avatar / foto siswa --}}
                    @php $foto = optional($user->siswa)->foto; @endphp
                    @if ($foto)
                        <img src="{{ asset('storage/foto/' . $foto) }}" alt="{{ $displayName }}"
                            class="w-8 h-8 rounded-full object-cover border">
                    @else
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="p-6" style="background-color: #f8f9fa;">
            @yield('content')
        </main>
        <div id="globalLoader"
            class="fixed inset-0 z-50 flex items-center justify-center
           bg-slate-900/80 backdrop-blur-sm transition-opacity duration-300">

            <div class="flex flex-col items-center gap-4">
                <div
                    class="w-12 h-12 border-4 border-blue-500
                    border-t-transparent rounded-full animate-spin">
                </div>
                <p class="text-slate-300 text-sm">Memuat...</p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const loader = document.getElementById('globalLoader');

            // Saat halaman selesai load → sembunyikan loader
            window.addEventListener('load', function() {
                loader.classList.add('opacity-0');

                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            });

            // Saat form di-submit → tampilkan loader
            // document.querySelectorAll('form').forEach(form => {
            //     form.addEventListener('submit', function() {
            //         loader.style.display = 'flex';
            //         loader.classList.remove('opacity-0');
            //     });
            // });

        });

        function showSuccess(message, title = 'Berhasil!') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981'
            });
        }

        function showError(message, title = 'Error!') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        }

        function showWarning(message, title = 'Peringatan!') {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#f59e0b'
            });
        }

        function showInfo(message, title = 'Informasi') {
            Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
        }

        function showConfirm(message, title = 'Konfirmasi', confirmText = 'Ya', cancelText = 'Tidak') {
            return Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            });
        }

        function showLoading(message = 'Memproses...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function hideLoading() {
            Swal.close();
        }
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // STOP dulu

                confirmDelete('Yakin ingin menghapus data ini?')
                    .then(isConfirmed => {
                        if (isConfirmed) {
                            form.submit(); // baru submit kalau klik Ya
                        }
                    });
            });
        });
        // Confirm delete function for forms
        function confirmDelete(message, title = 'Konfirmasi Hapus') {
            return showConfirm(message, title, 'Ya, Hapus', 'Batal').then((result) => {
                return result.isConfirmed;
            });
        }

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainContent = document.getElementById('main-content');

        // Check if sidebar is open
        function isSidebarOpen() {
            return sidebar.classList.contains('sidebar-open');
        }

        // Toggle sidebar
        function toggleSidebar() {
            if (isSidebarOpen()) {
                sidebar.classList.remove('sidebar-open');
                sidebar.classList.add('sidebar-closed');
                sidebarOverlay.classList.add('hidden');
                mainContent.classList.remove('main-content-expanded');
                mainContent.classList.add('main-content-collapsed');
            } else {
                sidebar.classList.remove('sidebar-closed');
                sidebar.classList.add('sidebar-open');
                sidebarOverlay.classList.remove('hidden');
                mainContent.classList.remove('main-content-collapsed');
                mainContent.classList.add('main-content-expanded');
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('sidebar-closed');
            sidebar.classList.remove('sidebar-open');
            sidebarOverlay.classList.add('hidden');
            mainContent.classList.remove('main-content-expanded');
            mainContent.classList.add('main-content-collapsed');
        });

        // Initialize sidebar state
        function initializeSidebar() {
            // Start with sidebar closed
            sidebar.classList.add('sidebar-closed');
            sidebar.classList.remove('sidebar-open');
            sidebarOverlay.classList.add('hidden');
            mainContent.classList.add('main-content-collapsed');
        }

        // Initialize on page load
        initializeSidebar();

        // Display flash messages with SweetAlert
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
    @yield('scripts')
    @stack('scripts')
</body>

</html>
