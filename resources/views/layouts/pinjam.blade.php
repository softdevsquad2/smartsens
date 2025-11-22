<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Applikasi Inventaris' }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('SMKN2TASIK.png') }}" type="image/x-icon">
    <!-- Font Awesome (icon keranjang) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @yield('head')
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-5 flex justify-between items-center">
            <div class="flex gap-2 items-center">
                <img src="{{ asset('SMKN2TASIK.png') }}" alt="Logo" class="h-10 w-10 ">
                <h1 class="text-2xl font-bold text-blue-600">Inventaris</h1>
            </div>

            <div class="flex items-center space-x-4">
                <button
                    class="border border-blue-400 bg-blue-500 rounded-md px-2 py-2 hover:bg-blue-600 text-white hover:text-white shadow-lg"><i
                        class="fa-solid fa-house px-1"></i><a href="{{ route('pinjam.index') }}">Home</a></button>
                <button
                    class="border border-blue-400 bg-blue-500 rounded-md px-2 py-2 hover:bg-blue-600 text-white hover:text-white shadow-lg"><i
                        class="fa-solid fa-rotate-left px-1"></i><a href="{{ route('kembali.pilih') }}">Kembalikan
                        Barang</a></button>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="pt-20 pb-10">
        @yield('content')
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')

</body>

</html>
