<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ExternalRedirectController;
use App\Http\Controllers\JurusanManageController;
use App\Http\Controllers\KelasManageController;
use App\Http\Controllers\PiketController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaManageController;
use App\Http\Controllers\ToolmanController;
use App\Http\Controllers\UksController;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\WaliKelasManageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Route untuk absensi GPS - redirect ke login
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/phpmyadmin', function () {
    return redirect()->to('http://127.0.0.1/phpmyadmin')->send();
});

// Auth Routes

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Halaman barang
Route::get('/pinjam', [BarangController::class, 'index'])->name('pinjam.index');

// Keranjang
Route::get('/pinjam/cart', [BarangController::class, 'cart'])->name('pinjam.cart');

// Tambah ke keranjang
Route::post('/pinjam/add', [BarangController::class, 'addToCart'])->name('pinjam.add');

// Checkout
Route::get('/pinjam/checkout', [BarangController::class, 'checkout'])->name('pinjam.checkout');
Route::get('/pinjam/pilih', [BarangController::class, 'pilih'])->name('pinjam.pilih');
Route::get('/pinjam/scan-card', [BarangController::class, 'scanCard'])
    ->name('pinjam.scan.card');
// Route::get('/pinjam/pilih', [BarangController::class, 'pilih'])
//     ->name('pinjam.pilih');
// Proses checkout
Route::post('/pinjam/checkout/process', [BarangController::class, 'processCheckout'])->name('pinjam.process');
Route::post('/pinjam/remove/{id}', [BarangController::class, 'removeItem'])->name('pinjam.remove');
// Halaman scan QR (uses ScanController)


Route::get('/api/get-siswa-by-nisn/{nisn}', function ($nisn) {
    $siswa = DB::table('tbl_siswa')
        ->join('tbl_kelas', 'tbl_siswa.id_kelas', '=', 'tbl_kelas.id_kelas')
        ->where('nisn', $nisn)
        ->select('tbl_siswa.*', 'tbl_kelas.nama_kelas as kelas')
        ->first();

    if (!$siswa) {
        return response()->json(['status' => false]);
    }

    return response()->json([
        'status' => true,
        'siswa' => $siswa
    ]);
});



// Store peminjaman (form from scan checkout)
Route::post('/peminjaman/store', [BarangController::class, 'store'])->name('peminjaman.store');

Route::get('/pinjam/scan', [BarangController::class, 'scanPage'])->name('pinjam.scan');
Route::post('/pinjam/scan/process', [BarangController::class, 'scanProcess'])->name('pinjam.scan.process');
Route::post('/pinjam/update-qty', [BarangController::class, 'updateQty'])->name('pinjam.updateQty');


// kemablikan barang
Route::post('/pinjam/kembali/process', [BarangController::class, 'processKembalikan'])->name('pinjam.kembalikan.proses');

Route::get('/pinjam/kembali', [BarangController::class, 'kembalikan'])->name('pinjam.kembali');

Route::get('/peminjaman', [BarangController::class, 'scanPageKembali'])->name('kembali.scan');

Route::post('/pinjam/scan/processBack', [BarangController::class, 'scanProcessBack'])->name('pinjam.scan.processBack');
Route::get('/kembali/scan-card', [BarangController::class, 'scanCardPage'])
    ->name('kembali.scan.card');
Route::get('/kembali/pilih', [BarangController::class, 'kembalikanPinjam'])
    ->name('kembali.pilih');
Route::get('/kembali/cari', [BarangController::class, 'cariSiswaPage'])->name('kembali.cari.siswa');
Route::get('/pinjam/cari', [BarangController::class, 'cariSiswaPagePinjam'])->name('pinjam.cari.siswa');
Route::get('/kembali/cari/result', [BarangController::class, 'cariSiswaResult'])->name('kembali.cari.hasil');

