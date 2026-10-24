# Belajar SekolahKu

Catatan ini menjelaskan alur dan struktur yang ada di repository, dengan contoh yang diringkas agar mudah dipelajari.

## Urutan belajar

1. [Gambaran besar](01-gambaran-besar.md) — role, fitur, dan alur request.
2. [Database MySQL](02-database-mysql.md) — sebelas tabel aplikasi dan hubungan pentingnya.
3. [Migration dan seeder](03-migration-seeder.md) — cara skema dibangun dan akun lokal disiapkan.

README utama menjelaskan [cara menyiapkan dan menjalankan aplikasi](../../README.md).

## Sumber dan batas informasi

Penjelasan database merujuk pada migration. Dump `database/ukk_pelanggaran_siswa.sql` tersedia di repository dan memuat nama tabel domain yang sama, tetapi kesamaan semua kolom dan constraint belum diverifikasi. Database lokal yang berjalan juga harus diperiksa terpisah; dokumentasi ini tidak mengklaim telah membaca isi database atau `.env` lokal.
