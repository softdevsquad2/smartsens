@extends('layouts.app')

@section('title', 'Preview Import Siswa')

@section('content')
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Preview Import Siswa</h1>

        <p class="mb-4 text-sm text-gray-600">Periksa data di bawah ini. Jika sudah sesuai, klik <strong>Konfirmasi
                Import</strong>.</p>

        <div class="overflow-auto border rounded p-3 bg-white">
            <table class="min-w-full table-auto">
                <thead>
                    <tr>
                        @foreach ($headers as $h)
                            <th class="px-2 py-1 border text-left text-sm">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($preview as $rIndex => $row)
                        <tr class="hover:bg-gray-50">
                            @foreach ($row as $c)
                                <td class="px-2 py-1 border text-sm">{{ $c }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('siswa.import') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="stored_file" value="{{ $stored_file }}">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Konfirmasi Import</button>
            <a href="{{ route('siswa.index') }}" class="ml-3 px-4 py-2 bg-gray-200 rounded">Batal</a>
        </form>
    </div>
@endsection
