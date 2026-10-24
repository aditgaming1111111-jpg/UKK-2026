# 3. Migration dan Seeder

## Migration

Migration adalah perubahan skema database yang disimpan sebagai kode. Laravel menjalankan migration yang belum diterapkan sesuai urutan nama file. `up()` menerapkan perubahan; `down()` mencoba membaliknya, tetapi belum tentu aman jika data sudah bergantung pada tabel tersebut.

Urutan penting SekolahKu:

1. Migration awal membuat tabel Laravel, termasuk `users`, session, cache, dan queue.
2. Migration akun mengubah `users` menjadi `t_users` lalu membuat `t_guru`.
3. Migration inti membuat tabel sekolah sesuai urutan foreign key.
4. Migration berikutnya menambahkan unique index kode jenis pelanggaran.

Migration akun memeriksa data `users` dan berhenti jika tabel sumber berisi record. Untuk database baru, gunakan database khusus yang kosong dan jalankan migration standar seperti dijelaskan di [README setup](../../README.md). Jika database sudah berisi data, buat backup dan periksa status migration sebelum melanjutkan.

Perintah pemeriksaan dan penerapan biasa:

```powershell
php artisan migrate:status
php artisan migrate
```

Jangan memakai `migrate:fresh` atau `db:wipe` pada database berisi data. Keduanya dapat menghapus tabel dan riwayat.

## Seeder dan factory

Seeder membuat data awal; factory menyiapkan record untuk pengujian. `DatabaseSeeder` memanggil `AuthenticationSeeder`, yang hanya berjalan pada environment `local`. Seeder menyediakan Admin dan Guru contoh, lalu Laravel melakukan hashing password sebelum disimpan. `updateOrCreate` berarti menjalankan ulang seeder dapat memperbarui akun contoh yang emailnya cocok.

Jalankan seeder hanya untuk database lokal yang memang ingin diberi atau diperbarui akun contoh:

```powershell
php artisan db:seed --class=AuthenticationSeeder
```

Detail akun lokal saat ini tercantum di README utama. Jangan menjalankan seeder contoh di server atau database produksi.

## Lokasi kode

- `database/migrations/` — perubahan struktur tabel.
- `database/seeders/DatabaseSeeder.php` — seeder utama.
- `database/seeders/AuthenticationSeeder.php` — akun development lokal.
- `database/factories/` — pembuat data untuk feature test.
- `tests/Feature/` — pengujian yang membuat data terisolasi melalui factory.

## Ingat

Migration mengatur struktur, seeder mengisi data awal, dan factory membuat data uji. Backup sebelum bekerja pada database berisi data; jangan reset database hanya untuk mengatasi error migration.