Route::get('/api/rfid/{code}', function ($code) {

    $siswa = DB::table('tbl_siswa')
        ->join('tbl_user', 'tbl_user.id_siswa', '=', 'tbl_siswa.id_siswa')
        ->where('tbl_siswa.card_code', $code)
        ->select('tbl_user.id_user', 'tbl_siswa.nama')
        ->first();

    if (!$siswa) {
        return response()->json([
            'success' => false,
            'message' => 'Kartu tidak dikenali!'
        ]);
    }

    session(['peminjam_id' => $siswa->id_user]);

    return response()->json([
        'success' => true,
        'nama' => $siswa->nama
    ]);
});






Route::prefix('toolman')->middleware(['auth', 'role:toolman'])->group(function () {

    Route::get('/dashboard', [ToolmanController::class, 'dashboard'])
        ->name('toolman.dashboard');

    Route::get('/barang', [ToolmanController::class, 'barang'])
        ->name('toolman.barang');

    Route::get('/peminjaman', [ToolmanController::class, 'peminjaman'])
        ->name('toolman.peminjaman');
    Route::get('/unduh', [ToolmanController::class, 'unduh'])
        ->name('toolman.unduh');

    Route::get('/pengembalian', [ToolmanController::class, 'pengembalian'])
        ->name('toolman.pengembalian');
    Route::post('/barang/store', [ToolmanController::class, 'storeBarang'])->name('toolman.barang.store');
    Route::get('/barang/{id}/edit', [ToolmanController::class, 'edit'])
        ->name('toolman.barang.edit');

    Route::put('/barang/{id}', [ToolmanController::class, 'update'])
        ->name('toolman.barang.update');

    Route::delete('/barang/{id}', [ToolmanController::class, 'destroy'])
        ->name('toolman.barang.delete');
    // Export Excel
    Route::get('/riwayat-peminjaman/excel', [ToolmanController::class, 'exportExcel'])
        ->name('peminjaman.excel');

    // Export PDF
    Route::get('/riwayat-peminjaman/pdf', [ToolmanController::class, 'exportPdf'])
        ->name('peminjaman.pdf');
});


// API Routes untuk absensi
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::post('/absen-masuk', [AbsensiController::class, 'absenMasuk']);
    Route::post('/absen-pulang', [AbsensiController::class, 'absenPulang']);
    Route::get('/settings', [SettingController::class, 'getCurrentSettings']);
    Route::get('/status-absensi', [SiswaController::class, 'getStatusAbsensi']);
    Route::post('/mark-absent', [AbsensiController::class, 'markAbsentStudents']);
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('admin.absensi');
    Route::get('/admin/backup', [AdminController::class, 'backupDatabase'])->name('admin.backup');
    Route::get('/restore', [AdminController::class, 'showRestoreForm'])->name('admin.restore');
    Route::post('/restore', [AdminController::class, 'restoreDatabase'])->name('admin.restore.post');



    Route::get('/backup/download/{filename}', [AdminController::class, 'downloadBackup'])->name('admin.backup.download');

    Route::get('/absensi/{id}/detail', [AbsensiController::class, 'showDetail'])
        ->name('absensi.detail');

    Route::get('/absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Import Siswa (Excel) and Template download need to be defined BEFORE the resource
    // to avoid the resource's {siswa} wildcard capturing paths like 'template' or 'import'.
    Route::post('siswa/import', [SiswaManageController::class, 'import'])->name('siswa.import');
    Route::post('siswa/import/preview', [SiswaManageController::class, 'previewImport'])->name('siswa.import.preview');
    // Template download
    Route::get('siswa/template', [SiswaManageController::class, 'downloadTemplate'])->name('siswa.template');
    // Manage Siswa (resource routes)
    Route::resource('siswa', SiswaManageController::class);

    // Manage Kelas
    Route::resource('kelas', KelasManageController::class)->parameters([
        'kelas' => 'kelas',
    ]);

    // Manage Jurusan
    Route::resource('jurusan', JurusanManageController::class);

    // Manage User
    Route::resource('user', UserManageController::class);

    // Manage Wali Kelas
    Route::resource('walikelas', WaliKelasManageController::class)->parameters([
        'walikelas' => 'walikelas',
    ]);
});

