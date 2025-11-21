<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIKET - SmartSens')</title>
    <link rel="shortcut icon" href="{{ asset('SMKN2TASIK.png') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
        }

        .dropdown-item:active {
            background-color: #0ea5e9;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-primary-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a class="flex items-center flex-shrink-0 text-white" href="{{ route('piket.dashboard') }}">
                        {{-- <i class="fas fa-clipboard-check text-2xl mr-2"></i> --}}
                        <img src="{{ asset('SMKN2TASIK.png') }}" class="w-10" alt="">
                        <span class="font-bold text-xl">PIKET</span>
                        <span class="text-primary-200 ml-2">SmartSens</span>
                    </a>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4">
                        <a href="{{ route('piket.dashboard') }}"
                            class="nav-link {{ request()->routeIs('piket.dashboard') ? 'active' : '' }} px-3 py-3 text-sm font-medium text-white hover:bg-primary-500 rounded-md transition">
                            <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                        </a>
                        <a href="{{ route('piket.siswa.index') }}"
                            class="nav-link {{ request()->routeIs('piket.siswa.*') ? 'active' : '' }} px-3 py-3 text-sm font-medium text-white hover:bg-primary-500 rounded-md transition">
                            <i class="fas fa-users mr-1"></i>Daftar Siswa
                        </a>
                        <a href="{{ route('piket.izin-pulang') }}"
                            class="nav-link {{ request()->routeIs('piket.izin-pulang*') ? 'active' : '' }} px-3 py-3 text-sm font-medium text-white hover:bg-primary-500 rounded-md transition">
                            <i class="fas fa-sign-out-alt mr-1"></i>Izin Pulang
                        </a>
                        <a href="{{ route('piket.riwayat-absen') }}"
                            class="nav-link {{ request()->routeIs('piket.riwayat-absen*') ? 'active' : '' }} px-3 py-3 text-sm font-medium text-white hover:bg-primary-500 rounded-md transition">
                            <i class="fas fa-history mr-2"></i>Riwayat Absen
                        </a>
                        <a href="{{ route('piket.laporan') }}"
                            class="nav-link {{ request()->routeIs('piket.laporan*') ? 'active' : '' }} block px-3 py-3 text-white font-medium hover:bg-primary-600 rounded-md">
                            <i class="fas fa-file-excel mr-1"></i>Unduh Laporan
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center text-white hover:text-primary-100 focus:outline-none transition">
                                <i class="fas fa-user-circle text-xl mr-1"></i>
                                <span class="text-sm font-medium mr-2">{{ Auth::user()->username }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" x-cloak
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                                role="menu">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition"
                                        role="menuitem">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="sm:hidden bg-primary-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('piket.dashboard') }}"
                    class="nav-link {{ request()->routeIs('piket.dashboard') ? 'active' : '' }} block px-3 py-2 text-white font-medium hover:bg-primary-600 rounded-md">
                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                </a>
                <a href="{{ route('piket.siswa.index') }}"
                    class="nav-link {{ request()->routeIs('piket.siswa.*') ? 'active' : '' }} block px-3 py-2 text-white font-medium hover:bg-primary-600 rounded-md">
                    <i class="fas fa-users mr-1"></i>Daftar Siswa
                </a>
                <a href="{{ route('piket.izin-pulang') }}"
                    class="nav-link {{ request()->routeIs('piket.izin-pulang*') ? 'active' : '' }} block px-3 py-2 text-white font-medium hover:bg-primary-600 rounded-md">
                    <i class="fas fa-sign-out-alt mr-1"></i>Izin Pulang
                </a>
                <a href="{{ route('piket.riwayat-absen') }}"
                    class="nav-link {{ request()->routeIs('piket.riwayat-absen*') ? 'active' : '' }} block px-3 py-2 text-white font-medium hover:bg-primary-600 rounded-md">
                    <i class="fas fa-sign-out-alt mr-1"></i>Riwayat Absen
                </a>
                <a href="{{ route('piket.laporan') }}"
                    class="nav-link {{ request()->routeIs('piket.unduh-laporan*') ? 'active' : '' }} block px-3 py-2 text-white font-medium hover:bg-primary-600 rounded-md">
                    <i class="fas fa-file-excel mr-1"></i>Unduh Laporan
                </a>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>

</html>
