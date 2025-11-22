<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ExternalRedirectController;
use App\Http\Controllers\JurusanManageController;
use App\Http\Controllers\KelasManageController;
use App\Http\Controllers\PiketController;
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


// =====================================
// ROOT + LOGIN
// =====================================
Route::get('/', fn() => redirect('/login'));
Route::get('/phpmyadmin', fn() => redirect()->to('http://127.0.0.1/phpmyadmin')->send());

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =====================================
// HALAMAN PEMINJAMAN (FRONT)
// =====================================
Route::get('/pinjam', [BarangController::class, 'index'])->name('pinjam.index');
Route::get('/pinjam/cart', [BarangController::class, 'cart'])->name('pinjam.cart');
Route::post('/pinjam/add', [BarangController::class, 'addToCart'])->name('pinjam.add');
Route::get('/pinjam/checkout', [BarangController::class, 'checkout'])->name('pinjam.checkout');
Route::post('/pinjam/checkout/process', [BarangController::class, 'processCheckout'])->name('pinjam.process');
Route::post('/pinjam/remove/{id}', [BarangController::class, 'removeItem'])->name('pinjam.remove');

Route::get('/pinjam/pilih', [BarangController::class, 'pilih'])->name('pinjam.pilih');
Route::get('/pinjam/scan-card', [BarangController::class, 'scanCard'])->name('pinjam.scan.card');

Route::get('/pinjam/scan', [BarangController::class, 'scanPage'])->name('pinjam.scan');
Route::post('/pinjam/scan/process', [BarangController::class, 'scanProcess'])->name('pinjam.scan.process');
Route::post('/pinjam/update-qty', [BarangController::class, 'updateQty'])->name('pinjam.updateQty');


// =====================================
// KEMBALIKAN BARANG
// =====================================
Route::post('/pinjam/kembali/process', [BarangController::class, 'processKembalikan'])->name('pinjam.kembalikan.proses');
Route::get('/pinjam/kembali', [BarangController::class, 'kembalikan'])->name('pinjam.kembali');

Route::get('/peminjaman', [BarangController::class, 'scanPageKembali'])->name('kembali.scan');
Route::post('/pinjam/scan/processBack', [BarangController::class, 'scanProcessBack'])->name('pinjam.scan.processBack');

Route::get('/kembali/scan-card', [BarangController::class, 'scanCardPage'])->name('kembali.scan.card');
Route::get('/kembali/pilih', [BarangController::class, 'kembalikanPinjam'])->name('kembali.pilih');


// =====================================
// 🔥 API RFID — HARUS DI LUAR AUTH
// =====================================
Route::get('/api/rfid/{code}', function ($code) {

    // Tes apakah route masuk
    // dd("RFID diterima:", $code);

    // $cleanCode = trim($code);

    $siswa = DB::table('tbl_siswa')
        ->leftJoin('tbl_user', 'tbl_user.id_siswa', '=', 'tbl_siswa.id_siswa')
        ->where('tbl_siswa.card_code', $code)
        ->select('tbl_user.id_user', 'tbl_siswa.nama')
        ->first();
    // dd($siswa);
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


// =====================================
// API — SEARCH NISN (OPEN)
// =====================================
Route::get('/api/get-siswa-by-nisn/{nisn}', function ($nisn) {
    $siswa = DB::table('tbl_siswa')
        ->join('tbl_kelas', 'tbl_siswa.id_kelas', '=', 'tbl_kelas.id_kelas')
        ->where('nisn', $nisn)
        ->select('tbl_siswa.*', 'tbl_kelas.nama_kelas as kelas')
        ->first();

    return $siswa
        ? response()->json(['status' => true, 'siswa' => $siswa])
        : response()->json(['status' => false]);
});


// =====================================
// GROUP API DENGAN AUTH
// =====================================
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::post('/absen-masuk', [AbsensiController::class, 'absenMasuk']);
    Route::post('/absen-pulang', [AbsensiController::class, 'absenPulang']);
    Route::get('/settings', [SettingController::class, 'getCurrentSettings']);
    Route::get('/status-absensi', [SiswaController::class, 'getStatusAbsensi']);
    Route::post('/mark-absent', [AbsensiController::class, 'markAbsentStudents']);
});


