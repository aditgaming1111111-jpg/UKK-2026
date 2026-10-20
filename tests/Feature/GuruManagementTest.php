<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\LogPelanggaran;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GuruManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_create_guru_with_a_linked_login_account(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($administrator)->post(route('admin.teachers.store'), [
            'nip' => '198700123',
            'nama' => 'Rina Puspita',
            'email' => 'RINA.LOGIN@example.test',
            'password_login' => 'rahasia-guru-2026',
            'password_login_confirmation' => 'rahasia-guru-2026',
            'role' => 'admin',
        ]);

        $teacher = Guru::query()->where('nip', '198700123')->firstOrFail();
        $user = User::query()->where('email', 'rina.login@example.test')->firstOrFail();
        $response->assertRedirect(route('admin.teachers.show', $teacher));
        $this->assertDatabaseHas('t_guru', ['id' => $teacher->id, 'user_id' => $user->id, 'status_aktif' => true]);
        $this->assertDatabaseHas('t_users', ['id' => $user->id, 'role' => 'guru', 'nama' => 'Rina Puspita']);
        $this->assertTrue(Hash::check('rahasia-guru-2026', $user->password));
        $this->assertTrue($teacher->user->is($user));
        $this->assertTrue($user->guru->is($teacher));
        $this->assertSame($teacher->email, $user->email);
    }

    public function test_admin_needs_no_teacher_record_and_teacher_creation_requires_a_linked_login(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->actingAs($administrator)->from(route('admin.teachers.create'))->post(route('admin.teachers.store'), [
            'nip' => '198700124',
            'nama' => 'Guru Tanpa Login',
            'email' => 'contact@example.test',
        ])->assertSessionHasErrors(['password_login']);

        $this->assertModelExists($administrator);
        $this->assertNull($administrator->guru);
        $this->assertTrue($administrator->isActiveAccount());
        $this->assertDatabaseMissing('t_guru', ['nip' => '198700124']);
    }

    public function test_duplicate_email_is_rejected_without_creating_a_teacher(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'same.login@example.test']);

        $this->actingAs($administrator)->from(route('admin.teachers.create'))
            ->post(route('admin.teachers.store'), [
                'nip' => '198700125',
                'nama' => 'Guru Email Duplikat',
                'email' => 'SAME.LOGIN@example.test',
                'password_login' => 'rahasia-guru-2026',
                'password_login_confirmation' => 'rahasia-guru-2026',
            ])
            ->assertSessionHasErrors(['email' => 'Email sudah digunakan pada data Guru atau akun pengguna.']);

        $this->assertDatabaseMissing('t_guru', ['nip' => '198700125']);
    }

    public function test_account_without_a_valid_teacher_relation_cannot_log_in(): void
    {
        $orphanTeacherAccount = User::factory()->create(['role' => 'guru']);

        $this->from(route('login'))->post(route('login.store'), [
            'email' => $orphanTeacherAccount->email,
            'password' => 'password',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertNull($orphanTeacherAccount->fresh()->guru);
    }

    public function test_admin_can_update_teacher_email_and_keep_identity_and_login_in_sync(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacher = Guru::factory()->for(User::factory()->state([
            'role' => 'guru',
            'email' => 'initial.login@example.test',
        ]), 'user')->create(['nama' => 'Nama Sebelum', 'email' => 'identity@example.test']);
        $user = $teacher->user;

        $this->actingAs($administrator)->put(route('admin.teachers.update', $teacher), [
            'nip' => $teacher->nip,
            'nama' => 'Nama Sesudah',
            'email' => 'UPDATED.LOGIN@example.test',
        ])->assertRedirect(route('admin.teachers.show', $teacher));

        $this->actingAs($administrator)->put(route('admin.teachers.account.update', $teacher), [
            'email_login' => 'ignored@example.test',
            'password_login' => '',
            'password_login_confirmation' => '',
        ])->assertRedirect(route('admin.teachers.show', $teacher));

        $teacher->refresh();
        $user->refresh();
        $this->assertDatabaseHas('t_guru', [
            'id' => $teacher->id,
            'nama' => 'Nama Sesudah',
            'email' => 'updated.login@example.test',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('t_users', [
            'id' => $user->id,
            'nama' => 'Nama Sesudah',
            'email' => 'updated.login@example.test',
            'role' => 'guru',
        ]);
        $this->assertTrue($teacher->user->is($user));
        $this->assertTrue($user->guru->is($teacher));
    }

    public function test_teacher_email_cannot_be_changed_to_another_users_email(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacher = Guru::factory()->create();
        User::factory()->create(['email' => 'already.used@example.test']);

        $this->actingAs($administrator)->from(route('admin.teachers.show', $teacher))
            ->put(route('admin.teachers.update', $teacher), [
                'nip' => $teacher->nip,
                'nama' => $teacher->nama,
                'email' => 'already.used@example.test',
            ])->assertSessionHasErrors(['email' => 'Email sudah digunakan pada data Guru atau akun pengguna lain.']);

        $this->assertDatabaseHas('t_users', ['id' => $teacher->user_id, 'email' => $teacher->user->email]);
    }

    public function test_deactivated_teacher_keeps_login_relation_assignments_and_violation_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacher = Guru::factory()->create();
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create();
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();
        $student = Siswa::factory()->create();
        $placement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $note = PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')
            ->for($teacher, 'guru')->create();
        $log = LogPelanggaran::factory()->for($note, 'catatanPelanggaran')->for($administrator, 'pengguna')->create();
        $userId = $teacher->user_id;

        $this->actingAs($administrator)->delete(route('admin.teachers.destroy', $teacher))
            ->assertRedirect(route('admin.teachers.show', $teacher));

        $this->assertDatabaseHas('t_guru', ['id' => $teacher->id, 'status_aktif' => false, 'user_id' => $userId]);
        $this->assertModelExists($teacher->user()->firstOrFail());
        $this->assertModelExists($note);
        $this->assertModelExists($log);

        $this->post(route('logout'));
        $this->post(route('login.store'), [
            'email' => $teacher->user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_list_search_filter_and_paginate_teachers(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        Guru::factory()->create(['nama' => 'Guru Dicari', 'nip' => 'nip-unik-1']);
        Guru::factory()->create(['status_aktif' => false, 'nama' => 'Guru Nonaktif']);
        Guru::factory()->count(15)->create();

        $this->actingAs($administrator)->get(route('admin.teachers.index', [
            'q' => 'Guru Dicari',
            'status' => 'aktif',
        ]))->assertSee('Guru Dicari')->assertDontSee('Guru Nonaktif');

        $this->actingAs($administrator)->get(route('admin.teachers.index'))
            ->assertSee('page=2');
    }

    public function test_teacher_directory_limits_pages_to_ten_and_keeps_search_filters(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        Guru::factory()->count(12)->sequence(fn ($sequence): array => [
            'nama' => 'Guru Filter '.str_pad((string) $sequence->index, 2, '0', STR_PAD_LEFT),
            'status_aktif' => true,
        ])->create();
        Guru::factory()->create(['nama' => 'Guru Nonaktif Filter', 'status_aktif' => false]);
        $filters = ['q' => 'Guru Filter', 'status' => 'aktif'];

        $this->actingAs($administrator)->get(route('admin.teachers.index', $filters))
            ->assertViewHas('teachers', fn ($teachers): bool => $teachers->count() === 10 && $teachers->total() === 12)
            ->assertSeeText('');

        $this->get(route('admin.teachers.index', [...$filters, 'page' => 2]))
            ->assertViewHas('teachers', fn ($teachers): bool => $teachers->count() === 2 && $teachers->total() === 12)
            ->assertSee('Guru Filter 10')
            ->assertSee('Guru Filter 11')
            ->assertDontSee('Guru Filter 00')
            ->assertSee('q=Guru%20Filter')
            ->assertSee('status=aktif');
    }

    public function test_only_admin_can_manage_teacher_records(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($guruUser, 'user')->create();
        $teacher = Guru::factory()->create();

        $this->get(route('admin.teachers.index'))->assertRedirect(route('login'));
        $this->actingAs($guruUser)->get(route('admin.teachers.index'))->assertForbidden();
        $this->actingAs($guruUser)->get(route('admin.teachers.show', $teacher))->assertForbidden();
        $this->actingAs($guruUser)->post(route('admin.teachers.store'), [])->assertForbidden();
        $this->actingAs($guruUser)->put(route('admin.teachers.account.update', $teacher), [])->assertForbidden();
    }
}
