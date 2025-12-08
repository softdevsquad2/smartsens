@extends('layouts.pinjam')

@section('content')
    <div class="p-4 max-w-xl mx-auto">

        <h2 class="text-xl font-semibold mb-4">Hasil untuk: "{{ $keyword }}" sss</h2>

        @if ($siswa->isEmpty())
            <p class="text-gray-600">Tidak ada siswa ditemukan.</p>
        @else
            <div class="space-y-3">
                @foreach ($siswa as $s)
                    <a href="{{ route('pinjam.checkout', ['id' => $s->id_user]) }}"
                        class="block p-4 bg-white border rounded-xl shadow hover:bg-gray-50">
                        <p class="font-bold">{{ $s->nama }}</p>
                        <p class="text-sm text-gray-600">NISN: {{ $s->nisn }}</p>
                        <p class="text-sm text-gray-600">Kelas: {{ $s->nama_kelas }}</p>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
@endsection
