# Panduan Belajar SekolahKu

Indeks ini menghubungkan panduan developer, referensi database, alur fitur, dan persiapan presentasi UKK. Gunakan [README utama](../../README.md) hanya untuk langkah instalasi dan menjalankan aplikasi; README utama tidak diubah sebagai bagian pekerjaan ini.

## Urutan belajar yang disarankan

1. [Gambaran besar](01-gambaran-besar.md) — pahami role, arsitektur, fitur dan aturan inti.
2. [Database MySQL](02-database-mysql.md) — pelajari 11 tabel inti dan tabel log aktivitas tambahan, kolom, foreign key, indeks dan batas verifikasi.
3. [Migration dan seeder](03-migration-seeder.md) — pahami urutan pembuatan skema serta akun pengembangan yang aman.
4. [Alur fitur](04-alur-fitur.md) — ikuti setiap proses dari route hingga database/view dan ekspor.
5. [Panduan membaca kode](05-panduan-membaca-kode.md) — pelajari fungsi lapisan Laravel dan contoh kode aktual.
6. [Validasi, keamanan, dan pengujian](06-validasi-keamanan-pengujian.md) — cek aturan yang diterapkan, test yang ada, hasil test, serta yang belum diverifikasi.
7. [Persiapan UKK](07-persiapan-ukk.md) — latihan jawaban penguji dan cara mendemonstrasikan satu fitur.

8. [Panduan penggunaan dan tanya jawab](08-panduan-penggunaan-dan-tanya-jawab.md) - panduan Admin/Guru, ringkasan perubahan, teknologi, alasan desain, serta pertanyaan penguji tingkat lanjut.

## Peta cepat

Catatan database: sebelas tabel inti SekolahKu ditambah `t_log_aktivitas` sebagai audit aktivitas umum, sehingga saat ini terdapat 12 tabel aplikasi berawalan `t_`.

| Kebutuhan | Dokumen |
|---|---|
| Setup dan menjalankan aplikasi di Laragon | [README utama](../../README.md) |
| Role, request, dan ringkasan sistem | [Gambaran besar](01-gambaran-besar.md) |
| Tabel, relasi, constraint dan ERD migration | [Database MySQL](02-database-mysql.md) |
| Migration, seeder, factory | [Migration dan seeder](03-migration-seeder.md) |
| Alur siswa, Guru, catatan, rekap, laporan | [Alur fitur](04-alur-fitur.md) |
| Route/controller/request/service/model/view/test | [Panduan membaca kode](05-panduan-membaca-kode.md) |
| Validasi, keamanan, dan hasil test | [Validasi dan pengujian](06-validasi-keamanan-pengujian.md) |
| Latihan pertanyaan dan demonstrasi UKK | [Persiapan UKK](07-persiapan-ukk.md) |
| Panduan memakai aplikasi, perubahan fitur, alasan desain, dan pertanyaan penguji mendalam | [Panduan penggunaan dan tanya jawab](08-panduan-penggunaan-dan-tanya-jawab.md) |

## Sumber akurasi dan batas dokumentasi

Dokumen teknis ini dicocokkan dengan kode aplikasi, migration, Form Request, test, route, model, Blade, konfigurasi dependency, dan riwayat Git yang tersedia di repository. Ringkasan fitur menggambarkan keadaan kode sampai commit `8a23517` pada 8 November 2026; angka test diberi tanggal agar tidak disalahartikan sebagai hasil yang selalu terbaru.

Skema database dalam dokumen merujuk pada migration. Dokumen tidak menyatakan bahwa dump SQL atau database MySQL lokal memiliki struktur/data yang identik, karena keduanya tidak diperiksa dalam pembaruan docs ini. Jangan menaruh nilai rahasia dari .env atau data pribadi siswa/Guru pada dokumentasi. README root tetap menjadi panduan instalasi dan menjalankan proyek; panduan penggunaan aplikasi ada di dokumen 08.