// =====================================
// TOOLMAN PANEL
// =====================================
Route::prefix('toolman')->middleware(['auth', 'role:toolman'])->group(function () {
    Route::get('/dashboard', [ToolmanController::class, 'dashboard'])->name('toolman.dashboard');
    Route::get('/barang', [ToolmanController::class, 'barang'])->name('toolman.barang');
    Route::get('/peminjaman', [ToolmanController::class, 'peminjaman'])->name('toolman.peminjaman');
    Route::get('/unduh', [ToolmanController::class, 'unduh'])->name('toolman.unduh');
    Route::get('/pengembalian', [ToolmanController::class, 'pengembalian'])->name('toolman.pengembalian');

    Route::post('/barang/store', [ToolmanController::class, 'storeBarang'])->name('toolman.barang.store');
    Route::get('/barang/{id}/edit', [ToolmanController::class, 'edit'])->name('toolman.barang.edit');
    Route::put('/barang/{id}', [ToolmanController::class, 'update'])->name('toolman.barang.update');
    Route::delete('/barang/{id}', [ToolmanController::class, 'destroy'])->name('toolman.barang.delete');

    Route::get('/riwayat-peminjaman/excel', [ToolmanController::class, 'exportExcel'])->name('peminjaman.excel');
    Route::get('/riwayat-peminjaman/pdf', [ToolmanController::class, 'exportPdf'])->name('peminjaman.pdf');
});


// =====================================
// ADMIN PANEL
// =====================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('admin.absensi');

    Route::get('/admin/backup', [AdminController::class, 'backupDatabase'])->name('admin.backup');
    Route::get('/backup/download/{filename}', [AdminController::class, 'downloadBackup'])->name('admin.backup.download');

    Route::get('/restore', [AdminController::class, 'showRestoreForm'])->name('admin.restore');
    Route::post('/restore', [AdminController::class, 'restoreDatabase'])->name('admin.restore.post');

    Route::post('siswa/import', [SiswaManageController::class, 'import'])->name('siswa.import');
    Route::post('siswa/import/preview', [SiswaManageController::class, 'previewImport'])->name('siswa.import.preview');
    Route::get('siswa/template', [SiswaManageController::class, 'downloadTemplate'])->name('siswa.template');

    Route::resource('siswa', SiswaManageController::class);
    Route::resource('kelas', KelasManageController::class);
    Route::resource('jurusan', JurusanManageController::class);
    Route::resource('user', UserManageController::class);

    Route::resource('walikelas', WaliKelasManageController::class);
});


// =====================================
// GURU (WALI KELAS)
// =====================================
Route::prefix('guru')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/siswa', [WaliKelasController::class, 'daftarSiswa'])->name('guru.siswa.index');
    Route::get('/absensi/hari-ini', [WaliKelasController::class, 'absensiHariIni'])->name('guru.absensi.hari-ini');
    Route::get('/absensi/laporan', [WaliKelasController::class, 'laporanAbsensi'])->name('guru.absensi.laporan');
    Route::get('/absensi/laporan/export', [WaliKelasController::class, 'exportAbsensiXlsx'])->name('guru.absensi.laporan.export');
});


// =====================================
// SISWA ROUTES
// =====================================
Route::prefix('siswa')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/absen', [SiswaController::class, 'absen'])->name('siswa.absen');
    Route::get('/riwayat-absensi', [SiswaController::class, 'riwayatAbsensi'])->name('siswa.riwayat-absensi');
    Route::get('/riwayat-sholat', [SiswaController::class, 'riwayatSholat'])->name('siswa.riwayat-sholat');
    Route::get('/settings', [SiswaController::class, 'settings'])->name('siswa.settings');
    Route::post('/settings', [SiswaController::class, 'updateSettings'])->name('siswa.settings.update');
});


