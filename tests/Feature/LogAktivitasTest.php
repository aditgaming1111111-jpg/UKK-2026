<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogAktivitasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_login_logout_and_student_crud_are_logged_once_without_sensitive_input(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->post(route('login.store'), [
            'email' => $administrator->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseCount('t_log_aktivitas', 1);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Login',
        ]);

        $this->post(route('admin.students.store'), [
            'nis' => '0123456789',
            'nisn' => '9876543210',
            'nama' => 'Siswa Pengujian',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-02',
            'tahun_masuk' => 2026,
        ])->assertRedirect();

        $student = Siswa::query()->where('nisn', '9876543210')->firstOrFail();
        $this->put(route('admin.students.update', $student), [
            'nis' => '0123456789',
            'nisn' => '9876543210',
            'nama' => 'Siswa Pengujian Diperbarui',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-02',
            'tahun_masuk' => 2026,
        ])->assertRedirect();
        $this->patch(route('admin.students.status', $student), ['status_siswa' => 'nonaktif'])->assertRedirect();

        $this->assertDatabaseCount('t_log_aktivitas', 4);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Siswa ditambahkan',
            'deskripsi' => 'Identitas siswa ID #'.$student->id.' berhasil ditambahkan.',
        ]);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Siswa diperbarui',
        ]);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Status siswa diubah',
        ]);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseCount('t_log_aktivitas', 5);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Logout',
        ]);

        $this->assertDatabaseMissing('t_log_aktivitas', ['deskripsi' => 'password']);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'ip_address' => '127.0.0.1',
        ]);
    }

    public function test_guru_login_and_logout_are_logged_with_guru_user_id(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($guru)->create();

        $this->post(route('login.store'), [
            'email' => $guru->email,
            'password' => 'password',
        ])->assertRedirect(route('guru.dashboard'));
        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->assertDatabaseCount('t_log_aktivitas', 2);
        $this->assertSame(2, LogAktivitas::query()->where('user_id', $guru->id)->count());
        $this->assertDatabaseHas('t_log_aktivitas', ['user_id' => $guru->id, 'aktivitas' => 'Login']);
        $this->assertDatabaseHas('t_log_aktivitas', ['user_id' => $guru->id, 'aktivitas' => 'Logout']);
    }

    public function test_failed_login_and_failed_student_validation_do_not_create_activity_logs(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->post(route('login.store'), [
            'email' => $administrator->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->actingAs($administrator)->post(route('admin.students.store'), [
            'nis' => '123',
            'nisn' => '9876543210',
            'nama' => 'Siswa Tidak Valid',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertSessionHasErrors('nis');

        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_only_admin_can_view_search_and_paginate_activity_logs(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($guru)->create();

        foreach (range(0, 10) as $index) {
            LogAktivitas::query()->create([
                'user_id' => $administrator->id,
                'aktivitas' => $index === 0 ? 'Pencatatan khusus' : 'Perubahan data',
                'deskripsi' => 'Aktivitas tes '.$index,
                'ip_address' => '127.0.0.1',
            ]);
        }

        $this->get(route('admin.audit-logs.index'))->assertRedirect(route('login'));
        $this->actingAs($guru)->get(route('admin.audit-logs.index'))->assertForbidden();

        $this->actingAs($administrator)->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertViewHas('logs', fn ($logs): bool => $logs->count() === 10 && $logs->total() === 11)
            ->assertSee('Pengguna')
            ->assertSee('Role')
            ->assertSee('Alamat IP');

        $this->get(route('admin.audit-logs.index', ['q' => 'Pencatatan khusus']))
            ->assertOk()
            ->assertSee('Pencatatan khusus')
            ->assertViewHas('logs', fn ($logs): bool => $logs->count() === 1 && $logs->total() === 1);

        $this->get(route('admin.audit-logs.index', ['q' => 'Pencatatan khusus', 'page' => 2]))
            ->assertOk()
            ->assertViewHas('logs', fn ($logs): bool => $logs->count() === 0 && $logs->total() === 1);
    }
}
