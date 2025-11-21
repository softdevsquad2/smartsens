@extends('layouts.pinjam')

@section('content')
    <div class="p-4">

        <!-- Floating Cart Button -->
        <a href="{{ route('pinjam.cart') }}"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-5 py-3 rounded-full shadow-md flex items-center gap-2 hover:bg-green-600 transition">
            <i class="fa fa-shopping-cart text-2xl"></i>

            <!-- jumlah keranjang dari session -->
            <span class="font-bold text-2xl" id="cartCount">
                {{ session('cart') ? array_sum(array_column(session('cart'), 'jumlah')) : 0 }}
            </span>
        </a>

        <!-- Judul -->
        <h2 class="text-2xl font-bold mb-5">Peminjaman Barang</h2>

        <!-- Grid Barang -->
        <input type="text" id="searchInput" placeholder="Cari barang..."
            class="mb-4 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">

            @foreach ($barang as $item)
                <div class="bg-white border rounded-xl shadow hover:shadow-lg transition p-3">

                    <!-- Gambar -->
                    <img src="{{ asset('storage/' . $item->gambar) }}"
                        class="w-full h-36 object-cover rounded-lg shadow-sm">

                    <!-- Nama -->
                    <h3 class="font-semibold text-sm mt-2 line-clamp-2">
                        {{ $item->nama_barang }}
                    </h3>

                    <!-- Stok -->
                    <p class="text-gray-600 text-xs">Stok: {{ $item->stok }}</p>

                    <!-- Form tambah ke keranjang -->
                    <form class="addCartForm" method="POST" action="{{ route('pinjam.add') }}">
                        @csrf
                        <input type="hidden" name="id_barang" value="{{ $item->id_barang }}">

                        <button type="submit" {{ $item->stok < 1 ? 'disabled' : '' }}
                            class="mt-3 w-full {{ $item->stok < 1 ? 'bg-blue-600' : '' }} bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600">
                            <i class="fa fa-cart-plus text-xl px-1"></i>Tambah
                        </button>
                    </form>

                </div>
            @endforeach

        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.grid > div');

            cards.forEach(card => {
                let nama = card.querySelector('h3').innerText.toLowerCase();
                if (nama.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        @if (session('success'))
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "{{ session('success') }}",
                // timer: 1500,
                showConfirmButton: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "{{ session('error') }}",
                timer: 1800,
                showConfirmButton: false
            });
        @endif
    </script>
    <script>
        // Setup fetch AJAX untuk semua form
        document.querySelectorAll('.addCartForm').forEach(form => {
            form.addEventListener('submit', function(e) {

                e.preventDefault();

                let btn = this.querySelector("button[type='submit']");

                // 🔵 Disable tombol setelah diklik
                btn.disabled = true;
                btn.classList.add("opacity-50", "cursor-not-allowed");
                btn.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Memproses...`;

                let formData = new FormData(this);

                fetch(this.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData,
                        credentials: "include"
                    })
                    .then(res => res.text())
                    .then(() => {

                        // update count keranjang
                        let countEl = document.getElementById('cartCount');
                        countEl.innerText = parseInt(countEl.innerText) + 1;

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Barang ditambahkan ke keranjang',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        // 🔵 Aktifkan lagi tombolnya setelah sukses
                        btn.disabled = false;
                        btn.classList.remove("opacity-50", "cursor-not-allowed");
                        btn.innerHTML = `<i class="fa fa-cart-plus text-xl px-1"></i>Tambah`;
                    })
                    .catch(() => {
                        // Jika gagal, tombol tetap diaktifkan kembali
                        btn.disabled = false;
                        btn.classList.remove("opacity-50", "cursor-not-allowed");
                        btn.innerHTML = `<i class="fa fa-cart-plus text-xl px-1"></i>Tambah`;

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menambahkan ke keranjang',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    });
            });
        });
    </script>
@endsection
