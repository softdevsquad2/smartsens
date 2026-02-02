<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Toolman')</title>
    <link rel="shortcut icon" href="{{ asset('SMKN2TASIK.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-gray-800 text-gray-100 flex flex-col">
            <img src="{{ asset('SMKN2TASIK.png') }}" alt="" class="h-20 w-20 mx-auto mt-2 mb-2 object-cover">
            <div class="p-6 text-2xl font-bold text-center border-b border-gray-700">
                Toolman
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('toolman.dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-blue-900 {{ request()->routeIs('toolman.dashboard') ? 'bg-blue-700' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('toolman.barang') }}"
                    class="block px-4 py-2 rounded hover:bg-blue-900 {{ request()->routeIs('toolman.barang') ? 'bg-blue-700' : '' }}">
                    Barang
                </a>
                <a href="{{ route('toolman.peminjaman') }}"
                    class="block px-4 py-2 rounded hover:bg-blue-900 {{ request()->routeIs('toolman.peminjaman') ? 'bg-blue-700' : '' }}">
                    Peminjaman
                </a>
                <a href="{{ route('toolman.unduh') }}"
                    class="block px-4 py-2 rounded hover:bg-blue-900 {{ request()->routeIs('toolman.unduh') ? 'bg-blue-700' : '' }}">
                    Unduh Riwayat Peminjaman
                </a>
                <a href="/toolman/settings"
                    class="block px-4 py-2 rounded hover:bg-blue-900 {{ request()->routeIs('toolman.profile.settings') ? 'bg-blue-700' : '' }}">
                    Pengaturan
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded bg-red-500 hover:bg-red-600 mt-4">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Header --}}
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h1>
                <div class="text-gray-600">
                    <i class="fa-solid fa-user mr-2"></i>Halo, <b>{{ Auth::user()->username }}</b>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('scripts')

</body>

</html>
