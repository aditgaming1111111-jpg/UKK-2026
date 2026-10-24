# SekolahKu

SekolahKu adalah aplikasi administrasi pelanggaran siswa berbasis Laravel. Aplikasi menyediakan pengelolaan siswa dan riwayat kelas, data Guru, tahun ajaran, jenis pelanggaran, pencatatan kejadian, rekap wali kelas, laporan, dan audit perubahan.

Role login hanya `admin` dan `guru`. Wali kelas ditentukan oleh penugasan Guru untuk kelas dan tahun ajaran tertentu.

## Teknologi

- PHP `^8.3` dan Laravel `^13.17`.
- MySQL.
- Blade, Tailwind CSS 4, JavaScript, dan Vite 8.
- Composer dan npm untuk memasang dependency dari lock file.

Untuk Vite 8, gunakan Node.js `^20.19.0` atau `>=22.12.0`.

## Menjalankan di Laragon (Windows)

Ikuti langkah berurutan. Perintah clone dijalankan dari `C:\laragon\www`; perintah berikutnya dijalankan dari folder proyek.

### 1. Siapkan perangkat

Pasang Laragon dengan PHP 8.3 atau lebih baru yang sesuai, MySQL, Composer, Node.js yang didukung Vite, dan npm. Buka Laragon lalu nyalakan MySQL.

Periksa tool yang tersedia:

```powershell
php -v
composer --version
node --version
npm --version
```

### 2. Ambil proyek

Jika proyek belum ada di komputer:

```powershell
Set-Location C:\laragon\www
git clone https://github.com/aditgaming1111111-jpg/UKK-2026.git sekolahku
```

Masuk ke foldernya:

```powershell
cd C:\laragon\www\sekolahku
```

Jika proyek sudah tersedia, buka terminal di folder tersebut dan lewati perintah clone serta `cd`. Jangan membuat clone kedua di dalam folder proyek.

### 3. Pasang dependency PHP

```powershell
composer install
```

Perintah ini memasang versi dependency yang dikunci di `composer.lock`. Jangan gunakan `composer update` untuk setup biasa karena dapat mengganti versi dependency.

### 4. Siapkan konfigurasi lokal

Buat `.env` dari template hanya jika file lokal belum ada. Perintah ini tidak menimpa `.env` yang sudah ada:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
```

Buka `.env` secara lokal dan isi koneksi MySQL untuk database khusus SekolahKu:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ukk_pelanggaran_siswa
DB_USERNAME=isi_user_mysql_lokal
DB_PASSWORD=isi_password_mysql_lokal
```

Sesuaikan user dan password dengan Laragon. Jangan menyalin `.env` dari komputer lain, jangan membagikan isinya, dan jangan commit file tersebut.

Untuk instalasi baru yang `.env`-nya baru dibuat, buat application key satu kali:

```powershell
php artisan key:generate
```

Jangan jalankan ulang perintah itu pada instalasi yang sudah berjalan tanpa alasan; mengganti key dapat membuat data sesi atau data terenkripsi lama tidak dapat dibaca.

### 5. Siapkan database dengan aman

Di HeidiSQL atau phpMyAdmin Laragon, buat database kosong bernama `ukk_pelanggaran_siswa` dengan charset `utf8mb4`. Pastikan MySQL aktif dan database yang dipilih memang database lokal SekolahKu.

Setelah mengubah `.env`, bersihkan cache konfigurasi:

```powershell
php artisan config:clear
```

Untuk database baru yang kosong, jalankan migration:

```powershell
php artisan migrate
```

Migration membangun skema berdasarkan file di `database/migrations`. Jika database sudah pernah dipakai, ekspor cadangan terlebih dahulu dan periksa statusnya:

```powershell
php artisan migrate:status
```

Hentikan proses jika database atau migration yang tertunda belum dipahami. Jangan gunakan `migrate:fresh`, `db:wipe`, atau rollback untuk memperbaiki setup karena dapat menghapus data atau tabel.

Repository juga memiliki dump SQL. Gunakan migration sebagai jalur setup standar; jangan mengimpor dump sekaligus menjalankan migration tanpa memastikan skema dan isi database cocok.

### 6. Buat akun pengembangan lokal (opsional)

Seeder autentikasi hanya berjalan ketika `APP_ENV=local`. Sebelum menjalankannya, isi `SEED_ADMIN_PASSWORD` dan `SEED_GURU_PASSWORD` di `.env` lokal dengan password pilihanmu yang masing-masing minimal 8 karakter. Jangan mencantumkan nilainya di chat atau commit ke Git. Jalankan hanya pada database development yang memang ingin diisi/diperbarui oleh akun contoh:

```powershell
php artisan db:seed --class=AuthenticationSeeder
```

Email akun lokal yang disiapkan:

| Role | Email |
|---|---|
| Admin | `admin@sekolahku.com` |
| Guru | `anton@sekolahku.com` |

Seeder menyimpan password dengan hashing Laravel. Seeder menggunakan `updateOrCreate`, jadi menjalankannya kembali dapat memperbarui data dan password untuk dua akun tersebut.

### 7. Pasang dan build asset frontend

```powershell
npm ci
```

```powershell
npm run build
```

`npm ci` menggunakan `package-lock.json`; `npm run build` membuat asset produksi di `public/build`.

### 8. Jalankan aplikasi

```powershell
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser dan masuk memakai akun lokal jika seeder sudah dijalankan.

Untuk pengembangan frontend dengan hot reload, hentikan server bila perlu, buka terminal kedua, lalu jalankan:

```powershell
npm run dev
```

Biarkan Vite tetap berjalan selama mengembangkan tampilan. Untuk penggunaan lokal tanpa Vite dev server, jalankan build pada langkah sebelumnya.

### 9. Periksa aplikasi

```powershell
php artisan route:list
```

```powershell
php artisan test
```

Jika halaman melaporkan koneksi ditolak, periksa apakah MySQL Laragon berjalan dan nilai host, port, database, serta akun lokal pada `.env` sudah sesuai. Jangan menampilkan password ketika meminta bantuan.

## Catatan keselamatan data

- `.env` menyimpan konfigurasi lokal dan tidak boleh masuk Git. File ini berbeda dari `.env.example`.
- Gunakan database khusus SekolahKu; jangan arahkan setup development ke database berisi data penting.
- `php artisan migrate` hanya menjalankan migration yang belum diterapkan, tetapi tetap tinjau status dan cadangkan database yang sudah berisi data.
- Jangan menjalankan seeder contoh di server atau database produksi.
- Riwayat kelas dan pelanggaran penting untuk laporan; jangan menghapus atau membangun ulang tabel hanya demi memperbaiki instalasi.

## Panduan belajar kode

Dokumentasi ringkas untuk memahami implementasi tersedia di [`docs/belajar-sekolahku/README.md`](docs/belajar-sekolahku/README.md). Mulai dari gambaran alur aplikasi, lanjut ke tabel dan relasi, lalu pelajari migration dan seeder.
