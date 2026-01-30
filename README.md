# SmartSens - Sistem Absensi GPS

Sistem absensi berbasis GPS untuk sekolah dengan antarmuka modern dan responsif.

## 🚀 Fitur Utama

- **Absensi GPS**: Sistem absensi menggunakan koordinat GPS untuk memastikan siswa berada di lokasi sekolah
- **Pencatatan Pelanggaran Otomatis**: Sistem otomatis mencatat pelanggaran siswa yang terlambat absen masuk dengan poin dari database pelanggaran
- **Multi-Role**: Dukungan untuk Admin, Guru, Operator, dan Siswa
- **Dashboard Modern**: Antarmuka yang modern dan responsif dengan Tailwind CSS
- **Manajemen Data**: Kelola siswa, kelas, jurusan, dan user dengan mudah
- **Pengaturan GPS**: Konfigurasi koordinat sekolah dan radius absensi
- **Dukungan Multi-Obat**: Sistem rekam medis mendukung pemberian multiple obat dengan jumlah dan aturan pakai yang dapat disesuaikan, serta pelacakan stok otomatis
- **Responsive Design**: Tampilan yang optimal di desktop dan mobile

## 📋 Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Web Server (Apache/Nginx) atau PHP Built-in Server

## 🛠️ Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd Smartsens
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stmsmart_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migrasi dan Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 6. Link Storage
```bash
php artisan storage:link
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🎯 Akun Default

### Admin
- **Username**: admin
- **Password**: admin123

### Siswa
- **Username**: ARIEF MAULANA RIZKI
- **Password**: 12326098

### UKS
- **Username**: petugas_uks
- **Password**: password
### PIKET
- **Username**: piket
- **Password**: password

### guru
- **Username**: aisiti
- **Password**: aisiti123

## Kesiswaan
- **username**: kesiswaan
- **password**: password

## 📱 Cara Penggunaan

### Untuk Admin
1. Login dengan akun admin
2. Atur koordinat sekolah di menu Pengaturan
3. Kelola data siswa, kelas, dan jurusan
4. Monitor absensi siswa

### Untuk Siswa
1. Login dengan akun siswa
2. Buka menu Absensi
3. Izinkan akses lokasi GPS
4. Lakukan absensi masuk/pulang

## 🔧 Konfigurasi GPS

1. Masuk ke menu **Pengaturan**
2. Set **Latitude** dan **Longitude** sekolah
3. Atur **Radius** absensi (dalam meter)
4. Konfigurasi **Jam Masuk** dan **Jam Pulang**
5. Simpan pengaturan

## 📁 Struktur Aplikasi

```
app/
├── Http/Controllers/     # Controller aplikasi
├── Models/               # Model Eloquent
├── Http/Middleware/      # Middleware
└── ...

resources/views/
├── layouts/             # Layout utama
├── admin/              # Halaman admin
├── siswa/              # Halaman siswa
└── auth/               # Halaman autentikasi

database/
├── migrations/          # Migrasi database
└── seeders/           # Seeder database
```

## 🎨 Teknologi yang Digunakan

- **Laravel 10**: Framework PHP
- **Tailwind CSS**: Framework CSS
- **Font Awesome**: Ikon
- **MySQL**: Database
- **JavaScript**: Interaktivitas

## 📱 Responsive Design

Aplikasi dirancang untuk berfungsi optimal di:
- Desktop (1024px+)
- Tablet (768px - 1023px)
- Mobile (320px - 767px)

## 🔒 Keamanan

- Password hashing dengan bcrypt
- CSRF protection
- Role-based access control
- Input validation
- SQL injection protection

## 🚀 Deployment

### Link Storage
jalankan fungsi

php artisan storage:link

### Production Setup
```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📞 Support

Jika mengalami masalah, silakan:
1. Periksa log error di `storage/logs/`
2. Pastikan semua persyaratan terpenuhi
3. Cek konfigurasi database
4. Pastikan GPS aktif di perangkat

## 📄 Lisensi

Aplikasi ini dibuat untuk keperluan pendidikan minta izin terlebih dahulu sebelum di gunakan.

---

**SmartSens** - Sistem Absensi GPS yang Modern dan Efisien 🎓
