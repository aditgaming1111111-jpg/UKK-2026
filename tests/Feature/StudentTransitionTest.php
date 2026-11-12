<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji pratinjau dan pemrosesan kenaikan siswa, pengecualian, kelulusan, pembatalan transaksi, dan akses.
 */
class StudentTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_preview_and_see_class_mapping_and_students(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $this->schoolClass('XI RPL 1', 'XI');
        $this->place($this->student(), $sourceClass, $originYear);

        $this->actingAs($admin)
            ->get(route('admin.academic-years.student-transitions', [
                'tahunAjaran' => $originYear,
                'tahun_ajaran_tujuan_id' => $targetYear->id,
                'mode' => 'naik',
            ]))
            ->assertOk()
            ->assertSeeText('X RPL 1')
            ->assertSeeText('XI RPL 1')
            ->assertSeeText('Memenuhi syarat');
    }

    public function test_admin_can_promote_students_using_mapping_and_preserves_old_placement(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 2', 'XI');
        $student = $this->student();
        $oldPlacement = $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)
            ->post(route('admin.academic-years.student-transitions.store', $originYear), [
                'mode' => 'naik',
                'tahun_ajaran_tujuan_id' => $targetYear->id,
                'pemetaan' => [$sourceClass->id => $targetClass->id],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $oldPlacement->refresh();
        $this->assertSame($sourceClass->id, $oldPlacement->kelas_id);
        $this->assertSame($originYear->id, $oldPlacement->tahun_ajaran_id);
        $this->assertSame($originYear->tanggal_selesai->toDateString(), $oldPlacement->tanggal_selesai->toDateString());
        $this->assertFalse($oldPlacement->status_aktif);
        $newPlacement = KelasSiswa::query()
            ->where('siswa_id', $student->id)
            ->where('tahun_ajaran_id', $targetYear->id)
            ->firstOrFail();
        $this->assertSame($targetClass->id, $newPlacement->kelas_id);
        $this->assertSame($targetYear->tanggal_mulai->toDateString(), $newPlacement->tanggal_mulai->toDateString());
        $this->assertTrue($newPlacement->status_aktif);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $admin->id,
            'aktivitas' => 'Kenaikan kelas massal',
        ]);
        $description = (string) \DB::table('t_log_aktivitas')
            ->where('user_id', $admin->id)
            ->where('aktivitas', 'Kenaikan kelas massal')
            ->value('deskripsi');
        $this->assertStringContainsString($student->nisn, $description);
        $this->assertStringContainsString($sourceClass->nama_kelas, $description);
        $this->assertStringContainsString($targetClass->nama_kelas, $description);
        $this->assertStringContainsString($originYear->nama_tahun_ajaran, $description);
        $this->assertStringContainsString($targetYear->nama_tahun_ajaran, $description);
    }

    public function test_excluded_student_is_not_promoted_and_other_students_are_processed(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $excludedStudent = $this->student();
        $includedStudent = $this->student();
        $this->place($excludedStudent, $sourceClass, $originYear);
        $this->place($includedStudent, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
            'dikecualikan' => [$excludedStudent->id],
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseMissing('t_kelas_siswa', [
            'siswa_id' => $excludedStudent->id,
            'tahun_ajaran_id' => $targetYear->id,
        ]);
        $this->assertDatabaseHas('t_kelas_siswa', [
            'siswa_id' => $includedStudent->id,
            'tahun_ajaran_id' => $targetYear->id,
        ]);
    }

    public function test_student_can_be_processed_as_repeating_the_same_grade(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('XI RPL 1', 'XI');
        $targetClass = $this->schoolClass('XI RPL 2', 'XI');
        $student = $this->student();
        $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'tinggal',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('t_kelas_siswa', [
            'siswa_id' => $student->id,
            'kelas_id' => $targetClass->id,
            'tahun_ajaran_id' => $targetYear->id,
        ]);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $admin->id,
            'aktivitas' => 'Proses tinggal kelas massal',
        ]);
    }

    public function test_grade_twelve_students_can_graduate_without_a_new_placement(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear] = $this->years();
        $sourceClass = $this->schoolClass('XII RPL 1', 'XII');
        $student = $this->student();
        $oldPlacement = $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'lulus',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'lulus']);
        $oldPlacement->refresh();
        $this->assertSame($originYear->tanggal_selesai->toDateString(), $oldPlacement->tanggal_selesai->toDateString());
        $this->assertFalse($oldPlacement->status_aktif);
        $this->assertSame(1, KelasSiswa::query()->where('siswa_id', $student->id)->count());
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $admin->id,
            'aktivitas' => 'Kelulusan massal',
        ]);
    }

    public function test_non_active_students_are_not_processed_automatically(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $student = $this->student(['status_siswa' => 'pindah']);
        $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)
            ->get(route('admin.academic-years.student-transitions', [
                'tahunAjaran' => $originYear,
                'mode' => 'naik',
                'tahun_ajaran_tujuan_id' => $targetYear->id,
            ]))
            ->assertOk()
            ->assertSeeText('Siswa berstatus pindah.');

        $this->assertDatabaseMissing('t_kelas_siswa', ['siswa_id' => $student->id, 'tahun_ajaran_id' => $targetYear->id]);
    }

    public function test_invalid_target_year_and_wrong_grade_mapping_are_rejected(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $wrongTargetClass = $this->schoolClass('XII RPL 1', 'XII');
        $student = $this->student();
        $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)
            ->post(route('admin.academic-years.student-transitions.store', $originYear), [
                'mode' => 'naik',
                'tahun_ajaran_tujuan_id' => $originYear->id,
                'pemetaan' => [$sourceClass->id => $wrongTargetClass->id],
            ])
            ->assertSessionHasErrors('tahun_ajaran_tujuan_id');

        $this->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $wrongTargetClass->id],
        ])->assertSessionHasErrors('pemetaan.'.$sourceClass->id);

        $this->assertDatabaseCount('t_kelas_siswa', 1);
    }

    public function test_same_batch_cannot_be_run_twice_for_the_same_student(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $student = $this->student();
        $this->place($student, $sourceClass, $originYear);
        $payload = [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ];

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), $payload)
            ->assertRedirect()->assertSessionHas('success');
        $this->post(route('admin.academic-years.student-transitions.store', $originYear), $payload)
            ->assertSessionHasErrors('siswa');

        $this->assertDatabaseCount('t_kelas_siswa', 2);
        $this->assertDatabaseCount('t_log_aktivitas', 1);
    }

    public function test_failure_rolls_back_placements_and_activity_logs(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $firstStudent = $this->student();
        $secondStudent = $this->student();
        $firstPlacement = $this->place($firstStudent, $sourceClass, $originYear);
        $secondPlacement = $this->place($secondStudent, $sourceClass, $originYear);
        $teacher = Guru::factory()->create();
        PelanggaranSiswa::factory()->for($secondPlacement, 'penempatanKelas')->for($teacher, 'guru')->create([
            'tanggal_pelanggaran' => '2026-07-01',
        ]);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ])->assertSessionHasErrors('siswa');

        $this->assertDatabaseHas('t_kelas_siswa', ['id' => $firstPlacement->id, 'tanggal_selesai' => null, 'status_aktif' => true]);
        $this->assertDatabaseHas('t_kelas_siswa', ['id' => $secondPlacement->id, 'tanggal_selesai' => null, 'status_aktif' => true]);
        $this->assertDatabaseMissing('t_kelas_siswa', ['siswa_id' => $firstStudent->id, 'tahun_ajaran_id' => $targetYear->id]);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_teacher_cannot_view_or_execute_student_transition(): void
    {
        $teacher = Guru::factory()->create();
        [$originYear, $targetYear] = $this->years();

        $this->actingAs($teacher->user)
            ->get(route('admin.academic-years.student-transitions', $originYear))
            ->assertForbidden();
        $this->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [],
        ])->assertForbidden();
    }

    public function test_transition_history_is_visible_on_origin_year_page(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('XII RPL 1', 'XII');
        $student = $this->student();
        $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'lulus',
        ])->assertRedirect();

        $this->get(route('admin.academic-years.student-transitions', [
            'tahunAjaran' => $originYear,
            'mode' => 'lulus',
        ]))->assertOk()->assertSeeText('Kelulusan massal')->assertSeeText($student->nisn);
    }

    /** @return array{0: TahunAjaran, 1: TahunAjaran} */
    private function years(): array
    {
        $originYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'status_aktif' => false,
        ]);
        $targetYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);

        return [$originYear, $targetYear];
    }

    private function schoolClass(string $name, string $level): Kelas
    {
        return Kelas::factory()->create([
            'nama_kelas' => $name,
            'tingkat' => $level,
        ]);
    }

    /** @param array<string, mixed> $attributes */
    private function student(array $attributes = []): Siswa
    {
        return Siswa::factory()->create($attributes);
    }

    private function place(Siswa $student, Kelas $kelas, TahunAjaran $year): KelasSiswa
    {
        return KelasSiswa::factory()->for($student, 'siswa')->for($kelas, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => $year->tanggal_mulai->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
    }
}
