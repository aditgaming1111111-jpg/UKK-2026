<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahunAjaranManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_activate_an_academic_year_without_removing_previous_year(): void
    {
        $this->actingAs(User::factory()->state(['role' => 'admin'])->create());
        $previous = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'status_aktif' => true,
        ]);
        $this->get(route('admin.academic-years.index'))->assertOk();
        $this->get(route('admin.academic-years.create'))->assertOk();
        $this->get(route('admin.academic-years.show', $previous))->assertOk();
        $this->get(route('admin.academic-years.edit', $previous))->assertOk();

        $this->post(route('admin.academic-years.store'), [
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('t_tahun_ajaran', ['id' => $previous->id, 'status_aktif' => false]);
        $this->assertDatabaseHas('t_tahun_ajaran', ['nama_tahun_ajaran' => '2026/2027', 'status_aktif' => true]);
        $this->assertDatabaseCount('t_tahun_ajaran', 2);
    }

    public function test_overlapping_academic_period_is_rejected_and_existing_history_remains(): void
    {
        $this->actingAs(User::factory()->state(['role' => 'admin'])->create());
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);

        $this->post(route('admin.academic-years.store'), [
            'nama_tahun_ajaran' => '2027/2028',
            'tanggal_mulai' => '2027-06-30',
            'tanggal_selesai' => '2028-06-30',
            'status_aktif' => '1',
        ])->assertSessionHasErrors('tanggal_mulai');

        $this->assertDatabaseHas('t_tahun_ajaran', ['id' => $year->id]);
        $this->assertDatabaseCount('t_tahun_ajaran', 1);
    }

    public function test_academic_year_directory_paginates_ten_records(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        TahunAjaran::factory()->count(11)->sequence(fn ($sequence): array => [
            'nama_tahun_ajaran' => (2020 + $sequence->index).'/'.(2021 + $sequence->index),
            'tanggal_mulai' => (2020 + $sequence->index).'-07-01',
            'tanggal_selesai' => (2021 + $sequence->index).'-06-30',
            'status_aktif' => false,
        ])->create();

        $this->actingAs($administrator)->get(route('admin.academic-years.index'))
            ->assertViewHas('academicYears', fn ($years): bool => $years->count() === 10 && $years->total() === 11)
            ->assertSeeText('');

        $this->get(route('admin.academic-years.index', ['page' => 2]))
            ->assertViewHas('academicYears', fn ($years): bool => $years->count() === 1 && $years->total() === 11);
    }

    public function test_admin_can_delete_an_academic_year_without_history(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $year = TahunAjaran::factory()->create();

        $this->actingAs($administrator)->delete(route('admin.academic-years.destroy', $year))
            ->assertRedirect(route('admin.academic-years.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($year);
    }

    public function test_academic_year_with_placement_or_assignment_history_cannot_be_deleted(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $year = TahunAjaran::factory()->create();
        $class = Kelas::factory()->create();
        $student = Siswa::factory()->create();
        KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();

        $this->actingAs($administrator)->delete(route('admin.academic-years.destroy', $year))
            ->assertSessionHasErrors('tahun_ajaran');

        $this->assertModelExists($year);
        $this->assertDatabaseHas('t_kelas_siswa', ['tahun_ajaran_id' => $year->id]);

        $yearWithoutPlacement = TahunAjaran::factory()->create();
        WaliKelas::factory()->for(Guru::factory(), 'guru')->for($class, 'kelas')->for($yearWithoutPlacement, 'tahunAjaran')->create();
        $this->delete(route('admin.academic-years.destroy', $yearWithoutPlacement))
            ->assertSessionHasErrors('tahun_ajaran');
        $this->assertModelExists($yearWithoutPlacement);
    }

    public function test_unchanged_academic_year_update_shows_success_notification(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);

        $this->actingAs($administrator)
            ->put(route('admin.academic-years.update', $year), [
                'nama_tahun_ajaran' => $year->nama_tahun_ajaran,
                'tanggal_mulai' => $year->tanggal_mulai->toDateString(),
                'tanggal_selesai' => $year->tanggal_selesai->toDateString(),
                'status_aktif' => '1',
            ])
            ->assertRedirect(route('admin.academic-years.show', $year))
            ->assertSessionHas('success', 'Pengaturan tahun ajaran berhasil diperbarui.');

        $this->actingAs($administrator)->get(route('admin.academic-years.show', $year))
            ->assertOk()
            ->assertSee('Pengaturan tahun ajaran berhasil diperbarui.')
            ->assertSee('opacity-100');
        $this->assertDatabaseHas('t_tahun_ajaran', [
            'id' => $year->id,
            'nama_tahun_ajaran' => '2026/2027',
            'status_aktif' => true,
        ]);
    }

    public function test_teacher_cannot_access_academic_year_management(): void
    {
        $teacher = Guru::factory()->create();
        $this->actingAs($teacher->user);

        $this->get(route('admin.academic-years.index'))->assertForbidden();
    }
}