// =====================================
// KETUA KELAS ROUTES
// =====================================
Route::prefix('siswa')->middleware(['auth', 'role:ketua'])->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/absen', [SiswaController::class, 'absen'])->name('siswa.absen');
    Route::get('/riwayat-absensi', [SiswaController::class, 'riwayatAbsensi'])->name('siswa.riwayat-absensi');
    Route::get('/riwayat-sholat', [SiswaController::class, 'riwayatSholat'])->name('siswa.riwayat-sholat');
    Route::get('/settings', [SiswaController::class, 'settings'])->name('siswa.settings');
    Route::post('/settings', [SiswaController::class, 'updateSettings'])->name('siswa.settings.update');
});


// =====================================
// UKS
// =====================================
Route::prefix('uks')->middleware(['auth', 'role:uks'])->group(function () {
    Route::get('/dashboard', [UksController::class, 'dashboard'])->name('uks.dashboard');

    Route::get('/uks/api/search-siswa', [SiswaController::class, 'searchSiswa'])->name('uks.api.search-siswa');

    Route::get('/obat', [UksController::class, 'obatIndex'])->name('uks.obat.index');
    Route::get('/obat/create', [UksController::class, 'obatCreate'])->name('uks.obat.create');
    Route::post('/obat', [UksController::class, 'obatStore'])->name('uks.obat.store');
    Route::get('/obat/{id}/edit', [UksController::class, 'obatEdit'])->name('uks.obat.edit');
    Route::put('/obat/{id}', [UksController::class, 'obatUpdate'])->name('uks.obat.update');
    Route::delete('/obat/{id}', [UksController::class, 'obatDestroy'])->name('uks.obat.destroy');

    Route::get('/stok', [UksController::class, 'stokIndex'])->name('uks.stok.index');
    Route::get('/stok/create', [UksController::class, 'stokCreate'])->name('uks.stok.create');
    Route::post('/stok', [UksController::class, 'stokStore'])->name('uks.stok.store');
    Route::get('/stok/{id}/edit', [UksController::class, 'stokEdit'])->name('uks.stok.edit');
    Route::put('/stok/{id}', [UksController::class, 'stokUpdate'])->name('uks.stok.update');
    Route::delete('/stok/{id}', [UksController::class, 'stokDestroy'])->name('uks.stok.destroy');

    Route::get('/rekam-medis', [UksController::class, 'rekamMedisIndex'])->name('uks.rekam-medis.index');
    Route::get('/rekam-medis/create', [UksController::class, 'rekamMedisCreate'])->name('uks.rekam-medis.create');
    Route::post('/rekam-medis', [UksController::class, 'rekamMedisStore'])->name('uks.rekam-medis.store');
    Route::get('/rekam-medis/{id}/edit', [UksController::class, 'rekamMedisEdit'])->name('uks.rekam-medis.edit');
    Route::put('/rekam-medis/{id}', [UksController::class, 'rekamMedisUpdate'])->name('uks.rekam-medis.update');
    Route::delete('/rekam-medis/{id}', [UksController::class, 'rekamMedisDestroy'])->name('uks.rekam-medis.destroy');

    Route::get('/uks/cari-siswa-rfid/{card_code}', [UksController::class, 'searchSiswaByRfid']);

    Route::get('/obat-keluar', [UksController::class, 'obatKeluarIndex'])->name('uks.obat-keluar.index');
    Route::get('/export/obat-keluar', [UksController::class, 'exportObatKeluar'])->name('uks.export.obat-keluar');

    Route::get('/export/kunjungan', [UksController::class, 'exportKunjungan'])->name('uks.export.kunjungan');
    Route::get('/export/rekam-medis', [UksController::class, 'exportRekamMedis'])->name('uks.export.rekam-medis');
    Route::get('/export/izin-pulang', [UksController::class, 'exportIzinPulang'])->name('uks.export.izin-pulang');
    Route::get('/export/obat', [UksController::class, 'exportObat'])->name('uks.export.obat');
});