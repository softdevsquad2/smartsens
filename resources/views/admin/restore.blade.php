@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Restore Database</h2>

        {{-- Pesan sukses / error --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form upload file SQL --}}
        <form action="{{ route('admin.restore.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="sql_file" class="block text-sm font-medium text-gray-700">Pilih File SQL</label>
                <input type="file" name="sql_file" id="sql_file" accept=".sql" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg p-2">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all">
                Restore Sekarang
            </button>
        </form>
    </div>
@endsection
