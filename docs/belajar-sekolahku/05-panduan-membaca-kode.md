# 5. Panduan Membaca Kode

Mulai dari URL/route, ikuti controller ke Form Request, service, model, lalu Blade dan test. Jangan mulai hanya dari nama file; cari route name di folder routes untuk memastikan halaman itu memang dipakai.

## Peta berkas

| Lapisan | Tanggung jawab di SekolahKu | Contoh |
|---|---|---|
| Route | Memetakan URL, HTTP method, nama route, dan grup middleware. | routes/web.php, routes/admin.php, routes/guru.php. |
| Middleware | Menolak pengguna yang belum login, akunnya tidak aktif, atau rolenya tidak cocok. | EnsureActiveAccount.php, EnsureUserRole.php. |
| Form Request | Memvalidasi input request dan, pada beberapa request, otorisasi awal. | app/Http/Requests/ khususnya subfolder Admin/, Guru/, dan Auth/. |
| Controller | Mengatur alur HTTP: menerima request, memanggil service/model, memilih view/redirect/response. | app/Http/Controllers/. |
| Service | Memusatkan proses multi-langkah/transaksi dan query domain yang dapat digunakan controller. | app/Services/. |
| Model | Menentukan tabel, mass assignment, cast, relasi, dan scope Eloquent. | app/Models/. |
| Policy/Gate | Memeriksa aksi atas class/record. | app/Policies/, pemanggilan Gate::authorize dan $user->can(...). |
| View | Menghasilkan antarmuka server-rendered. | resources/views/, komponen reusable di resources/views/components/. |
| Asset frontend | CSS Tailwind, JavaScript interaksi, konfigurasi bundler. | resources/css/app.css, resources/js/app.js, vite.config.js. |
| Test | Memeriksa hasil request dan integritas perubahan melalui perilaku aplikasi. | tests/Feature/*Test.php. |

## Contoh nyata: login

Route POST /login ada di routes/web.php, lalu diarahkan ke AuthenticatedSessionController::store. Cuplikan dari app/Http/Controllers/Auth/AuthenticatedSessionController.php (sekitar baris 41–47):

    $authenticated = Auth::attemptWhen(
        [
            'email' => $email,
            'password' => $request->validated('password'),
        ],
        static fn (User $user): bool => $user->hasSupportedRole() && $user->isActiveAccount(),
    );

Email dan password berasal dari hasil validasi, bukan seluruh payload mentah. Callback membatasi role yang dikenal dan mensyaratkan akun aktif. Sesudah berhasil, controller meregenerasi session (sekitar baris 58), lalu mengarahkan berdasarkan User::dashboardRouteName(). Logout melakukan Auth::logout(), invalidasi sesi, dan regenerasi token CSRF.

## Contoh nyata: pencatatan dan snapshot poin

Form route dan endpoint pencarian dideklarasikan pada routes/guru.php (baris 20–22). Form ada di resources/views/guru/pelanggaran/create.blade.php; JavaScript mengirim pencarian ke endpoint yang URL-nya disisipkan Blade. Controller pencarian memeriksa tanggal pada penempatan, bukan mengambil kelas siswa secara sembarang dari data terbaru.

PencatatanPelanggaranService::record membuka transaksi (app/Services/PencatatanPelanggaranService.php, baris 18–20). Service menemukan Guru melalui user login, memeriksa penempatan dan periode tanggal, memilih jenis yang masih boleh dicatat, lalu menyimpan:

    'poin_saat_dicatat' => $violation->poin,

Baris ini (sekitar 77) menyalin poin master ke catatan sebagai snapshot. Request tidak menerima poin/Guru/status sebagai sumber kebenaran. Setelah membuat record, service menulis log aksi tambah sebelum transaksi selesai. Jika salah satu operasi gagal, transaksi membatalkan semuanya.

## Contoh nyata: cakupan Riwayat Pelanggaran dan Catatan Saya

PelanggaranSiswa::scopeVisibleTo berada di app/Models/PelanggaranSiswa.php (baris 50–56). Untuk role yang didukung, scope mengembalikan query tanpa membatasi pencatat. Riwayat lalu menerapkan pencarian, filter, urutan, dan pagination di RiwayatPelanggaranController::index (baris 20–52).

Catatan Saya sengaja punya controller/query lain. CatatanSayaController::index (baris 18–27) mengambil identitas Guru aktif lalu menggunakan:

    ->whereBelongsTo($teacher, 'guru')

Karena itu daftar ini personal. Detail Catatan Saya juga memeriksa Policy viewOwn; ia tidak sekadar mengandalkan link yang tersembunyi.

## Contoh nyata: koreksi dan audit

RiwayatPelanggaranService::update (sekitar baris 17–50) memulai transaksi, mengunci row catatan, memanggil Gate::authorize('update', ...), menyimpan nilai sebelum perubahan, memperbarui field yang diizinkan, lalu menulis data sebelum/sesudah beserta alasan ke t_log_pelanggaran. Pembatalan mengikuti pola serupa, hanya mengubah status menjadi dibatalkan. PelanggaranSiswaPolicy mengizinkan Admin mengelola semua catatan dan Guru hanya catatannya sendiri; Policy tidak memberikan penghapusan permanen.

## Contoh nyata: rekap wali kelas

Route rekap berada di routes/guru.php (baris 16–19), seluruhnya tetap dalam grup role:guru. RekapKelasController memanggil RekapWaliKelasReport; service memvalidasi assignment dan parameter kelas/tahun yang diminta terhadap penugasan Guru login sebelum membentuk query penempatan/catatan. Ringkasan aktif dibangun dengan agregat pada poin_saat_dicatat dan kondisi status; tabel rincian mengambil record secara terpisah. Cara ini menghindari mencampur kolom detail dengan agregat tanpa pengelompokan. PDF menggunakan Pdf::loadView; Excel memakai Excel::download dan dua sheet export khusus.

## Cara menelusuri fitur lain

Contoh daftar siswa:

1. Cari nama route di routes/admin.php atau routes/guru.php.
2. Ikuti controller index.
3. Periksa request pencarian/filter yang di-inject dan service SiswaReadService.
4. Pastikan relasi/cakupan di model Siswa, KelasSiswa, dan Policy.
5. Lihat resources/views/siswa/index.blade.php untuk rendering dan komponen pagination.
6. Lihat tests/Feature/SiswaManagementTest.php untuk hasil yang diharapkan.

Untuk tiap perubahan, periksa juga test fitur yang sepadan. Test bukan hanya dokumentasi; test menjalankan perilaku dan menjadi bukti regresi yang dapat diulang.