// Guru (Wali Kelas) Routes
Route::prefix('guru')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/siswa', [WaliKelasController::class, 'daftarSiswa'])->name('guru.siswa.index');
    Route::get('/absensi/hari-ini', [WaliKelasController::class, 'absensiHariIni'])->name('guru.absensi.hari-ini');
    Route::get('/absensi/laporan', [WaliKelasController::class, 'laporanAbsensi'])->name('guru.absensi.laporan');
    // Export laporan as XLSX
    Route::get('/absensi/laporan/export', [WaliKelasController::class, 'exportAbsensiXlsx'])->name('guru.absensi.laporan.export');
});

// Siswa Routes
Route::prefix('siswa')->middleware(['auth', 'role:siswa,ketua'])->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/absen', [SiswaController::class, 'absen'])->name('siswa.absen');
    Route::get('/riwayat-absensi', [SiswaController::class, 'riwayatAbsensi'])->name('siswa.riwayat-absensi');
    Route::get('/riwayat-sholat', [SiswaController::class, 'riwayatSholat'])->name('siswa.riwayat-sholat');
    Route::get('/settings', [SiswaController::class, 'settings'])->name('siswa.settings');
    Route::post('/settings', [SiswaController::class, 'updateSettings'])->name('siswa.settings.update');
});
// Siswa Routes
// Route::prefix('siswa')->middleware(['auth', 'role:ketua'])->group(function () {
//     Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
//     Route::get('/absen', [SiswaController::class, 'absen'])->name('siswa.absen');
//     Route::get('/riwayat-absensi', [SiswaController::class, 'riwayatAbsensi'])->name('siswa.riwayat-absensi');
//     Route::get('/riwayat-sholat', [SiswaController::class, 'riwayatSholat'])->name('siswa.riwayat-sholat');
//     Route::get('/settings', [SiswaController::class, 'settings'])->name('siswa.settings');
//     Route::post('/settings', [SiswaController::class, 'updateSettings'])->name('siswa.settings.update');
// });

