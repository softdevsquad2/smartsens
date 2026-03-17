<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Guru\PelanggaranController as GuruPelanggaranController;
use App\Http\Controllers\Guru\PretasiController;
use App\Http\Controllers\JurusanManageController;
use App\Http\Controllers\KelasManageController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PiketController;
use App\Http\Controllers\ProfileController;
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

    return redirect()->route('login');
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

    if (! $siswa) {
        return response()->json(['status' => false]);
    }

    return response()->json([
        'status' => true,
        'siswa' => $siswa,
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

Route::post('/toolman/token/generate', [ToolmanController::class, 'generateReturnToken'])->name('toolman.generateToken');

Route::get('/peminjaman', [BarangController::class, 'scanPageKembali'])->name('kembali.scan');

Route::post('/pinjam/scan/processBack', [BarangController::class, 'scanProcessBack'])->name('pinjam.scan.processBack');
Route::get('/kembali/scan-card', [BarangController::class, 'scanCardPage'])
    ->name('kembali.scan.card');
Route::get('/kembali/pilih', [BarangController::class, 'kembalikanPinjam'])
    ->name('kembali.pilih');
Route::get('/kembali/cari', [BarangController::class, 'cariSiswaPage'])->name('kembali.cari.siswa');
Route::get('/pinjam/cari', [BarangController::class, 'cariSiswaPagePinjam'])->name('pinjam.cari.siswa');
Route::get('/kembali/cari/result', [BarangController::class, 'cariSiswaResult'])->name('kembali.cari.hasil');
Route::get('/pinjam/cari/result', [BarangController::class, 'cariSiswaResultPinjam'])->name('pinjam.cari.hasil');

Route::get('/api/rfid/{code}', function ($code) {

    $siswa = DB::table('tbl_siswa')
        ->join('tbl_user', 'tbl_user.id_siswa', '=', 'tbl_siswa.id_siswa')
        ->where('tbl_siswa.card_code', $code)
        ->select('tbl_user.id_user', 'tbl_siswa.nama')
        ->first();

    if (! $siswa) {
        return response()->json([
            'success' => false,
            'message' => 'Kartu tidak dikenali!',
        ]);
    }

    session(['peminjam_id' => $siswa->id_user]);

    return response()->json([
        'success' => true,
        'nama' => $siswa->nama,
    ]);
});

Route::prefix('pelanggaran')->middleware(['auth', 'role:kesiswaan'])->group(function () {

    Route::get('/dashboard', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
    Route::get('/settings', [PelanggaranController::class, 'settings'])->name('pelanggaran.settings');
    Route::post('/profile/{userId}/update-credentials', [ProfileController::class, 'updateCredentials'])
        ->name('pelanggaran.profile.update-credentials')
        ->where('userId', '[0-9]+');
    Route::get('/pelanggaran', [PelanggaranController::class, 'pelanggaran'])->name('pelanggaran.pelanggaran');
    Route::post('/pelanggaran', [PelanggaranController::class, 'storePelanggaranJenis'])->name('pelanggaran.pelanggaran.store');
    Route::put('/pelanggaran/{id}', [PelanggaranController::class, 'updatePelanggaranJenis'])->name('pelanggaran.pelanggaran.update');
    Route::delete('/pelanggaran/{id}', [PelanggaranController::class, 'deletePelanggaranJenis'])->name('pelanggaran.pelanggaran.delete');
    Route::get('/riwayat', [PelanggaranController::class, 'riwayat'])->name('pelanggaran.riwayat');
    Route::get('/unduh', [PelanggaranController::class, 'unduh'])->name('pelanggaran.unduh');
    Route::get('/unduh/pdf', [PelanggaranController::class, 'exportPDF'])->name('pelanggaran.unduh.pdf');
    Route::get('/unduh/excel', [PelanggaranController::class, 'exportExcel'])->name('pelanggaran.unduh.excel');
    Route::get('/riwayat/{nama}', [PelanggaranController::class, 'detail'])->name('pelanggaran.riwayat.detail');
    Route::get('/rekam', [PelanggaranController::class, 'rekamPelanggaran'])->name('guru.pelanggaran.rekam');
    Route::post('/rekam/store', [PelanggaranController::class, 'storePelanggaran'])->name('pelanggaran.rekam.store');
    Route::get('/rekam/list', [PelanggaranController::class, 'listRekamPelanggaran'])->name('pelanggaran.rekam.list');
    Route::delete('/rekam/{id}', [PelanggaranController::class, 'deleteRekamPelanggaran'])->name('pelanggaran.rekam.delete');

    // List Pelanggaran
    Route::get('/prestasi/list', [PelanggaranController::class, 'listPrestasi'])->name('pelanggaran.prestasi.list');
    Route::get('/list-pelanggaran', [PelanggaranController::class, 'listPelanggaran'])->name('pelanggaran.list');

    // Jenis Prestasi Management
    Route::get('/jenis-prestasi', [PelanggaranController::class, 'jenisPrestasi'])->name('pelanggaran.jenis-prestasi');
    Route::post('/jenis-prestasi', [PelanggaranController::class, 'storeJenisPrestasi'])->name('pelanggaran.jenis-prestasi.store');
    Route::put('/jenis-prestasi/{id}', [PelanggaranController::class, 'updateJenisPrestasi'])->name('pelanggaran.jenis-prestasi.update');
    Route::delete('/jenis-prestasi/{id}', [PelanggaranController::class, 'deleteJenisPrestasi'])->name('pelanggaran.jenis-prestasi.delete');

    // Prestasi Management
    Route::get('/prestasi/manage', [PelanggaranController::class, 'managePrestasi'])->name('pelanggaran.prestasi.manage');
    Route::put('/prestasi/{id}', [PelanggaranController::class, 'updatePrestasi'])->name('pelanggaran.prestasi.update');
    Route::delete('/prestasi/{id}', [PelanggaranController::class, 'deletePrestasi'])->name('pelanggaran.prestasi.delete');

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
    Route::post('/check-absen-bolos', [AbsensiController::class, 'checkAndMarkAbsenBolos']);
});

// Scheduler Routes (tanpa auth dan CSRF untuk automation)
Route::prefix('scheduler')->withoutMiddleware(['web'])->group(function () {
    Route::match(['get', 'post'], '/mark-absent', [AbsensiController::class, 'markAbsentStudents']);
    Route::match(['get', 'post'], '/check-bolos', [AbsensiController::class, 'checkAndMarkAbsenBolos']);
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('admin.absensi');
    Route::get('/admin/backup', [AdminController::class, 'backupDatabase'])->name('admin.backup');
    Route::get('/restore', [AdminController::class, 'showRestoreForm'])->name('admin.restore');
    Route::post('/restore', [AdminController::class, 'restoreDatabase'])->name('admin.restore.post');

    Route::get('/backup/download/{filename}', [AdminController::class, 'downloadBackup'])->name('admin.backup.download');

    Route::get('/absensi/{id}/detail', [AbsensiController::class, 'showDetail'])
        ->name('absensi.detail');

    Route::get('/absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    Route::post('siswa/import', [SiswaManageController::class, 'import'])->name('siswa.import');
    Route::post('siswa/import/preview', [SiswaManageController::class, 'previewImport'])->name('siswa.import.preview');
    Route::get('siswa/template', [SiswaManageController::class, 'downloadTemplate'])->name('siswa.template');
    Route::resource('siswa', SiswaManageController::class);

    Route::post('walikelas/import', [WaliKelasManageController::class, 'import'])->name('walikelas.import');
    Route::get('walikelas/template', [WaliKelasManageController::class, 'downloadTemplate'])->name('walikelas.template');
    Route::resource('walikelas', WaliKelasManageController::class)->parameters([
        'walikelas' => 'walikelas',
    ]);

    Route::resource('kelas', KelasManageController::class)->parameters([
        'kelas' => 'kelas',
    ]);

    // Manage Jurusan
    Route::resource('jurusan', JurusanManageController::class);

    // Manage User
    Route::resource('user', UserManageController::class);

    // Manage Wali Kelas
    Route::resource('walikelas', WaliKelasManageController::class)->parameters(['walikelas' => 'walikelas']);
});

// Guru (Wali Kelas) Routes
Route::prefix('guru')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/siswa', [WaliKelasController::class, 'daftarSiswa'])->name('guru.siswa.index');
    Route::get('/absensi/hari-ini', [WaliKelasController::class, 'absensiHariIni'])->name('guru.absensi.hari-ini');
    Route::get('/absensi/laporan', [WaliKelasController::class, 'laporanAbsensi'])->name('guru.absensi.laporan');
    Route::get('/absensi/laporan/export', [WaliKelasController::class, 'exportAbsensiXlsx'])->name('guru.absensi.laporan.export');
    Route::get('/settings', [WaliKelasController::class, 'settings'])->name('guru.settings');

    // Profile & Credentials Management
    Route::post('/profile/{userId}/update-credentials', [ProfileController::class, 'updateCredentials'])
        ->name('guru.profile.update-credentials')
        ->where('userId', '[0-9]+');

    // Rekam Pelanggaran dan Prestasi
    Route::get('/rekam/pilih', [GuruPelanggaranController::class, 'pilihJenis'])->name('guru.rekam.pilih');
    Route::get('/pelanggaran/form', [GuruPelanggaranController::class, 'formPelanggaran'])->name('guru.pelanggaran.form');
    Route::post('/pelanggaran/store', [GuruPelanggaranController::class, 'storePelanggaran'])->name('guru.pelanggaran.store');
    Route::get('/prestasi/form', [PretasiController::class, 'formPrestasi'])->name('guru.prestasi.form');
    Route::post('/prestasi/store', [PretasiController::class, 'storePrestasi'])->name('guru.prestasi.store');
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

Route::post('/profile/{userId}/update-credentials', [ProfileController::class, 'updateCredentials'])
    ->name('siswa.profile.update-credentials')
    ->where('userId', '[0-9]+');
