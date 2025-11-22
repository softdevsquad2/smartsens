@extends('layouts.pinjam')

@section('content')
    <div class="p-4 max-w-xl mx-auto text-center">

        <h2 class="text-2xl font-bold mb-4">Scan Kartu Siswa</h2>
        <p class="text-gray-600 mb-6">Tempelkan kartu RFID siswa ke reader…</p>

        <!-- INPUT OTOMATIS -->
        <input type="text" id="rfidInput" autofocus class="w-full p-4 text-center border rounded-lg text-xl tracking-widest"
            placeholder="Menunggu kartu..." />

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let input = document.getElementById("rfidInput");

            input.focus();

            // ketika RFID masuk otomatis (biasanya enter di akhir)
            input.addEventListener("input", function() {

                let card = this.value.trim();

                if (card.length < 5) return; // cegah false trigger (optional)

                fetch(`/api/rfid/${card}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Kartu Terbaca!",
                                text: "Siswa: " + data.nama,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('pinjam.checkout') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: data.message
                            });
                        }
                    });

                this.value = ""; // reset input supaya bisa scan lagi
            });
        });
    </script>
@endsection
