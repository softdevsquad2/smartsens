@extends('layouts.pinjam')

@section('content')
    <div class="p-4 max-w-xl mx-auto">

        <h2 class="text-2xl font-bold mb-5 text-center">Cari Siswa</h2>

        <form action="{{ route('kembali.cari.hasil') }}" method="GET">
            <input type="text" name="q" required placeholder="Masukkan nama atau NISN..."
                class="w-full p-3 border rounded-lg mb-4">

            <button class="w-full bg-blue-600 text-white p-3 rounded-lg">Cari</button>
        </form>

    </div>
@endsection
