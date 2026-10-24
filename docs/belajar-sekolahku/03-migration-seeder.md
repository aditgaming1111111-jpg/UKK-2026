# 3. Migration dan Seeder

## Apa perbedaannya?

- **Migration** adalah kode perubahan skema database yang dapat dijalankan berurutan.
- **Seeder** memasukkan data awal atau data contoh.
- **Factory** membuat record uji untuk test, biasanya pada database test yang di-reset secara terisolasi oleh `RefreshDatabase`.

Jalankan perintah terhadap database yang dikonfigurasi aplikasi. Periksa `APP_ENV`, koneksi, dan database tujuan sebelum menjalankan migration atau seeder. Jangan menyalin `.env` dari komputer lain dan jangan tampilkan rahasianya saat meminta bantuan.

## Urutan migration SekolahKu

1. `0001_01_01_000000_create_users_table.php` membuat tabel awal Laravel termasuk autentikasi.
2. `2026_10_01_121847_migrate_users_to_t_users_and_create_t_guru_table.php` mengganti nama tabel akun menjadi `t_users`, mengubah kolom `name` ke `nama`, menambah `role`, dan membuat `t_guru`. Migration berhenti jika tabel `users` berisi data agar pemindahan akun tidak ditentukan secara sembarangan.
3. `2026_10_01_132222_create_sekolahku_core_tables.php` membuat tabel siswa, kelas, tahun ajaran, penempatan, wali kelas, kategori, jenis pelanggaran, catatan, serta audit sesuai urutan FK. Foreign key memakai perlindungan `restrictOnDelete()` pada relasi historis.
4. `2026_10_01_155432_add_unique_index_to_t_pelanggaran_kode.php` memeriksa duplikasi kode sebelum menambahkan unique index.

Nama file diawali timestamp agar Laravel mengetahui urutan eksekusi. Skema rinci ada di [Database MySQL](02-database-mysql.md).

## Periksa dan jalankan migration dengan aman

Di PowerShell, dari root proyek:

```powershell
php artisan migrate:status
```

Periksa database tujuan dan migration yang sudah berjalan. Jika database baru memang kosong dan sudah benar dipilih, terapkan migration yang tertunda:

```powershell
php artisan migrate
```

`migrate` tidak sama dengan `migrate:fresh`, tetapi tetap mengubah database. Pada database yang sudah memiliki data, buat backup dan pahami migration tertunda sebelum melanjutkan. Jangan menggunakan `migrate:fresh`, `db:wipe`, atau rollback untuk mengatasi error tanpa strategi pemulihan yang jelas.

## Seeder akun development

`database/seeders/DatabaseSeeder.php` memanggil `AuthenticationSeeder`. Seeder autentikasi:

- Berhenti kecuali `APP_ENV=local`.
- Membutuhkan `SEED_ADMIN_PASSWORD` dan `SEED_GURU_PASSWORD` dengan panjang minimal 8 karakter.
- Membuat atau memperbarui akun contoh `admin@sekolahku.com` dan `anton@sekolahku.com`.
- Membuat identitas Guru yang terhubung ke akun Guru.
- Menyimpan password menggunakan `Hash::make`.
- Menggunakan `updateOrCreate`, sehingga menjalankannya lagi dapat memperbarui nama/password akun yang emailnya cocok dan data Guru terkait.

Atur password hanya di `.env` lokal yang tidak dilacak Git. Template `.env.example` menyediakan variabel kosong. Jalankan hanya di database development yang memang ingin diberi atau diperbarui akun contoh:

```powershell
php artisan db:seed --class=AuthenticationSeeder
```

Seeder ini bukan contoh penggunaan untuk production. Jangan meletakkan password sungguhan ke dokumentasi, chat, source code, atau commit. README utama berisi langkah setup proyek; dokumen ini hanya menjelaskan migration dan seeder, bukan menggantikannya.

## Factory dan test

Factory berada di `database/factories/`. Feature test pada `tests/Feature/` mengatur record uji sendiri. Test menggunakan konfigurasi PHPUnit yang menetapkan SQLite `:memory:` dan `RefreshDatabase`; itu terpisah dari database MySQL lokal selama konfigurasi test tersebut tetap berlaku. Jangan menganggap test sudah memakai MySQL lokal.

## File terkait

- `database/migrations/` — perubahan struktur.
- `database/seeders/DatabaseSeeder.php` — titik masuk seeder.
- `database/seeders/AuthenticationSeeder.php` — akun lokal dan relasi Guru.
- `config/app.php` — membaca password seed dari konfigurasi environment.
- `.env.example` — nama variabel yang perlu disetel pada `.env` lokal, tanpa nilai contoh rahasia.
- `database/factories/` — pembuatan data uji.
- `phpunit.xml` — konfigurasi database untuk test.

Lihat [cara menjalankan aplikasi](../../README.md) untuk langkah instalasi lengkap. README tersebut sengaja tidak diubah dalam pekerjaan dokumentasi ini.
