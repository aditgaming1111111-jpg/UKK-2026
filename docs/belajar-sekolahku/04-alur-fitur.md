# 4. Alur Fitur dan Data

Dokumen ini menelusuri proses pengguna sampai database dan tampilan. Rute lengkap yang terdaftar dapat diperiksa dengan `php artisan route:list`; nama route di bawah cocok dengan deklarasi repository saat dokumentasi diperbarui.

## 1. Login, akses, dan profil

1. Browser membuka `GET /login` → `AuthenticatedSessionController::create` menampilkan `resources/views/auth/login.blade.php` (atau mengarahkan pengguna yang sudah login ke dashboard).
2. Form mengirim `POST /login` dengan CSRF → `LoginRequest` memvalidasi email dan password.
3. `AuthenticatedSessionController::store` menormalisasi email, membatasi percobaan login, lalu mengautentikasi hanya role `admin`/`guru` dengan akun aktif.
4. Sesi diregenerasi setelah login berhasil; nama route dashboard dipilih dari role.
5. Route internal mendapat middleware `auth` dan `account.active`. Route grup Admin menambahkan `role:admin`; grup Guru menambahkan `role:guru`. Jika akun Guru dinonaktifkan saat sesi masih berjalan, middleware akun aktif membatalkan sesi.
6. Profil memakai `UpdateProfileRequest` dan `UpdatePasswordRequest`. Profil Guru memperbarui nama akun dan identitas Guru dalam transaksi. Ganti password meminta password sekarang dan konfirmasi password baru.

**Berkas:** `routes/web.php`, `routes/admin.php`, `routes/guru.php`, `app/Http/Controllers/Auth/AuthenticatedSessionController.php`, `app/Http/Requests/Auth/LoginRequest.php`, `app/Http/Middleware/EnsureActiveAccount.php`, `app/Http/Middleware/EnsureUserRole.php`, `app/Http/Controllers/ProfileController.php`, Form Request profil, `app/Models/User.php`, `resources/views/auth/login.blade.php`, `resources/views/profile/edit.blade.php`.

## 2. Dashboard

**Admin.** `GET /admin` memanggil `AdminDashboardController::__invoke`. Dashboard memilih tahun ajaran dari filter, menghitung siswa dan Guru aktif serta catatan/poin aktif untuk tahun itu, meringkas jenis per kategori, dan mengambil catatan serta log terbaru. Data dirender di `resources/views/admin/dashboard.blade.php`. Angka berasal dari query, bukan konstanta tampilan.

**Guru.** `GET /guru` memanggil `GuruDashboardController::__invoke`. Query catatan dashboard dibatasi ke `guru_id` milik Guru login dan tahun ajaran terpilih. Sistem menghitung semua catatan Guru pada periode, status aktif, dan status dibatalkan, menampilkan catatan terbaru, serta mencari penugasan wali kelas yang valid untuk periode. Jika tidak ada penugasan, bagian rekap tidak dikirim sebagai aktif ke view `resources/views/guru/dashboard.blade.php`. Hak ini hanya memengaruhi rekap, bukan pencatatan Guru ke kelas lain.

## 3. Siswa dan riwayat penempatan

Admin memakai route `admin.students.*` untuk tambah, lihat, ubah, menonaktifkan/mengaktifkan identitas, dan membuat penempatan. Guru memakai `guru.students.*` hanya untuk membaca.

- `StoreSiswaRequest` / `UpdateSiswaRequest` menjaga NISN tepat 10 digit dan unik serta memvalidasi nama, jenis kelamin, tahun masuk, dan field terkait.
- `SiswaReadService::directory` membentuk query pencarian/filter, eager-load penempatan yang dianggap saat ini, lalu pagination 10 data per halaman.
- `SiswaReadService::detail` mengambil riwayat penempatan, riwayat catatan (dengan cakupan sesuai pengguna), dan ringkasan poin aktif per tahun ajaran.
- `SiswaPenempatanService` membuat penempatan dalam transaksi dan menolak rentang yang bertentangan. Penempatan lama tidak diganti dengan identitas siswa baru.
- Foreign key restrict dan aksi status menjaga riwayat tetap terhubung. Catatan historis menyimpan referensi penempatan yang dipilih saat kejadian.

**Berkas utama:** `app/Http/Controllers/Admin/SiswaController.php`, `app/Http/Controllers/Guru/SiswaController.php`, `app/Services/SiswaReadService.php`, `app/Services/SiswaPenempatanService.php`, `app/Policies/SiswaPolicy.php`, `app/Http/Requests/Admin/*Siswa*.php`, `resources/views/siswa/`.

## 4. Guru dan akun pengguna

