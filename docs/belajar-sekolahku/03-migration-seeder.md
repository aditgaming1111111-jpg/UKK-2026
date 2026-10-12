# Bab 3 — Migration dan Seeder

## 1. Inti materi

- Migration menyimpan perubahan skema database sebagai kode yang berurutan.
- `up()` menerapkan perubahan; `down()` membalik perubahan jika aman.
- Migration awal Laravel membuat tabel akun dan pendukung. Migration berikutnya mengubah tabel akun menjadi `t_users`, membuat `t_guru`, lalu membuat tabel domain.
- Seeder menyiapkan akun pengembangan lokal; password di-hash oleh Laravel.
- Factory membuat record contoh untuk pengujian, sedangkan data aplikasi disimpan melalui form dan controller.
- Migration dan seeder belum dijalankan dalam audit dokumentasi ini.

## 2. Fungsi

Migration menjadikan definisi database dapat ditinjau dan dikendalikan lewat Git. Tim dapat melihat tabel, kolom, constraint, dan urutan perubahan tanpa menjadikan file SQL manual satu-satunya sumber skema.

Seeder mengisi data awal yang dibutuhkan lingkungan tertentu. Di SekolahKu, `AuthenticationSeeder` hanya boleh berjalan saat environment `local`; ia membuat akun admin dan guru contoh beserta identitas Guru. Factory berfungsi untuk menghasilkan data uji, bukan otomatis dijalankan setiap kali aplikasi dimulai.

## 3. Lokasi kode

- `database/migrations/0001_01_01_000000_create_users_table.php` — tabel autentikasi awal Laravel, password reset, dan session.
- `database/migrations/0001_01_01_000001_create_cache_table.php` — cache dan cache lock.
- `database/migrations/0001_01_01_000002_create_jobs_table.php` — antrean job dan kegagalan job.
- `database/migrations/2026_10_01_121847_migrate_users_to_t_users_and_create_t_guru_table.php` — konversi tabel users dan pembuatan guru.
- `database/migrations/2026_10_01_132222_create_sekolahku_core_tables.php` — tabel domain lain dengan urutan FK.
- `database/migrations/2026_10_01_155432_add_unique_index_to_t_pelanggaran_kode.php` — unique index kode.
- `database/seeders/AuthenticationSeeder.php` dan `database/seeders/DatabaseSeeder.php` — seeder akun lokal dan titik pemanggilannya.
- `database/factories/` — factory model yang dipakai tes.

## 4. Contoh kode aktual

File: `database/migrations/2026_10_01_121847_migrate_users_to_t_users_and_create_t_guru_table.php`

```php
if (DB::table('users')->exists()) {
    throw new RuntimeException(
        'Tabel users berisi data. Tetapkan role dan strategi pemindahan akun sebelum migration ini dijalankan.'
    );
}

Schema::rename('users', 't_users');

Schema::table('t_users', function (Blueprint $table): void {
    $table->renameColumn('name', 'nama');
    $table->dropColumn(['email_verified_at', 'remember_token']);
    $table->string('role', 20);
});
```

File: `database/seeders/AuthenticationSeeder.php`

```php
if (! app()->environment('local')) {
    throw new \RuntimeException('AuthenticationSeeder hanya boleh dijalankan pada APP_ENV=local.');
}

$admin = User::updateOrCreate(
    ['email' => 'admin@sekolahku.test'],
    [
        'nama' => 'Administrator SekolahKu',
        'password' => Hash::make(self::ADMIN_PASSWORD),
        'role' => 'admin',
    ],
);
```

Cuplikan seeder sengaja tidak menampilkan nilai password development yang ada di file sumber.

## 5. Penjelasan kode

- Migration akun memeriksa tabel asal sebelum rename. Jika tabel `users` berisi data, migration berhenti agar tidak menetapkan role atau memindahkan akun tanpa strategi yang jelas.
- `Schema::rename` mengganti nama tabel. `Schema::table` mengubah kolom tabel tersebut.
- `string('role', 20)` menambahkan kolom role bertipe teks dengan batas panjang 20; daftar nilai yang diterima aplikasi diperiksa di kode autentikasi/model, bukan sebagai database enum.
- `updateOrCreate` mencari akun dengan email yang diberikan: bila ada, ia memperbarui atribut; bila belum ada, ia membuat record.
- `Hash::make` membuat hash password sebelum nilai dikirim ke model/database.
- Environment guard mencegah seeder akun contoh dijalankan pada environment selain `local`.

