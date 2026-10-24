# 7. Persiapan Menghadapi Penguji UKK

Jawaban di bawah mengikuti kode SekolahKu saat ini. Saat presentasi, buka file yang dirujuk dan tunjukkan alurnya; hindari mengklaim aturan yang tidak ada pada request, service, migration, atau test.

## Ringkasan proyek dalam satu menit

> SekolahKu adalah aplikasi Laravel 13 untuk administrasi data sekolah dan pencatatan pelanggaran siswa. Role login hanya Admin dan Guru. Identitas siswa memakai NISN unik, sementara kelas siswa dan wali kelas dicatat sebagai histori per tahun ajaran. Ketika Guru mencatat pelanggaran, backend memeriksa penempatan yang berlaku pada tanggal kejadian, mengambil poin dari master aktif, lalu menyimpan snapshot poin. Catatan koreksi atau pembatalan dicatat ke log dalam transaksi. Admin mengelola sistem dan laporan seluruh sekolah; Guru dapat mencatat dari kelas mana pun dan melihat riwayat yang diizinkan. Guru dengan penugasan wali kelas yang sesuai memperoleh rekap kelas khusus.

Tunjukkan gambaran dan teknologi di 01-gambaran-besar.md, struktur database di 02-database-mysql.md, lalu telusuri satu fitur melalui 05-panduan-membaca-kode.md.

## Pertanyaan umum

### 1. Kenapa memakai Laravel, Blade, dan Eloquent?

Laravel menyediakan pola route, middleware, Form Request, Policy, transaksi, session, dan ORM yang digunakan langsung di proyek. Blade merender halaman dari server; Eloquent menghubungkan model dengan tabel dan relasi. Frontend dibangun dari Tailwind CSS, JavaScript seperlunya, dan Vite.

**Bukti:** composer.json, package.json, routes/, app/Models/, resources/views/, vite.config.js.

### 2. Apa bedanya Admin dan Guru? Apakah wali kelas role ketiga?

Tidak. Nilai role login hanya admin atau guru. Wali kelas adalah status penugasan pada t_wali_kelas untuk Guru, kelas, tahun ajaran, dan periode tanggal tertentu. Role menentukan area utama; penugasan menentukan apakah Guru dapat melihat rekap kelas terkait.

**Bukti:** routes/admin.php, routes/guru.php, EnsureUserRole.php, WaliKelas.php, RekapWaliKelasReport.php.

### 3. Apa urutan request dari browser?

Route mencocokkan URL dan HTTP method. Middleware memeriksa autentikasi/status/role. Form Request memvalidasi input. Controller mengatur response dan memanggil service ketika proses terdiri dari beberapa langkah. Service menggunakan model Eloquent dan transaksi. Controller mengembalikan Blade, redirect, file PDF, atau Excel.

### 4. Apa tanggung jawab Form Request?

Form Request mendeklarasikan aturan input dan dapat menolak request yang tidak berhak. Contohnya StoreSiswaRequest memeriksa NISN dan data siswa; StorePelanggaranSiswaRequest memvalidasi tanggal, penempatan, dan jenis. Validasi frontend hanya membantu penggunaan form; keputusan akhir tetap diperiksa backend.

### 5. Untuk apa Policy?

Policy menjawab apakah pengguna tertentu boleh melakukan aksi pada model/record. Contohnya PelanggaranSiswaPolicy mengizinkan Admin mengoreksi siapa pun, sedangkan Guru hanya catatannya sendiri. Controller/service memanggil otorisasi sehingga membuka URL langsung tetap diperiksa.

### 6. Mengapa akun Guru dipisah dari data Guru?

t_users menyimpan email login, password hash, dan role. t_guru menyimpan NIP, nama, status, dan user_id. Relasi user_id unik membuat satu akun terkait satu identitas Guru. Admin tidak wajib mempunyai identitas Guru.

### 7. Bagaimana riwayat kelas siswa dipertahankan?

Identitas siswa dibuat sekali di t_siswa. Setiap penempatan masuk ke t_kelas_siswa dengan kelas, tahun ajaran, tanggal mulai, dan tanggal selesai. Perpindahan membuat/menutup histori, tidak memindahkan atau mengganti identitas siswa. Catatan pelanggaran menunjuk ke kelas_siswa_id sehingga kelas kejadian lama tetap dapat dibaca.

### 8. Bagaimana aplikasi memilih kelas ketika pelanggaran dicatat?

Pencarian menerima tanggal kejadian. Query memilih penempatan yang periode penempatannya dan periode tahun ajarannya mencakup tanggal itu. Penyimpanan memeriksa ulang ID penempatan dan tanggal di backend. Sistem tidak seharusnya memilih kelas hanya karena record itu pertama atau paling baru.

**Bukti:** Guru/PelanggaranSiswaController::searchStudents, SearchSiswaPenempatanRequest, StorePelanggaranSiswaRequest, PencatatanPelanggaranService::record.

### 9. Apa arti snapshot poin dan mengapa diperlukan?

Snapshot adalah nilai poin yang disalin dari master ke poin_saat_dicatat pada saat pencatatan. Jika Admin mengubah master setelahnya, catatan lama tetap memakai poin historisnya. Jika koreksi mengganti jenis pelanggaran, service mengambil snapshot sesuai jenis pengganti dan menyimpan perubahan pada audit.

### 10. Apakah total poin dibatasi 100?

Tidak. Validasi membatasi poin satu jenis pelanggaran pada 1–100, bukan jumlah seluruh poin siswa. Agregat menjumlahkan poin_saat_dicatat dari catatan aktif dan tidak mengikutkan catatan dibatalkan.

### 11. Apa beda Riwayat Pelanggaran dan Catatan Saya?