Admin memakai `admin.teachers.*`. Identitas Guru di `t_guru` dan akun di `t_users` dibuat/diubah bersama dalam transaksi. `StoreGuruRequest` memeriksa NIP unik tanpa menetapkan pola 18 digit, nama maksimal 100, email valid dan tidak dipakai akun/data Guru lain, serta password login minimal 8 karakter dan terkonfirmasi. Password di-hash. Admin tidak membutuhkan identitas Guru; akun role Guru harus terkait identitas Guru aktif agar bisa login. Menonaktifkan Guru mempertahankan record, penugasan, dan catatan lamanya.

**Berkas:** `app/Http/Controllers/Admin/GuruController.php`, `app/Services/GuruDirectory.php`, `app/Models/Guru.php`, `app/Models/User.php`, `app/Policies/GuruPolicy.php`, `app/Http/Requests/Admin/*Guru*.php`, `resources/views/admin/guru/`.

## 5. Kelas, periode akademik, dan wali kelas

Ketiga modul ini hanya dapat dikelola Admin.

- Kelas: `admin.classes.*`; kelas dengan riwayat terkait tidak dihapus permanen.
- Tahun ajaran: `admin.academic-years.*`; `TahunAjaranManager` menjalankan perubahan yang saling terkait dalam transaksi, memeriksa validitas tanggal dan benturan periode, serta mempertahankan tahun lama.
- Penugasan wali kelas: route `admin.homeroom-teachers.*`; `WaliKelasAssignmentService::assign` mengunci data kelas/Guru yang terkait, memeriksa Guru aktif dan tumpang tindih, lalu menutup penugasan lama dan membuat penugasan baru dalam transaksi. `close` mengakhiri penugasan dengan tanggal, bukan menghapus sejarah.

Saat menggunakan rekap, penugasan dicocokkan dengan guru login, kelas, tahun ajaran, dan tanggal periode. Mengubah ID kelas/tahun di URL tidak seharusnya memberi akses ke kelas di luar penugasan karena service menghitung ulang cakupan.

**Berkas:** controller Admin kelas/tahun/wali kelas, `TahunAjaranManager`, `WaliKelasAssignmentService`, model masing-masing, Policy, Form Request Admin, dan Blade `resources/views/admin/kelas/`, `tahun-ajaran/`, `wali-kelas/`.

## 6. Kategori dan jenis pelanggaran

Admin mengelola `admin.violation-categories.*` dan `admin.violations.*`. Kategori atau jenis yang tidak lagi digunakan dibuat nonaktif, bukan dihapus permanen. Untuk pencatatan baru, scope `Pelanggaran::availableForRecording()` mensyaratkan jenis dan kategori sama-sama aktif. Jenis nonaktif tetap dapat dibaca pada catatan historis melalui relasi catatan.

Validasi create/update ada pada `StorePelanggaranRequest`, `UpdatePelanggaranRequest`, dan pasangan request kategori. Kode jenis unik, poin master 1–100, nama 100 karakter.

**Berkas:** controller Admin kategori/jenis, `app/Models/Pelanggaran.php`, model kategori, Policy dan Form Request, `resources/views/admin/kategori-pelanggaran/`, `resources/views/admin/jenis-pelanggaran/`.

## 7. Pencatatan pelanggaran

1. Guru membuka `GET /guru/pelanggaran/create`. Controller mengirim kategori aktif dan jenis aktif beserta poin untuk ditampilkan.
2. JavaScript pada `resources/js/app.js` menunggu input pencarian dan tanggal kejadian, lalu memanggil `GET /guru/pelanggaran/cari-siswa?q=...&tanggal_pelanggaran=...` dengan jeda singkat.
3. `SearchSiswaPenempatanRequest` memvalidasi kueri/tanggal. `PelanggaranSiswaController::searchStudents` mencari NISN/nama pada penempatan yang tanggalnya dan tahun ajarannya memuat tanggal kejadian; hasil dibatasi 20 dan mengembalikan ID penempatan, identitas, kelas, serta tahun ajaran.
4. Form mengirim `POST /guru/pelanggaran` dengan `kelas_siswa_id`, jenis, tanggal, keterangan, dan tindakan. Input guru, poin, status, kelas, dan tahun ajaran bebas dari browser tidak dipercaya.
5. `StorePelanggaranSiswaRequest` memvalidasi bentuk input dan mencocokkan tanggal dengan penempatan serta periode. `PencatatanPelanggaranService::record` dalam transaksi mengambil Guru aktif dari akun login, mengunci/memeriksa penempatan, menghitung tanggal berlaku, mengambil master jenis yang masih aktif, membuat catatan aktif dengan snapshot poin, kemudian menulis log aksi tambah.
6. Setelah berhasil, pengguna diarahkan ke detail siswa.

