<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaliKelasManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_replace_active_assignment_and_previous_record_is_preserved(): void
    {
        $this->actingAs(User::factory()->state(['role' => 'admin'])->create());
        $class = Kelas::factory()->create();
        $firstYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
        ]);
        $nextYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => false,
        ]);
        $previous = WaliKelas::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $firstYear->id,
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $nextTeacher = Guru::factory()->create();
        $inactiveTeacher = Guru::factory()->create(['nama' => 'Guru Nonaktif', 'status_aktif' => false]);
        $this->get(route('admin.homeroom-teachers.index'))->assertOk();
        $this->get(route('admin.homeroom-teachers.create'))
            ->assertOk()
            ->assertSee($nextTeacher->nama)
            ->assertDontSee($inactiveTeacher->nama);
        $this->get(route('admin.homeroom-teachers.show', $previous))->assertOk();

        $this->post(route('admin.homeroom-teachers.store'), [
            'guru_id' => $nextTeacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $nextYear->id,
            'tanggal_mulai' => '2026-07-01',
        ])->assertRedirect();

        $this->assertDatabaseHas('t_wali_kelas', ['id' => $previous->id, 'tanggal_selesai' => '2026-06-30 00:00:00', 'status_aktif' => false]);
        $this->assertDatabaseHas('t_wali_kelas', ['guru_id' => $nextTeacher->id, 'kelas_id' => $class->id, 'status_aktif' => true]);
        $this->assertDatabaseCount('t_wali_kelas', 2);
    }

    public function test_overlapping_assignment_is_rejected_without_changing_previous_record(): void
    {
        $this->actingAs(User::factory()->state(['role' => 'admin'])->create());
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $previous = WaliKelas::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-08-15',
            'status_aktif' => false,
        ]);
        $newTeacher = Guru::factory()->create();

        $this->post(route('admin.homeroom-teachers.store'), [
            'guru_id' => $newTeacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-08-01',
        ])->assertSessionHasErrors('kelas_id');

        $this->assertDatabaseHas('t_wali_kelas', ['id' => $previous->id, 'tanggal_selesai' => '2026-08-15 00:00:00', 'status_aktif' => false]);
        $this->assertDatabaseCount('t_wali_kelas', 1);
    }

    public function test_inactive_teacher_cannot_be_assigned_and_non_admin_cannot_view_assignments(): void
    {
        $inactiveTeacher = Guru::factory()->create(['status_aktif' => false]);
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create();
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $this->actingAs($admin)->post(route('admin.homeroom-teachers.store'), [
            'guru_id' => $inactiveTeacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => $year->tanggal_mulai->toDateString(),
        ])->assertSessionHasErrors('guru_id');

        $teacher = Guru::factory()->create();
        $this->actingAs($teacher->user)->get(route('admin.homeroom-teachers.index'))->assertForbidden();
    }

    public function test_homeroom_assignment_history_paginates_ten_records(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        WaliKelas::factory()->count(11)->create();

        $this->actingAs($administrator)->get(route('admin.homeroom-teachers.index'))
            ->assertViewHas('assignments', fn ($assignments): bool => $assignments->count() === 10 && $assignments->total() === 11)
            ->assertSeeText('');

        $this->get(route('admin.homeroom-teachers.index', ['page' => 2]))
            ->assertViewHas('assignments', fn ($assignments): bool => $assignments->count() === 1 && $assignments->total() === 11);
    }
}
