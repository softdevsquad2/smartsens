<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIKET - SmartSens')</title>

    <link rel="shortcut icon" href="{{ asset('SMKN2TASIK.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            600: '#0284c7',
                            700: '#0369a1'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: false }">

    <!-- MOBILE HEADER -->
    <header class="md:hidden bg-primary-600 text-white flex items-center justify-between px-4 py-3">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('SMKN2TASIK.png') }}" class="w-8">
            <span class="font-bold">PIKET</span>
        </div>
        <button @click="sidebarOpen = true">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </header>

    <!-- SIDEBAR OVERLAY (Mobile) -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black/50 z-40 md:hidden"
        @click="sidebarOpen = false"></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-primary-700 text-white z-50 transform transition-transform duration-300
               md:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- LOGO -->
        <div class="flex items-center space-x-2 px-6 py-4 border-b border-primary-600">
            <img src="{{ asset('SMKN2TASIK.png') }}" class="w-10">
            <div>
                <p class="font-bold leading-none">PIKET</p>
                <p class="text-xs text-primary-200">SmartSens</p>
            </div>
        </div>

        <!-- MENU -->
        <nav class="px-4 py-4 space-y-1">
            @php
                $menuClass = 'flex items-center px-4 py-3 rounded-lg transition hover:bg-primary-600';
                $activeClass = 'bg-primary-600';
            @endphp

            <a href="{{ route('piket.dashboard') }}"
                class="{{ $menuClass }} {{ request()->routeIs('piket.dashboard') ? $activeClass : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            <a href="{{ route('piket.siswa.index') }}"
                class="{{ $menuClass }} {{ request()->routeIs('piket.siswa.*') ? $activeClass : '' }}">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3">Daftar Siswa</span>
            </a>

            <a href="{{ route('piket.izin-pulang') }}"
                class="{{ $menuClass }} {{ request()->routeIs('piket.izin-pulang*') ? $activeClass : '' }}">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="ml-3">Izin Pulang</span>
            </a>

            <a href="{{ route('piket.riwayat-absen') }}"
                class="{{ $menuClass }} {{ request()->routeIs('piket.riwayat-absen*') ? $activeClass : '' }}">
                <i class="fas fa-history w-5"></i>
                <span class="ml-3">Riwayat Absen</span>
            </a>

            <a href="{{ route('piket.laporan') }}"
                class="{{ $menuClass }} {{ request()->routeIs('piket.laporan*') ? $activeClass : '' }}">
                <i class="fas fa-file-excel w-5"></i>
                <span class="ml-3">Unduh Laporan</span>
            </a>
        </nav>

        <!-- USER -->
        <div class="absolute bottom-0 w-full border-t border-primary-600 px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ Auth::user()->username }}</p>
                    <p class="text-xs text-primary-200">Petugas Piket</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-300 hover:text-red-400">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="md:ml-64 px-4 py-6">
        @yield('content')
    </main>

    <!-- SWEET ALERT -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
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
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

</body>

</html>