**Berkas:** route Guru, `Guru/PelanggaranSiswaController.php`, `SearchSiswaPenempatanRequest.php`, `StorePelanggaranSiswaRequest.php`, `PencatatanPelanggaranService.php`, `Pelanggaran.php`, `resources/views/guru/pelanggaran/create.blade.php`, `resources/js/app.js`.

## 8. Riwayat, koreksi, pembatalan, dan Catatan Saya

**Riwayat Pelanggaran** memakai `RiwayatPelanggaranController::index` dan `PelanggaranSiswa::visibleTo`. Role Admin/Guru yang didukung dapat melihat seluruh catatan; halaman menyediakan pencarian nama/NISN dan filter tahun ajaran, kelas, jenis, Guru, tanggal, serta status. Hasil diurutkan berdasarkan tanggal kejadian lalu ID menurun, dipaginasi 10 data, dan filter dipertahankan pada link pagination.

**Catatan Saya** memiliki controller berbeda, `CatatanSayaController`, dengan query `whereBelongsTo($teacher, 'guru')`. Itu tetap membatasi hasil dan detail pada Guru login; halaman tidak digabung dengan Riwayat Pelanggaran.

Untuk perubahan, `PelanggaranSiswaPolicy` membolehkan Admin mengoreksi/membatalkan siapa pun; Guru hanya catatannya sendiri. `RiwayatPelanggaranService` mengunci record dan menjalankan perubahan serta penulisan log dalam satu transaksi. Koreksi memerlukan alasan dan memeriksa ulang tanggal. Pembatalan mengubah status menjadi `dibatalkan`; record tidak dihapus. Tidak tersedia penghapusan permanen dari route ini.

**Berkas:** route Admin/Guru, `RiwayatPelanggaranController.php`, `CatatanSayaController.php`, dua Form Request index serta request koreksi/batal, `PelanggaranSiswaPolicy.php`, `RiwayatPelanggaranService.php`, model catatan/log, views `resources/views/pelanggaran-siswa/` dan `resources/views/guru/catatan-saya/`.

## 9. Rekap wali kelas, laporan, dan ekspor

**Rekap wali kelas** berada di `GET /guru/rekap-kelas`, detail siswa, serta endpoint PDF/Excel. `RekapWaliKelasReport` membentuk konteks berdasarkan Guru, penugasan yang valid, tahun ajaran, kelas yang ditugaskan, periode penempatan siswa, tanggal kejadian, dan filter nama/NISN serta tanggal. Ringkasan menghitung siswa, siswa dengan pelanggaran aktif, jumlah catatan aktif, dan total poin snapshot aktif. Siswa tanpa catatan aktif tetap dapat muncul di tabel rekap. Detail dapat menampilkan catatan yang dibatalkan tanpa menjumlahkannya sebagai aktif. Export memakai data dengan cakupan akses/filter yang sama; Excel mempunyai sheet ringkasan dan rincian. Ekspor tidak dibatasi hanya pada 10 record halaman web.

**Laporan Admin** berada di `/admin/laporan`, `/admin/laporan/cetak`, dan `/admin/laporan/pdf`. `LaporanPelanggaranService::query` menerapkan filter pada query database; `summary` menghitung catatan/poin aktif dan catatan batal. Halaman memakai pagination 10; cetak/PDF memuat seluruh hasil yang cocok dengan filter dan tidak hanya halaman paginator.

**Berkas:** `Guru/RekapKelasController.php`, `RekapWaliKelasReport.php`, tiga class `app/Exports/RekapWaliKelas*.php`, views `resources/views/guru/rekap-kelas/`; controller Admin laporan, `LaporanPelanggaranService.php`, `resources/views/admin/laporan/index.blade.php`, `resources/views/laporan/print.blade.php`.

## 10. Pagination dan antarmuka

Daftar utama memakai paginator Laravel dengan batas 10 pada query, bukan mengambil semua record lalu memotong di PHP. Query filter diterapkan sebelum `paginate(10)`; `withQueryString()` mempertahankan parameter saat pindah halaman. Komponen `resources/views/components/ui/pagination.blade.php` menyajikan tampilan navigasi sesuai UI aplikasi. Riwayat pelanggaran pada detail siswa juga dipaginasi; ringkasan poin tetap dihitung dari seluruh catatan yang sesuai, bukan hanya baris halaman saat ini.

Layout Admin/Guru berbagi shell dan komponen UI Blade. CSS sumber berada di `resources/css/app.css`; perilaku modal/toast dan pencarian form pelanggaran berada di `resources/js/app.js`; Vite membundel keduanya melalui `vite.config.js`.
