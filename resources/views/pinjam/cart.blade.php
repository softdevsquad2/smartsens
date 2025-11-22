@extends('layouts.pinjam')

@section('content')
    <div class="p-4">

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-5">Keranjang Peminjaman</h2>

        @if (empty($cart))
            <div class="bg-white p-5 rounded-xl shadow text-center">
                <p class="text-gray-600">Keranjang masih kosong.</p>
                <a href="{{ route('pinjam.index') }}"
                    class="mt-4 inline-block bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600 transition">
                    Kembali Belanja
                </a>
            </div>
        @else
            <div class="bg-white p-4 rounded-xl shadow mb-5">

                @foreach ($cart as $item)
                    <div class="flex items-center justify-between border-b py-3">

                        <div class="flex items-center gap-3">

                            <!-- Gambar -->
                            <img src="{{ asset('storage/' . $item['gambar']) }}" class="w-16 h-16 object-cover rounded">

                            <div>
                                <!-- Nama -->
                                <p class="font-semibold">{{ $item['nama_barang'] }}</p>

                                <!-- Qty control -->
                                <div class="flex items-center mt-2">

                                    <!-- Kurangi -->
                                    <form action="{{ route('pinjam.updateQty') }}" method="POST" class="updateQtyForm">
                                        @csrf
                                        <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                                        <input type="hidden" name="action" value="minus">

                                        <button type="submit"
                                            class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center hover:bg-gray-300">
                                            <i class="fa fa-minus text-sm"></i>
                                        </button>
                                    </form>

                                    <!-- Jumlah -->
                                    <div class="mx-3 w-10 text-center font-semibold">
                                        {{ $item['jumlah'] }}
                                    </div>

                                    <!-- Tambah -->
                                    <form action="{{ route('pinjam.updateQty') }}" method="POST" class="updateQtyForm">
                                        @csrf
                                        <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                                        <input type="hidden" name="action" value="plus">

                                        <button type="submit"
                                            class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center hover:bg-gray-300">
                                            <i class="fa fa-plus text-sm"></i>
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>

                        <!-- Tombol hapus -->
                        <form method="POST" action="{{ route('pinjam.remove', $item['id_barang']) }}">
                            @csrf
                            <button class="text-red-500 hover:text-red-700">
                                <i class="fa fa-trash text-xl mr-3"></i>
                            </button>
                        </form>

                    </div>
                @endforeach


            </div>

            <!-- Button Checkout -->
            <a href="{{ route('pinjam.pilih') }}"
                class="w-full block text-center bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Lanjut ke checkout
            </a>
        @endif

    </div>
@endsection
