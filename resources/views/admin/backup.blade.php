@extends('layouts.app')

@section('title', 'Backup Data - SmartSens')
@section('page-title', 'Backup Data')
@section('page-description', 'Buat dan unduh backup data absensi sistem')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">Backup Data Sistem</h2>
        <p class="text-gray-600 mb-6">
            Klik tombol di bawah ini untuk membuat dan mengunduh file backup database secara otomatis.
        </p>

        <button onclick="backupNow()"
            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-database mr-2"></i> Backup Sekarang
        </button>
    </div>

    <script>
        function backupNow() {
            Swal.fire({
                title: 'Backup Data',
                text: 'Apakah Anda yakin ingin membuat backup database sekarang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Backup',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/admin/backup';
                }
            });
        }
    </script>
@endsection
