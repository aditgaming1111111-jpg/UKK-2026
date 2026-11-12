<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuruIndexRequest;
use App\Http\Requests\Admin\StoreGuruRequest;
use App\Http\Requests\Admin\UpdateGuruAccountRequest;
use App\Http\Requests\Admin\UpdateGuruRequest;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\LogPelanggaran;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\GuruDirectory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Menangani permintaan HTTP untuk guru, termasuk penyajian data dan perubahan yang diizinkan.
 */
class GuruController extends Controller
{
    /**
     * Menampilkan daftar guru dengan filter dan paginasi yang berlaku.
     */
    public function index(GuruIndexRequest $request, GuruDirectory $guruDirectory): View
    {
        return view('admin.guru.index', [
            'teachers' => $guruDirectory->paginate($request->validated()),
            'filters' => $request->validated(),
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat guru.
     */
    public function create(): View
    {
        Gate::authorize('create', Guru::class);

        return view('admin.guru.create');
    }

    /**
     * Memvalidasi dan menyimpan guru baru.
     */
    public function store(StoreGuruRequest $request, ActivityLogger $activityLogger): RedirectResponse
    {
        $data = $request->validated();

        $guru = DB::transaction(static function () use ($data): Guru {
            $user = User::query()->create([
                'nama' => $data['nama'],
                'email' => Str::lower(trim($data['email'])),
                'password' => $data['password_login'],
                'role' => 'guru',
            ]);

            return $user->guru()->create([
                'nip' => $data['nip'],
                'status_kepegawaian' => $data['status_kepegawaian'],
                'nama' => $data['nama'],
                'email' => Str::lower(trim($data['email'])),
                'status_aktif' => true,
            ]);
        });
        $activityLogger->record($request->user(), $request, 'Guru ditambahkan', 'Identitas Guru ID #' . $guru->id . ' dan akun login berhasil dibuat.');

        return to_route('admin.teachers.show', $guru)->with('success', 'Data Guru berhasil ditambahkan.');
    }

    /**
     * Menampilkan rincian guru yang dipilih.
     */
    public function show(Guru $guru): View
    {
        Gate::authorize('view', $guru);
        $guru->load(['user:id,nama,email,role', 'waliKelas.tahunAjaran:id,nama_tahun_ajaran', 'waliKelas.kelas:id,nama_kelas']);
        $hasHistory = $guru->waliKelas()->exists()
            || $guru->catatanPelanggaran()->exists()
            || LogPelanggaran::query()->where('user_id', $guru->user_id)->exists();

        return view('admin.guru.show', ['teacher' => $guru, 'hasHistory' => $hasHistory]);
    }

    /**
     * Menyiapkan formulir perubahan untuk guru yang dipilih.
     */
    public function edit(Guru $guru): View
    {
        Gate::authorize('update', $guru);

        return view('admin.guru.edit', ['teacher' => $guru]);
    }

    /**
     * Memvalidasi dan menyimpan perubahan pada guru.
     */
    public function update(UpdateGuruRequest $request, Guru $guru, ActivityLogger $activityLogger): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($guru, $data): void {
            $lockedGuru = Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail();
            $lockedGuru->update([
                'nip' => $data['nip'],
                'status_kepegawaian' => $data['status_kepegawaian'],
                'nama' => $data['nama'],
                'email' => Str::lower(trim($data['email'])),
            ]);

            if ($lockedGuru->user_id !== null) {
                $user = User::query()->whereKey($lockedGuru->user_id)->lockForUpdate()->firstOrFail();

                if ($user->role !== 'guru') {
                    throw ValidationException::withMessages([
                        'akun' => 'Akun yang terhubung tidak memiliki role Guru. Periksa relasi akun sebelum mengubah data.',
                    ]);
                }

                $user->update([
                    'nama' => $lockedGuru->nama,
                    'email' => $lockedGuru->email,
                ]);
            }
        });
        $activityLogger->record($request->user(), $request, 'Data Guru diperbarui', 'Identitas Guru ID #' . $guru->id . ' berhasil diperbarui.');

        return to_route('admin.teachers.show', $guru)->with('success', 'Identitas Guru berhasil diperbarui.');
    }

    /**
     * Menghapus guru setelah pemeriksaan otorisasi dan aturan terkait.
     */
    public function destroy(Guru $guru, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('delete', $guru);

        DB::transaction(function () use ($guru): void {
            $lockedGuru = Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail();
            $user = User::query()->whereKey($lockedGuru->user_id)->lockForUpdate()->firstOrFail();

            if (
                $lockedGuru->waliKelas()->exists()
                || $lockedGuru->catatanPelanggaran()->exists()
                || LogPelanggaran::query()->where('user_id', $user->id)->exists()
                || LogAktivitas::query()->where('user_id', $user->id)->exists()
            ) {
                throw ValidationException::withMessages([
                    'guru' => 'Guru memiliki riwayat penugasan, catatan, atau aktivitas sehingga tidak dapat dihapus permanen. Nonaktifkan Guru untuk mempertahankan riwayat.',
                ]);
            }

            $lockedGuru->delete();
            $user->delete();
        });
        $activityLogger->record($request->user(), $request, 'Guru dihapus', 'Identitas Guru ID #' . $guru->id . ' tanpa histori berhasil dihapus.');

        return to_route('admin.teachers.index')
            ->with('success', 'Data Guru dan akun login tanpa riwayat berhasil dihapus permanen.');
    }

    /**
     * Menonaktifkan akun guru beserta status aktif yang terkait.
     */
    public function deactivate(Guru $guru, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('changeStatus', $guru);

        DB::transaction(function () use ($guru): void {
            Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail()->update(['status_aktif' => false]);
        });
        $activityLogger->record($request->user(), $request, 'Guru dinonaktifkan', 'Guru ID #' . $guru->id . ' berhasil dinonaktifkan.');

        return to_route('admin.teachers.show', $guru)
            ->with('success', 'Guru dinonaktifkan. Akun login, penugasan, dan seluruh histori tetap tersimpan.');
    }

    /**
     * Memperbarui kredensial akun yang terhubung dengan data guru.
     */
    public function updateAccount(UpdateGuruAccountRequest $request, Guru $guru, ActivityLogger $activityLogger): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($guru, $data): void {
            $lockedGuru = Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail();
            if ($lockedGuru->user_id === null) {
                throw ValidationException::withMessages([
                    'password_login' => 'Identitas Guru tidak memiliki akun login yang valid.',
                ]);
            }

            $user = User::query()->whereKey($lockedGuru->user_id)->lockForUpdate()->firstOrFail();

            if ($user->role !== 'guru') {
                throw ValidationException::withMessages([
                    'password_login' => 'Akun terhubung tidak memiliki role Guru. Relasi akun tidak diubah.',
                ]);
            }

            $user->email = $lockedGuru->email;

            if (! empty($data['password_login'])) {
                $user->password = $data['password_login'];
            }

            $user->nama = $lockedGuru->nama;
            $user->save();
        });
        $activityLogger->record($request->user(), $request, 'Akun Guru diperbarui', 'Pengaturan akun Guru ID #' . $guru->id . ' diperbarui tanpa mencatat data kredensial.');

        return to_route('admin.teachers.show', $guru)->with('success', 'Pengaturan akun login berhasil diperbarui.');
    }

    /**
     * Mengaktifkan kembali akun guru melalui pemeriksaan otorisasi.
     */
    public function activate(Guru $guru, Request $request, ActivityLogger $activityLogger): RedirectResponse
    {
        Gate::authorize('changeStatus', $guru);

        DB::transaction(function () use ($guru): void {
            Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail()->update(['status_aktif' => true]);
        });
        $activityLogger->record($request->user(), $request, 'Guru diaktifkan', 'Guru ID #' . $guru->id . ' berhasil diaktifkan.');

        return to_route('admin.teachers.show', $guru)->with('success', 'Guru dan akun login yang terhubung berhasil diaktifkan.');
    }
}
