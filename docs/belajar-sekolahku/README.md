# Belajar SekolahKu

Panduan ini membantu siswa RPL memahami implementasi SekolahKu dari kode yang ada di repository. Isinya memilih bagian penting dan menjelaskan hubungan antarfile; bukan salinan source code.

## Urutan belajar

| Urutan | Bab | Isi | Status |
|---|---|---|---|
| 1 | [Gambaran Besar SekolahKu](01-gambaran-besar.md) | Pengguna, teknologi, struktur aplikasi, dan alur request | Dibuat |
| 2 | [Database MySQL](02-database-mysql.md) | Koneksi, tabel domain, relasi, indeks, dan ERD migration | Dibuat |
| 3 | [Migration dan Seeder](03-migration-seeder.md) | Perubahan skema, data lokal, factory, dan risiko rollback | Dibuat |
| 4 | Model Eloquent | Model, mass assignment, casts, relasi, dan query | Menunggu tahap berikutnya |
| 5 | Route, Controller, dan Middleware | Request, pembatasan role, validasi, dan response | Menunggu tahap berikutnya |
| 6 | Blade dan Frontend Laravel | Layout, komponen, form, dan tampilan data | Menunggu tahap berikutnya |
| 7 | Tailwind CSS | Utility class, responsive design, dan Vite | Menunggu tahap berikutnya |
| 8 | Validasi dan Keamanan | Validasi, sesi, otorisasi, CSRF, output, dan rahasia | Menunggu tahap berikutnya |
| 9 | Alur Fitur SekolahKu | Penelusuran fitur dari form sampai hasil | Menunggu tahap berikutnya |
| 10 | Testing, Debugging, dan Git | Test, build, error, dan kerja dengan Git | Menunggu tahap berikutnya |
| 11 | Persiapan Tanya Jawab UKK | Pertanyaan latihan dengan jawaban sesuai implementasi | Menunggu tahap berikutnya |

## Hubungan antar bab

Mulai dari gambaran besar, lalu pelajari tempat data disimpan (database), bagaimana skema database dibuat (migration), dan bagaimana aplikasi membaca data (Model Eloquent). Setelah itu ikuti jalur request melalui route, controller, validasi, middleware, dan Blade. Bab fitur akan menggabungkan konsep-konsep tersebut; bab keamanan dan pengujian membantu memeriksa apakah alurnya aman dan bekerja.

## Catatan akurasi

Contoh dan diagram pada Bab 1–3 diturunkan dari kode yang saat ini ada, terutama file migration, model, route, konfigurasi, dan seeder. Koneksi ke MySQL lokal tidak berhasil saat audit terakhir karena server menolak koneksi pada `127.0.0.1:3306`; jadi ERD di Bab 2 menjelaskan struktur yang ditulis oleh migration, bukan hasil inspeksi langsung database yang sedang berjalan.

Repository juga berisi `database/db_ukk_2026 (1).sql`. Pemeriksaan nama tabel pada dump itu menemukan sepuluh tabel domain berawalan `t_` dan tidak menemukan `t_log_pelanggaran`, sedangkan migration saat ini membuat sebelas tabel domain. Karena ada perbedaan tersebut, panduan memakai migration sebagai sumber skema aplikasi saat ini dan tidak menyatakan dump SQL itu identik atau aman untuk diimpor.
