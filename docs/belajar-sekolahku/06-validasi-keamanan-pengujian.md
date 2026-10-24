# 6. Validasi, Keamanan, dan Pengujian

## Aturan validasi yang terverifikasi

| Data/fitur | Implementasi saat ini | Sumber utama |
|---|---|---|
| NISN | Wajib tepat 10 digit dan unik; unique index juga ada di skema. | StoreSiswaRequest, UpdateSiswaRequest, migration t_siswa. |
| Nama siswa/Guru/jenis/kategori/profil | Wajib sesuai form dan maksimal 100 karakter pada request tambah/edit yang relevan. | Store/UpdateSiswaRequest, Store/UpdateGuruRequest, Store/UpdatePelanggaranRequest, pasangan request kategori, UpdateProfileRequest. |
| NIP Guru | Wajib, string maksimal 255, unik. Tidak ada aturan 18 digit; migration hanya membuat kolom string dan unique. | StoreGuruRequest, UpdateGuruRequest, migration t_guru. |
| Email | Format email dan unik di akun t_users. Form identitas Guru juga memvalidasi agar email tidak bentrok dengan akun lain serta identitas Guru lain. | Store/UpdateGuruRequest, UpdateProfileRequest; t_users punya unique email, t_guru.email tidak punya unique index di migration. |
| Password login Guru | Minimal 8 karakter dan perlu konfirmasi. | StoreGuruRequest dan update akun terkait. |
| Ganti password profil | Meminta password saat ini, password baru minimal 8 karakter, dan konfirmasi. Model User memakai cast hashed; seeder juga memakai Hash::make. | UpdatePasswordRequest, User, AuthenticationSeeder. |
| Jenis pelanggaran | Kode unik; nama maksimal 100; poin bilangan bulat minimal 1 dan maksimal 100; kategori harus aktif ketika membuat jenis baru. | StorePelanggaranRequest, UpdatePelanggaranRequest. |
| Akumulasi poin siswa | Tidak ada batas jumlah total 100; agregat menjumlahkan snapshot pada record aktif. | Query dashboard, detail siswa, rekap, dan laporan. |
| Tahun masuk | Bilangan bulat antara 1900 sampai 2100. | StoreSiswaRequest, UpdateSiswaRequest. |
| Tahun ajaran | Nama unik sesuai Form Request; tanggal selesai tidak boleh sebelum tanggal mulai; manager memeriksa benturan periode. | Store/UpdateTahunAjaranRequest, TahunAjaranManager. |
| Tanggal kejadian | Wajib tanggal valid dan tidak boleh melewati hari ini. Harus tercakup oleh tanggal penempatan dan tanggal tahun ajaran yang dipilih. | StorePelanggaranSiswaRequest, SearchSiswaPenempatanRequest, PencatatanPelanggaranService. |
| Koreksi/pembatalan | Alasan diwajibkan; tanggal koreksi divalidasi lagi terhadap konteks penempatan/tahun catatan. | UpdatePelanggaranSiswaRequest, CancelPelanggaranSiswaRequest, RiwayatPelanggaranService. |
| Penempatan dan wali kelas | ID FK harus ada; tanggal diverifikasi service terhadap periode, dan konflik rentang ditolak. | StorePenempatanKelasRequest, StoreWaliKelasRequest, SiswaPenempatanService, WaliKelasAssignmentService. |

Batas validasi aplikasi berbeda dari batas fisik kolom. Contohnya, migration mendefinisikan nama sebagai string standar (umumnya sampai 255 karakter di MySQL), sedangkan Form Request membatasi nama pengguna/jenis/kategori/profil sampai 100. Poin master dan snapshot memakai unsigned small integer di migration, sementara batas bisnis poin master 1–100 ditegakkan request.

Tidak semua aturan diwujudkan sebagai constraint SQL. Benturan tanggal penempatan/penugasan diperiksa service, sehingga perubahan melalui jalur selain aplikasi perlu berhati-hati.

## Keamanan yang diterapkan

