@extends('layouts.pinjam')

@section('content')
    <div class="p-4">

        <h2 class="text-2xl font-bold mb-5">Checkout Peminjaman</h2>

        @if (empty($cart))
            <div class="bg-white p-5 rounded-xl shadow text-center">
                <p class="text-gray-600">Keranjang masih kosong.</p>
                <a href="{{ route('pinjam.index') }}"
                    class="mt-4 inline-block bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600 transition">
                    Kembali Belanja
                </a>
            </div>
        @else
            <!-- List Barang -->
            <div class="bg-white p-4 rounded-xl shadow mb-5">

                @foreach ($cart as $item)
                    <div class="flex items-center justify-between border-b py-3">

                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . $item['gambar']) }}" class="w-16 h-16 object-cover rounded">

                            <div>
                                <p class="font-semibold">{{ $item['nama_barang'] }}</p>
                                <p class="text-gray-600 text-sm">Jumlah: {{ $item['jumlah'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Form Checkout -->
            <div class="bg-white p-5 rounded-xl shadow">
                <form id="formCheckout" action="{{ route('pinjam.process') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="font-semibold text-sm">Tujuan Peminjaman</label>
                        <input type="text" name="tujuan" required class="w-full mt-2 px-3 py-2 border rounded-lg"
                            placeholder="Contoh: Acara sekolah / kegiatan kelas / teknisi">
                    </div>

                    {{-- <div class="mb-4">
                        <label class="font-semibold text-sm">Tanggal Kembali (Opsional)</label>
                        <input type="date" name="tanggal_kembali" class="w-full mt-2 px-3 py-2 border rounded-lg">
                    </div> --}}

                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Pinjam
                    </button>

                </form>
            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Tambahkan popup konfirmasi sebelum submit
        document.getElementById('formCheckout')?.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Yakin data sudah benar?",
                text: "Data tidak bisa diubah setelah disimpan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yakin!"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
@endsection
