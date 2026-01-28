# Dokumentasi Fitur Rekam Pelanggaran dan Prestasi Siswa

## Ringkasan Perubahan

Telah diimplementasikan fitur pencatatan pelanggaran dan prestasi siswa yang menggunakan halaman terpisah (bukan modal), dengan tambahan fitur upload foto dan point sistem untuk prestasi.

## Database

### Table Baru

1. **tbl_jenis_prestasi**
   - `id` - Primary key
   - `nama_prestasi` - Nama jenis prestasi
   - `poin_prestasi` - Point untuk jenis prestasi tersebut
   - `keterangan` - Deskripsi prestasi

2. **tbl_rekam_prestasi_siswa**
   - `id` - Primary key
   - `id_siswa` - Foreign key ke tbl_siswa
   - `id_jenis_prestasi` - Foreign key ke tbl_jenis_prestasi
   - `tanggal_prestasi` - Tanggal prestasi dicatat
   - `bukti_prestasi` - Path file bukti (opsional)
   - `keterangan` - Deskripsi tambahan
   - `id_user` - Foreign key ke tbl_user (petugas pencatat)

### Modifikasi Tabel Existing

**tbl_rekam_pelanggaran** - Ditambahkan kolom:
- `foto_pelanggaran` - Path file foto pelanggaran (wajib saat pencatatan)
- `id_user` - Foreign key ke tbl_user (petugas pencatat)

## File yang Dibuat/Diubah

### Controllers
- `app/Http/Controllers/Guru/PelanggaranController.php` - Menangani rekam pelanggaran
- `app/Http/Controllers/Guru/PretasiController.php` - Menangani rekam prestasi

### Models
- `app/Models/RekamPelanggaran.php` - Model rekam pelanggaran (baru)
- `app/Models/RekamPrestasi.php` - Model rekam prestasi (baru)
- `app/Models/JenisPrestasi.php` - Model jenis prestasi (baru)
- `app/Models/Siswa.php` - Update relationships

### Views
- `resources/views/guru/rekam/pilih-jenis.blade.php` - Halaman pilihan
- `resources/views/guru/rekam/pelanggaran.blade.php` - Form rekam pelanggaran
- `resources/views/guru/rekam/prestasi.blade.php` - Form rekam prestasi
- `resources/views/guru/dashboard.blade.php` - Update: hapus modal, ubah button

### Migrations
- `database/migrations/2026_01_28_000001_add_foto_and_user_to_rekam_pelanggaran.php`
- `database/migrations/2026_01_28_000002_create_tbl_jenis_prestasi_table.php`
- `database/migrations/2026_01_28_000003_create_tbl_rekam_prestasi_siswa_table.php`

### Seeders
- `database/seeders/JenisPretasiSeeder.php` - Menambahkan 13 jenis prestasi default

### Routes
- Diubah di `routes/web.php` dengan routes baru untuk guru

## Alur Kerja

### Rekam Pelanggaran
1. Guru klik tombol "+" di dashboard
2. Pilih "Rekam Pelanggaran"
3. Form membuka dengan:
   - Pilih siswa (searchable select2)
   - Tanggal pelanggaran (default hari ini)
   - Upload foto pelanggaran (wajib, max 2MB)
   - Pilih jenis pelanggaran (checkbox multiple)
4. Submit form
5. Foto disimpan di `storage/app/public/pelanggaran/`
6. Data pelanggaran dicatat dengan id_user petugas

### Rekam Prestasi
1. Guru klik tombol "+" di dashboard
2. Pilih "Rekam Prestasi"
3. Form membuka dengan:
   - Pilih siswa (searchable select2)
   - Pilih jenis prestasi dengan pointnya
   - Tanggal prestasi (default hari ini)
   - Upload bukti prestasi (opsional, max 2MB)
   - Keterangan (opsional)
4. Submit form
5. Bukti disimpan di `storage/app/public/prestasi/` (jika ada)
6. Data prestasi dicatat dengan id_user petugas

## Data yang Disimpan

### Pelanggaran
- Siapa yang melakukan pelanggaran
- Jenis pelanggaran apa
- Kapan terjadi
- Foto bukti pelanggaran
- Siapa petugas yang mencatat (user_id)

### Prestasi
- Siapa yang berprestasi
- Jenis prestasi apa (dengan point)
- Kapan terjadi
- Bukti prestasi (opsional)
- Catatan tambahan
- Siapa petugas yang mencatat (user_id)

## Routes

```
GET    /guru/rekam/pilih                 - guru.rekam.pilih
GET    /guru/pelanggaran/form            - guru.pelanggaran.form
POST   /guru/pelanggaran/store           - guru.pelanggaran.store
GET    /guru/prestasi/form               - guru.prestasi.form
POST   /guru/prestasi/store              - guru.prestasi.store
```

## Jenis Prestasi Default

Data seeder sudah menambahkan 13 jenis prestasi dengan point:
1. Juara 1 Tingkat Sekolah (50 poin)
2. Juara 2 Tingkat Sekolah (40 poin)
3. Juara 3 Tingkat Sekolah (30 poin)
4. Juara 1 Tingkat Kota (100 poin)
5. Juara 2 Tingkat Kota (80 poin)
6. Juara 3 Tingkat Kota (60 poin)
7. Juara 1 Tingkat Provinsi (150 poin)
8. Juara 2 Tingkat Provinsi (120 poin)
9. Juara 3 Tingkat Provinsi (100 poin)
10. Prestasi Akademik - IPK Tertinggi (75 poin)
11. Prestasi Olahraga (50 poin)
12. Prestasi Seni (50 poin)
13. Prestasi Kepemimpinan (60 poin)

Admin dapat menambah jenis prestasi baru melalui management panel.

## Fitur Form

### Upload Foto/Bukti
- Drag & drop support
- Click to upload
- Preview sebelum submit
- Hapus file button
- File validation (image only, max 2MB)

### Select Siswa & Jenis
- Select2 dengan search
- Keyboard navigation
- Clear selection button

### Validasi
- Validasi server-side dengan Laravel validation
- Error message yang user-friendly
- Redirect kembali ke form dengan input sebelumnya jika ada error

## Folders Storage

```
storage/
├── app/
│   └── public/
│       ├── pelanggaran/    (untuk foto pelanggaran)
│       └── prestasi/       (untuk bukti prestasi)
```

## Testing

Untuk testing, pastikan:
1. Login sebagai guru/wali kelas
2. Klik tombol "+" di dashboard
3. Test kedua flow (pelanggaran dan prestasi)
4. Verifikasi data tersimpan di database
5. Verifikasi file foto/bukti tersimpan di storage