// UKS Routes
Route::prefix('uks')->middleware(['auth', 'role:uks'])->group(function () {
    Route::get('/dashboard', [UksController::class, 'dashboard'])->name('uks.dashboard');
    Route::get('/uks/api/search-siswa', [SiswaController::class, 'searchSiswa'])->name('uks.api.search-siswa');

    // Medicine Management (explicit routes matching UksController method names)
    Route::get('/obat', [UksController::class, 'obatIndex'])->name('uks.obat.index');
    Route::get('/obat/create', [UksController::class, 'obatCreate'])->name('uks.obat.create');
    Route::post('/obat', [UksController::class, 'obatStore'])->name('uks.obat.store');
    Route::get('/obat/{id}/edit', [UksController::class, 'obatEdit'])->name('uks.obat.edit');
    Route::put('/obat/{id}', [UksController::class, 'obatUpdate'])->name('uks.obat.update');
    Route::delete('/obat/{id}', [UksController::class, 'obatDestroy'])->name('uks.obat.destroy');

    // Stock Management
    Route::get('/stok', [UksController::class, 'stokIndex'])->name('uks.stok.index');
    Route::get('/stok/create', [UksController::class, 'stokCreate'])->name('uks.stok.create');
    Route::post('/stok', [UksController::class, 'stokStore'])->name('uks.stok.store');
    Route::get('/stok/{id}/edit', [UksController::class, 'stokEdit'])->name('uks.stok.edit');
    Route::put('/stok/{id}', [UksController::class, 'stokUpdate'])->name('uks.stok.update');
    Route::delete('/stok/{id}', [UksController::class, 'stokDestroy'])->name('uks.stok.destroy');

    // Medical Records
    Route::get('/rekam-medis', [UksController::class, 'rekamMedisIndex'])->name('uks.rekam-medis.index');
    Route::get('/rekam-medis/create', [UksController::class, 'rekamMedisCreate'])->name('uks.rekam-medis.create');
    Route::post('/rekam-medis', [UksController::class, 'rekamMedisStore'])->name('uks.rekam-medis.store');
    Route::get('/rekam-medis/{id}/edit', [UksController::class, 'rekamMedisEdit'])->name('uks.rekam-medis.edit');
    Route::put('/rekam-medis/{id}', [UksController::class, 'rekamMedisUpdate'])->name('uks.rekam-medis.update');
    Route::delete('/rekam-medis/{id}', [UksController::class, 'rekamMedisDestroy'])->name('uks.rekam-medis.destroy');
    Route::get('/uks/api/search-siswa', [UksController::class, 'searchSiswa'])->name('uks.search-siswa');

    // Lihat semua rekam medis milik 1 siswa
    Route::get('/rekam-medis/siswa/{id_siswa}', [UksController::class, 'rekamMedisBySiswa'])->name('uks.rekam-medis.by-siswa');

    // UKS Visits
    Route::get('/kunjungan', [UksController::class, 'kunjunganIndex'])->name('uks.kunjungan.index');
    Route::get('/kunjungan/create', [UksController::class, 'kunjunganCreate'])->name('uks.kunjungan.create');
    Route::post('/kunjungan', [UksController::class, 'kunjunganStore'])->name('uks.kunjungan.store');
    Route::get('/kunjungan/{id}/edit', [UksController::class, 'kunjunganEdit'])->name('uks.kunjungan.edit');
    Route::put('/kunjungan/{id}', [UksController::class, 'kunjunganUpdate'])->name('uks.kunjungan.update');
    Route::delete('/kunjungan/{id}', [UksController::class, 'kunjunganDestroy'])->name('uks.kunjungan.destroy');

    // Izin Pulang
    Route::get('/izin-pulang', [UksController::class, 'izinPulang'])->name('uks.izin-pulang');
    Route::get('/izin-pulang/create', [UksController::class, 'createIzinPulang'])->name('uks.izin-pulang.create');
    Route::post('/izin-pulang', [UksController::class, 'storeIzinPulang'])->name('uks.izin-pulang.store');

    // Siswa
    Route::get('/siswa', [UksController::class, 'siswaIndex'])->name('uks.siswa.index');
    Route::get('/siswa/cari', [UksController::class, 'cariSiswa'])->name('uks.siswa.cari');

    Route::get('/uks/cari-siswa-rfid/{card_code}', [UksController::class, 'searchSiswaByRfid']);

    // Daftar Obat Keluar
    Route::get('/obat-keluar', [UksController::class, 'obatKeluarIndex'])->name('uks.obat-keluar.index');
    Route::get('/export/obat-keluar', [UksController::class, 'exportObatKeluar'])->name('uks.export.obat-keluar');

    // Export Routes
    Route::get('/export/kunjungan', [UksController::class, 'exportKunjungan'])->name('uks.export.kunjungan');
    Route::get('/export/rekam-medis', [UksController::class, 'exportRekamMedis'])->name('uks.export.rekam-medis');
    Route::get('/export/izin-pulang', [UksController::class, 'exportIzinPulang'])->name('uks.export.izin-pulang');
    Route::get('/export/obat', [UksController::class, 'exportObat'])->name('uks.export.obat');
});

// PIKET Routes
Route::prefix('piket')->middleware(['auth', 'role:piket'])->group(function () {
    Route::get('/dashboard', [PiketController::class, 'dashboard'])->name('piket.dashboard');

    // Siswa Routes
    Route::get('/siswa', [PiketController::class, 'daftarSiswa'])->name('piket.siswa.index');
    Route::get('/riwayat-absen', [PiketController::class, 'riwayat'])->name('piket.riwayat-absen');
    Route::get('/unduh-laporan', [PiketController::class, 'laporan'])->name('piket.laporan');
    Route::get('/unduh-laporan/export', [PiketController::class, 'exportLaporan'])->name('piket.laporan.export');
    // Izin Pulang Routes
    Route::get('/izin-pulang', [PiketController::class, 'izinPulang'])->name('piket.izin-pulang');
    Route::get('/izin-pulang/create', [PiketController::class, 'createIzinPulang'])->name('piket.izin-pulang.create');
    Route::post('/izin-pulang', [PiketController::class, 'storeIzinPulang'])->name('piket.izin-pulang.store');
});

// API Routes for search and RFID (outside UKS middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/uks/api/search-siswa', [SiswaController::class, 'searchSiswa'])->name('uks.api.search-siswa');
});