Riwayat Pelanggaran adalah daftar semua catatan yang boleh dilihat Admin/Guru menurut implementasi sekarang; tidak dibatasi berdasarkan pencatat. Catatan Saya menggunakan relasi Guru login dan hanya menampilkan catatan Guru tersebut. Dua halaman memiliki controller/query dan test terpisah.

### 12. Apa yang terjadi saat catatan dibatalkan?

Record tidak dihapus. Status menjadi dibatalkan, alasan dan data sebelum/sesudah dicatat pada t_log_pelanggaran, serta laporan tetap dapat menampilkan riwayat itu. Query total poin aktif mengecualikannya.

### 13. Bagaimana rekap wali kelas mencegah akses kelas lain?

Service memilih penugasan milik Guru login yang sesuai tahun ajaran/kelas dan tanggal. Bila parameter URL meminta kelas yang tidak termasuk penugasan, service menolak akses; ekspor juga membangun konteks dari service tersebut. Menyembunyikan tombol hanya membantu UI, bukan kontrol utama.

### 14. Mengapa koreksi/pembatalan dan log berada dalam transaksi?

Keduanya harus konsisten. Jika update berhasil tetapi insert audit gagal, sistem tidak boleh menyisakan perubahan tanpa log. DB::transaction memastikan kegagalan salah satu bagian membatalkan rangkaian perubahan.

### 15. Apa fungsi migration, seeder, dan factory?

Migration mendefinisikan skema secara terurut. Seeder mengisi akun contoh untuk local dengan password dari environment dan hashing. Factory membuat fixture data terkontrol untuk test. Test memakai SQLite in-memory sesuai phpunit.xml, bukan database MySQL lokal.

### 16. Bagaimana tahu fitur tidak regresi?

Feature test mengirim request melalui route dan memeriksa status/redirect, isi response, perubahan database, otorisasi, serta hitungan. Run terbaru dalam dokumentasi ini: 112 test dan 751 assertion lulus dengan php artisan test --compact. Angka itu berlaku untuk kondisi repository saat dijalankan dan bukan pengganti uji MySQL/browser pada mesin deployment.

## Penjelasan fitur di depan penguji: contoh pencatatan

1. Tunjukkan routes/guru.php di sekitar deklarasi GET form, GET pencarian, dan POST pencatatan.
2. Tunjukkan resources/views/guru/pelanggaran/create.blade.php: field tanggal, pilihan hasil pencarian, kategori/jenis, keterangan dan tindakan. Hidden placement ID tetap dianggap input tidak terpercaya.
3. Tunjukkan resources/js/app.js: kode mengirim pencarian dengan query dan tanggal serta menampilkan hasil/empty state.
4. Tunjukkan SearchSiswaPenempatanRequest dan Guru/PelanggaranSiswaController::searchStudents untuk validasi dan pencarian tanggal-penempatan.
5. Tunjukkan StorePelanggaranSiswaRequest untuk pemeriksaan server saat submit.
6. Tunjukkan PencatatanPelanggaranService::record: Guru dari user login, tanggal penempatan, jenis aktif, poin snapshot, status awal, audit, transaksi.
7. Tunjukkan model PelanggaranSiswa untuk relasi catatan ke penempatan/jenis/Guru/log.
8. Tunjukkan tests/Feature/GuruPencatatanPelanggaranTest.php, terutama test input manipulatif, penempatan invalid, jenis nonaktif, dan snapshot.

## Pertanyaan lanjutan yang mungkin muncul

**“Kenapa masih simpan pelanggaran_id jika sudah ada poin snapshot?”**
pelanggaran_id menunjuk jenis master untuk nama/kategori dan informasi historis yang terkait; poin_saat_dicatat menjaga nilai poin yang benar pada saat catatan dibuat. Master tetap dapat dinonaktifkan tanpa menghilangkan relasi histori.

**“Kalau siswa pindah kelas, bagaimana laporan kejadian lama?”**
Catatan tetap menunjuk ke record t_kelas_siswa lama. Laporan membaca relasi penempatan itu, sehingga kelas saat kejadian tidak berubah ketika ada penempatan baru.

**“Bisakah Guru mencatat pelanggaran di kelas lain?”**
Ya. Pencatatan tidak mensyaratkan penugasan wali kelas. Backend mensyaratkan Guru aktif dan penempatan siswa yang valid pada tanggal kejadian. Penugasan wali kelas hanya membuka rekap kelas sesuai cakupannya.

**“Kenapa NIP bukan 18 digit?”**
Implementasi menyimpan NIP sebagai string unik dan validasi tidak menetapkan format 18 digit. Proyek belum mengunci apakah nilai ini NIP ASN atau nomor internal sekolah; jangan mengklaim format tertentu.

**“Apakah database aktual sudah cocok dengan ERD?”**
Yang dapat dipastikan dari repository adalah skema migration. Untuk memastikan database pada PC/server, periksa migrate:status dan struktur tabel langsung pada koneksi yang benar. Jangan menyimpulkan dari ERD atau dump saja.

**“Apakah test membuktikan MySQL?”**
Tidak. phpunit.xml mengatur SQLite in-memory. Ini menguji banyak perilaku aplikasi dengan test terisolasi; query/constraint yang spesifik MySQL masih perlu diverifikasi di MySQL development/staging.

## Tips presentasi kode

- Terangkan tujuan file sebelum membaca baris kode.
- Ikuti satu alur secara berurutan: route → middleware/request → controller/service → model/database → view → test.
- Ketika menunjukkan validasi, jelaskan contoh input valid dan tidak valid.
- Ketika menunjukkan query, jelaskan cakupan role dan filter agar tidak sekadar membaca sintaks.
- Sebutkan batas verifikasi dengan jujur: khususnya kondisi database lokal, hasil browser, dan dependency/konfigurasi deployment.
