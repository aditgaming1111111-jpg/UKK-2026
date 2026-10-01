<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\User;
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

    public function test_teacher_cannot_access_academic_year_management(): void
    {
        $teacher = Guru::factory()->create();
        $this->actingAs($teacher->user);

        $this->get(route('admin.academic-years.index'))->assertForbidden();
    }
}
