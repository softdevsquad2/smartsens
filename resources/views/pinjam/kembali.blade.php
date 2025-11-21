@extends('layouts.pinjam')

@section('content')
    <div class="p-4">

        <h2 class="text-2xl font-bold mb-5">
            Barang dipinjam {{ $user->siswa->nama }}
        </h2>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($peminjaman->isEmpty())
            <div class="bg-white p-5 rounded-xl shadow text-center">
                <p class="text-gray-600">Tidak ada barang yang dipinjam</p>
                <a href="{{ route('pinjam.index') }}"
                    class="mt-4 inline-block bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition">
                    Pinjam Barang
                </a>
            </div>
        @else
            <!-- FORM PENGEMBALIAN -->
            <form method="POST" action="{{ route('pinjam.kembalikan.proses') }}">
                @csrf

                <div class="bg-white p-4 rounded-xl shadow mb-5">
                    @foreach ($peminjaman as $item)
                        <label class="flex items-center justify-between border-b py-3 cursor-pointer">

                            <div class="flex items-center gap-3">

                                <!-- Checkbox -->
                                <input type="checkbox" name="barang[]" value="{{ $item->id_peminjaman }}"
                                    class="w-5 h-5 accent-green-600">

                                <!-- Gambar -->
                                <img src="{{ asset('storage/' . $item->barang->gambar) }}"
                                    class="w-16 h-16 object-cover rounded">

                                <!-- Detail -->
                                <div>
                                    <p class="font-semibold">{{ $item->barang->nama_barang }}</p>
                                    <p class="text-gray-600 text-sm">Jumlah: {{ $item->jumlah }}</p>
                                </div>

                            </div>

                        </label>
                    @endforeach
                </div>

                <!-- Tombol kembalikan -->
                <button type="submit"
                    class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Kembalikan Barang Terpilih
                </button>

            </form>
        @endif

    </div>
@endsection
@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        @endif
    </script>
@endsection