- Login hanya menerima role yang didukung dan akun aktif; percobaan login dibatasi, sesi diregenerasi saat berhasil, dan logout menginvalidasi sesi.
- Route internal berada di belakang middleware auth dan account.active; grup route Admin dan Guru memberi pemeriksaan role terpisah.
- Form HTML memakai CSRF Laravel. Input POST/PUT/PATCH divalidasi di server; pemilihan nilai penting (identitas Guru, poin, status, kelas/tahun dari penempatan) ditentukan/ditinjau backend.
- Model membatasi atribut mass assignment dengan Fillable. Query Eloquent menggunakan parameter binding; nilai search di-escape untuk wildcard LIKE pada query yang relevan.
- Policy melindungi detail/koreksi/pembatalan. Riwayat bersama dan Catatan Saya punya query berbeda.
- Foreign key restrict membantu mencegah penghapusan referensi historis. Siswa, Guru, kategori/jenis, tahun ajaran, dan penugasan dipertahankan atau dinonaktifkan sesuai alur aplikasi, bukan dihapus permanen bila masih terkait.
- Audit tambah/koreksi/batal menyimpan pengguna, aksi, data sebelum/sesudah, alasan, waktu; transaksi menjaga konsistensi catatan dan log.
- View login dan profil tidak perlu mengirim nilai password tersimpan. Kolom password disembunyikan dari serialisasi model User.

Batas penting: keamanan konfigurasi di luar kode (nilai rahasia, hak akses server, backup, TLS, produksi) harus diperiksa pada deployment masing-masing. Jangan menyalin .env ke dokumentasi.

## Test yang tersedia

Test PHPUnit berbasis feature berada di tests/Feature/. Cakupan penting:

| File | Contoh perilaku yang diuji |
|---|---|
| AuthenticationTest.php | Login Admin/Guru, kredensial gagal, logout, route internal, lintas role, akun Guru nonaktif. |
| SiswaManagementTest.php | CRUD/status siswa, NISN duplikat, batas nama, penempatan/riwayat, benturan kelas, detail dan pagination. |
| GuruManagementTest.php | Identitas-akun Guru, email duplikat, relasi akun, akun tidak aktif, batas nama, pagination dan hak Admin. |
| KelasManagementTest.php, TahunAjaranManagementTest.php, WaliKelasManagementTest.php | CRUD yang diizinkan, konflik periode, riwayat dan perlindungan akses. |
| PelanggaranMasterManagementTest.php | Kode duplikat, batas poin 1/100, nama 100 karakter, kategori nonaktif, historis setelah master dinonaktifkan. |
| GuruPencatatanPelanggaranTest.php | Input manipulatif, Guru/poin/status dari server, tanggal dan penempatan, master nonaktif, snapshot poin. |
| RiwayatPelanggaranTest.php, CatatanSayaTest.php | Cakupan seluruh riwayat Guru vs catatan pribadi, filter, pagination, koreksi/batal, audit dan poin. |
| LaporanSekolahTest.php | Filter laporan, PDF/cetak, batas wali kelas, tahun historis, PDF/Excel dan hasil melebihi 10 baris. |
| AdminDashboardTest.php, GuruDashboardTest.php | Statistik database, filter tahun ajaran, empty state, pembatasan role dan penugasan wali kelas. |
| ProfileTest.php | Privasi hash password, pembaruan profil, nama/email, perubahan password. |

## Hasil run terbaru

Pada 24 Oktober 2026, perintah php artisan test --compact dijalankan setelah audit dokumentasi dan sebelum perubahan dokumentasi:

- **Berhasil:** 112 test.
- **Gagal:** 0.
- **Assertions:** 751.
- **Durasi yang dilaporkan PHPUnit:** sekitar 11,957 detik.

Perubahan pada pekerjaan ini hanya file Markdown; tidak mengubah perilaku aplikasi. Hasil di atas adalah hasil test yang benar-benar dijalankan, bukan jaminan MySQL produksi atau browser nyata.

## Yang belum diverifikasi

- Kecocokan skema/data MySQL lokal dengan migration atau dump tidak diperiksa dalam pekerjaan dokumentasi ini.
- Test suite menggunakan SQLite in-memory sesuai phpunit.xml, bukan membuktikan seluruh perilaku spesifik MySQL.
- Tidak dilakukan pengujian visual browser desktop/mobile atau interaksi mouse/touch/keyboard.
- Tidak dijalankan build frontend khusus untuk perubahan Markdown.
- Hasil test dapat berubah jika kode, dependency, konfigurasi, atau data uji berubah; jalankan kembali php artisan test pada commit yang akan dinilai.