## 6. Hubungan antarfile dan urutan dependency

Urutan nama file migration memakai timestamp/prefix agar Laravel menjalankan perubahan dalam urutan yang tepat:

1. Migration `0001...users` membuat tabel `users`, `password_reset_tokens`, dan `sessions`.
2. Migration `0001...cache` membuat `cache` dan `cache_locks`.
3. Migration `0001...jobs` membuat `jobs`, `job_batches`, dan `failed_jobs`.
4. Migration `...121847...` mengubah `users` menjadi `t_users` dan membuat `t_guru`, yang FK-nya menuju `t_users`.
5. Migration `...132222...` membuat tabel domain. Parent dibuat sebelum tabel yang mereferensikannya: siswa, kelas, tahun ajaran, kategori, penempatan, wali kelas, jenis pelanggaran, catatan, lalu log.
6. Migration `...155432...` menambahkan unique index `t_pelanggaran.kode` setelah tabel tersebut tersedia. Migration juga memeriksa kode duplikat sebelum membuat index.

`DatabaseSeeder::run()` memanggil `AuthenticationSeeder`. Factory seperti `UserFactory` dan `KelasSiswaFactory` dipanggil dalam test untuk membuat pasangan record yang saling terkait.

## 7. Contoh alur nyata

Saat database baru dibangun, migration membuat `t_siswa` sebelum `t_kelas_siswa`, karena penempatan kelas memiliki foreign key ke siswa, kelas, dan tahun ajaran. Setelah tabel induk ada, catatan pelanggaran dibuat dengan foreign key ke penempatan, jenis pelanggaran, dan guru. Log dibuat setelah tabel catatan tersedia.

Sebaliknya, seeder pengembangan dapat menyiapkan login lokal setelah tabel akun dan guru tersedia. Record siswa contoh pada tes berasal dari factory. Record yang dibuat pengguna saat memakai aplikasi berasal dari validasi dan controller/service, bukan dari migration atau factory.

Migration `down()` pada tabel inti terlebih dahulu memeriksa apakah masih ada record di tabel domain; jika ada, ia melempar exception dan menolak rollback. Penghapusan database dapat menghilangkan data permanen. Jangan menjalankan perintah destruktif terhadap database berisi data.

## 8. Kesalahan umum

- Menganggap `down()` selalu aman. Pada migration ini rollback dapat sengaja dihentikan jika data sudah tersimpan.
- Menganggap seeder sama dengan migration. Migration membuat/ubah struktur; seeder membuat record awal.
- Menganggap factory adalah data produksi. Factory terutama menyediakan data buatan untuk pengujian.
- Menjalankan `migrate:fresh` untuk “memperbaiki” skema. Perintah itu menghapus tabel lalu membangun ulang database.
- Menjalankan migration konversi akun pada tabel `users` berisi data. Implementasi saat ini akan menghentikannya dan meminta strategi migrasi data terlebih dahulu.
- Mengira password seeder disimpan polos. Seeder memanggil `Hash::make`, dan model `User` juga mendeklarasikan cast password `hashed`.

## 9. Ringkasan hafalan

Migration mendefinisikan struktur; seeder mengisi data awal; factory membuat data uji. Laravel menjalankan migration berdasarkan urutan namanya. Foreign key membuat urutan pembuatan tabel penting. `up()` menerapkan perubahan dan `down()` membalik jika aman. Perintah yang mereset tabel berisiko menghapus data.

## 10. Latihan

1. Mengapa migration konversi akun memeriksa apakah `users` berisi data sebelum rename?
2. Apa bedanya migration, seeder, dan factory?
3. Migration mana yang membuat `t_log_pelanggaran`, dan tabel apa yang harus ada lebih dahulu?

## 11. Simulasi jawaban penguji

**Penguji:** Apa yang dilakukan migration dan bagaimana aplikasi menjaga agar data tidak terhapus saat rollback?

**Contoh jawaban:** Migration mencatat perubahan struktur database dalam kode. `up()` membuat atau mengubah tabel; `down()` membalik perubahan. Pada tabel inti SekolahKu, `down()` memeriksa apakah masih ada data dan membatalkan rollback jika penghapusan dapat menghilangkan riwayat.
