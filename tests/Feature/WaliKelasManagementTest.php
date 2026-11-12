<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\LogAktivitas;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji penetapan wali kelas, koreksi dan riwayat penugasan, konflik periode, serta akses rekap kelas.
 */
class WaliKelasManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_replace_active_assignment_and_previous_record_is_preserved(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $this->actingAs($administrator);
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
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Wali kelas ditugaskan',
        ]);
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

    public function test_assignment_status_filters_are_qualified_and_preserved_with_other_filters_and_pagination(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $class = Kelas::factory()->create(['nama_kelas' => 'XI RPL 2']);
        $otherClass = Kelas::factory()->create(['nama_kelas' => 'X RPL 1']);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'status_aktif' => true,
        ]);
        $inactiveYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2025/2026',
            'status_aktif' => false,
        ]);

        $activeAssignment = WaliKelas::factory()->for($class, 'kelas')->for($inactiveYear, 'tahunAjaran')->create([
            'status_aktif' => true,
            'tanggal_selesai' => null,
        ]);
        $completedByDate = WaliKelas::factory()->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'status_aktif' => true,
            'tanggal_selesai' => '2026-10-01',
        ]);
        $completedByStatus = WaliKelas::factory()->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'status_aktif' => false,
            'tanggal_selesai' => null,
        ]);
        WaliKelas::factory()->for($otherClass, 'kelas')->for($inactiveYear, 'tahunAjaran')->create([
            'status_aktif' => false,
            'tanggal_selesai' => '2026-06-30',
        ]);
        WaliKelas::factory()->count(11)->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'status_aktif' => false,
            'tanggal_selesai' => '2026-10-02',
        ]);

        $this->actingAs($administrator)->get(route('admin.homeroom-teachers.index'))
            ->assertOk()
            ->assertViewHas('assignments', fn ($assignments): bool => $assignments->total() === 15);

        $this->get(route('admin.homeroom-teachers.index', ['status' => 'aktif']))
            ->assertOk()
            ->assertViewHas('assignments', fn ($assignments): bool => $assignments->total() === 1
                && $assignments->getCollection()->first()->is($activeAssignment));

        $filters = [
            'status' => 'selesai',
            'tahun_ajaran_id' => $year->id,
            'kelas_id' => $class->id,
        ];
        $firstPage = $this->get(route('admin.homeroom-teachers.index', $filters));
        $firstPage->assertOk()
            ->assertViewHas('assignments', fn ($assignments): bool => $assignments->count() === 10
                && $assignments->total() === 13)
            ->assertSee('status=selesai')
            ->assertSee('tahun_ajaran_id='.$year->id)
            ->assertSee('kelas_id='.$class->id);

        $this->get(route('admin.homeroom-teachers.index', [...$filters, 'page' => 2]))
            ->assertOk()
            ->assertViewHas('assignments', fn ($assignments): bool => $assignments->count() === 3
                && $assignments->total() === 13
                && $assignments->getCollection()->contains(fn (WaliKelas $assignment): bool => $assignment->is($completedByDate))
                && $assignments->getCollection()->contains(fn (WaliKelas $assignment): bool => $assignment->is($completedByStatus)));
    }

    public function test_only_assigned_homeroom_teacher_can_open_its_class_recap(): void
    {
        $ordinaryTeacher = Guru::factory()->create();
        $this->actingAs($ordinaryTeacher->user)
            ->get(route('guru.class-recap.index'))
            ->assertForbidden();

        $homeroomTeacher = Guru::factory()->create();
        $assignedClass = Kelas::factory()->create(['nama_kelas' => 'XI RPL Wali']);
        $otherClass = Kelas::factory()->create(['nama_kelas' => 'XI RPL Lain']);
        $assignedStudent = Siswa::factory()->create(['nama' => 'Siswa Dalam Kelas Wali']);
        $otherStudent = Siswa::factory()->create(['nama' => 'Siswa Kelas Lain']);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => 'Tahun Berjalan',
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => today()->addMonth()->toDateString(),
            'status_aktif' => true,
        ]);
        WaliKelas::factory()->for($homeroomTeacher, 'guru')->for($assignedClass, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        KelasSiswa::factory()->for($assignedStudent, 'siswa')->for($assignedClass, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        KelasSiswa::factory()->for($otherStudent, 'siswa')->for($otherClass, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);

        $this->actingAs($homeroomTeacher->user)
            ->get(route('guru.class-recap.index', ['tahun_ajaran_id' => $year->id, 'kelas_id' => $assignedClass->id]))
            ->assertOk()
            ->assertViewHas('selectedClass', fn (Kelas $kelas): bool => $kelas->is($assignedClass))
            ->assertDontSee('Data Siswa')
            ->assertSee('Rekap Poin');

        $this->get(route('guru.class-recap.index', ['tahun_ajaran_id' => $year->id, 'kelas_id' => $otherClass->id]))
            ->assertForbidden();

        $this->get(route('guru.class-recap.show', [$year->id, $assignedClass->id, $assignedStudent->id]))
            ->assertOk()
            ->assertSee('Siswa Dalam Kelas Wali');
        $this->get(route('guru.class-recap.show', [$year->id, $assignedClass->id, $otherStudent->id]))
            ->assertNotFound();
    }

    public function test_admin_can_correct_active_assignment_dates_and_audit_the_change_without_losing_recap_data(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacher = Guru::factory()->create([
            'nama' => 'Aldi Maulana, S.Pd.',
        ]);
        $class = Kelas::factory()->create([
            'nama_kelas' => 'XII RPL 2',
            'tingkat' => 'XII',
            'jurusan' => 'RPL',
        ]);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-13 00:00:00',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);
        $assignment = WaliKelas::factory()->for($teacher, 'guru')->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2027-06-18',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-07-13 00:00:00',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'tanggal_pelanggaran' => '2026-08-10',
            'poin_saat_dicatat' => 5,
            'status' => 'aktif',
        ]);

        $this->actingAs($administrator)
            ->patch(route('admin.homeroom-teachers.update', $assignment), [
                'tanggal_mulai' => '2026-07-13',
                'tanggal_selesai' => null,
            ])
            ->assertRedirect(route('admin.homeroom-teachers.show', $assignment))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('t_wali_kelas', [
            'id' => $assignment->id,
            'tanggal_mulai' => '2026-07-13 00:00:00',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Tanggal penugasan wali kelas dikoreksi',
        ]);
        $activityDescription = LogAktivitas::query()
            ->where('aktivitas', 'Tanggal penugasan wali kelas dikoreksi')
            ->value('deskripsi');
        $this->assertStringContainsString('2027-06-18', $activityDescription);
        $this->assertStringContainsString('2026-07-13', $activityDescription);

        $this->get(route('admin.homeroom-teachers.show', $assignment))
            ->assertOk()
            ->assertSee('value="2026-07-13"', false)
            ->assertSee('Belum diakhiri');

        $this->actingAs($teacher->user)->get(route('guru.class-recap.index', [
            'tahun_ajaran_id' => $year->id,
            'kelas_id' => $class->id,
        ]))
            ->assertOk()
            ->assertSee($placement->siswa->nama)
            ->assertViewHas('summary', fn (array $summary): bool => $summary['active_records'] === 1 && $summary['active_points'] === 5);
    }

    public function test_assignment_date_correction_rejects_conflicts_for_class_and_teacher(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacher = Guru::factory()->create();
        $otherTeacher = Guru::factory()->create();
        $class = Kelas::factory()->create();
        $otherClass = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-13',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $assignment = WaliKelas::factory()->for($teacher, 'guru')->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-07-13',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $classConflict = WaliKelas::factory()->for($otherTeacher, 'guru')->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'status_aktif' => false,
        ]);
        $teacherConflict = WaliKelas::factory()->for($teacher, 'guru')->for($otherClass, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'status_aktif' => false,
        ]);

        $this->actingAs($administrator)
            ->patch(route('admin.homeroom-teachers.update', $assignment), [
                'tanggal_mulai' => '2026-07-13',
                'tanggal_selesai' => '2026-09-15',
            ])
            ->assertSessionHasErrors('tanggal_mulai');

        $this->assertDatabaseHas('t_wali_kelas', [
            'id' => $assignment->id,
            'tanggal_mulai' => '2026-07-13 00:00:00',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);

        $classConflict->delete();

        $this->patch(route('admin.homeroom-teachers.update', $assignment), [
            'tanggal_mulai' => '2026-07-13',
            'tanggal_selesai' => '2026-09-15',
        ])->assertSessionHasErrors('tanggal_selesai');

        $this->assertDatabaseHas('t_wali_kelas', [
            'id' => $assignment->id,
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $this->assertDatabaseHas('t_wali_kelas', ['id' => $teacherConflict->id]);
    }

    public function test_assignment_date_correction_validates_year_period_order_and_admin_access(): void
    {
        $teacher = Guru::factory()->create();
        $teacherUser = $teacher->user;
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-13',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $assignment = WaliKelas::factory()->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-07-13',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);

        $this->actingAs($teacherUser)
            ->patch(route('admin.homeroom-teachers.update', $assignment), [
                'tanggal_mulai' => '2026-07-13',
                'tanggal_selesai' => null,
            ])
            ->assertForbidden();

        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator)
            ->patch(route('admin.homeroom-teachers.update', $assignment), [
                'tanggal_mulai' => '2026-06-30',
                'tanggal_selesai' => null,
            ])
            ->assertSessionHasErrors('tanggal_mulai');

        $this->patch(route('admin.homeroom-teachers.update', $assignment), [
            'tanggal_mulai' => '2026-07-13',
            'tanggal_selesai' => '2026-07-12',
        ])->assertSessionHasErrors('tanggal_selesai');

        $this->assertDatabaseHas('t_wali_kelas', [
            'id' => $assignment->id,
            'tanggal_mulai' => '2026-07-13 00:00:00',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $this->assertDatabaseMissing('t_log_aktivitas', [
            'aktivitas' => 'Tanggal penugasan wali kelas dikoreksi',
        ]);
    }
}
