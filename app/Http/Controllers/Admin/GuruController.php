<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuruIndexRequest;
use App\Http\Requests\Admin\StoreGuruRequest;
use App\Http\Requests\Admin\UpdateGuruAccountRequest;
use App\Http\Requests\Admin\UpdateGuruRequest;
use App\Models\Guru;
use App\Models\LogPelanggaran;
use App\Models\User;
use App\Services\GuruDirectory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GuruIndexRequest $request, GuruDirectory $guruDirectory): View
    {
        return view('admin.guru.index', [
            'teachers' => $guruDirectory->paginate($request->validated()),
            'filters' => $request->validated(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Guru::class);

        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuruRequest $request): RedirectResponse
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
                'nama' => $data['nama'],
                'email' => Str::lower(trim($data['email'])),
                'status_aktif' => true,
            ]);
        });

        return to_route('admin.teachers.show', $guru)->with('success', 'Data Guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru): View
    {
        Gate::authorize('update', $guru);

        return view('admin.guru.edit', ['teacher' => $guru]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuruRequest $request, Guru $guru): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($guru, $data): void {
            $lockedGuru = Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail();
            $lockedGuru->update([
                'nip' => $data['nip'],
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

        return to_route('admin.teachers.show', $guru)->with('success', 'Identitas Guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        Gate::authorize('delete', $guru);

        DB::transaction(function () use ($guru): void {
            $lockedGuru = Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail();
            $user = User::query()->whereKey($lockedGuru->user_id)->lockForUpdate()->firstOrFail();

            if ($lockedGuru->waliKelas()->exists()
                || $lockedGuru->catatanPelanggaran()->exists()
                || LogPelanggaran::query()->where('user_id', $user->id)->exists()) {
                throw ValidationException::withMessages([
                    'guru' => 'Guru memiliki riwayat penugasan, catatan, atau aktivitas audit sehingga tidak dapat dihapus permanen. Nonaktifkan Guru untuk mempertahankan riwayat.',
                ]);
            }

            $lockedGuru->delete();
            $user->delete();
        });

        return to_route('admin.teachers.index')
            ->with('success', 'Data Guru dan akun login tanpa riwayat berhasil dihapus permanen.');
    }

    public function deactivate(Guru $guru): RedirectResponse
    {
        Gate::authorize('changeStatus', $guru);

        DB::transaction(function () use ($guru): void {
            Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail()->update(['status_aktif' => false]);
        });

        return to_route('admin.teachers.show', $guru)
            ->with('success', 'Guru dinonaktifkan. Akun login, penugasan, dan seluruh histori tetap tersimpan.');
    }

    public function updateAccount(UpdateGuruAccountRequest $request, Guru $guru): RedirectResponse
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

        return to_route('admin.teachers.show', $guru)->with('success', 'Pengaturan akun login berhasil diperbarui.');
    }

    public function activate(Guru $guru): RedirectResponse
    {
        Gate::authorize('changeStatus', $guru);

        DB::transaction(function () use ($guru): void {
            Guru::query()->whereKey($guru->id)->lockForUpdate()->firstOrFail()->update(['status_aktif' => true]);
        });

        return to_route('admin.teachers.show', $guru)->with('success', 'Guru dan akun login yang terhubung berhasil diaktifkan.');
    }
}
