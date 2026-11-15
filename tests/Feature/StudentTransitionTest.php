<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\StudentTransitionBatch;
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
            ->assertSeeText('Memenuhi syarat')
            ->assertSeeText('Jenis proses:')
            ->assertSeeText('Tahun asal:')
            ->assertSeeText('Jumlah siswa yang terdampak: 1')
            ->assertSee('data-modal-open="confirm-student-transition"', false)
            ->assertSeeText('Penempatan lama tetap disimpan dan aktivitas dicatat.')
            ->assertSeeText('Penempatan asal diakhiri dan penempatan baru dibuat');
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
        $batch = StudentTransitionBatch::query()->sole();
        $this->assertSame(StudentTransitionBatch::STATUS_COMPLETED, $batch->status);
        $this->assertSame(1, $batch->jumlah_diproses);
        $this->assertSame($admin->id, $batch->user_id);
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

        $this->get(route('admin.academic-years.student-transitions', [
            'tahunAjaran' => $originYear,
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'mode' => 'naik',
        ]))
            ->assertSee('data-modal-open="undo-transition-'.$batch->id.'"', false)
            ->assertSee('Status dan penempatan asal akan dipulihkan.');
    }

    public function test_admin_can_promote_grade_eleven_to_twelve_using_target_year_start_date(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('XI RPL 2', '11');
        $targetClass = $this->schoolClass('XII RPL 2', '12');
        $student = $this->student();
        $sourcePlacement = $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ])->assertRedirect()->assertSessionHas('success');

        $targetPlacement = KelasSiswa::query()
            ->where('siswa_id', $student->id)
            ->where('tahun_ajaran_id', $targetYear->id)
            ->firstOrFail();
        $this->assertSame($targetClass->id, $targetPlacement->kelas_id);
        $this->assertSame($targetYear->tanggal_mulai->toDateString(), $targetPlacement->tanggal_mulai->toDateString());
        $this->assertTrue($targetPlacement->status_aktif);
        $sourcePlacement->refresh();
        $this->assertSame($originYear->tanggal_selesai->toDateString(), $sourcePlacement->tanggal_selesai->toDateString());
        $this->assertFalse($sourcePlacement->status_aktif);
    }

    public function test_promotion_mapping_shows_only_eligible_grade_ten_and_eleven_classes(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $gradeTen = $this->schoolClass('X RPL 1', '10');
        $gradeEleven = $this->schoolClass('XI RPL 2', '11');
        $gradeTwelve = $this->schoolClass('XII TKJ 5', '12');
        $this->schoolClass('XI RPL 1', '11');
        $this->schoolClass('XII RPL 1', '12');
        $this->place($this->student(), $gradeTen, $originYear);
        $this->place($this->student(), $gradeEleven, $originYear);
        $this->place($this->student(), $gradeTwelve, $originYear);

        $this->actingAs($admin)->get(route('admin.academic-years.student-transitions', [
            'tahunAjaran' => $originYear,
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
        ]))->assertOk()
            ->assertSeeText('X RPL 1')
            ->assertSeeText('XI RPL 2')
            ->assertSee('data-transition-map="'.$gradeTen->id.'"', false)
            ->assertSee('data-transition-map="'.$gradeEleven->id.'"', false)
            ->assertDontSee('data-transition-map="'.$gradeTwelve->id.'"', false)
            ->assertSeeText('XII RPL 1')
            ->assertSeeText('Siswa kelas XII harus diproses melalui kelulusan atau tinggal kelas.');
    }

    public function test_promotion_preview_explains_when_origin_year_only_has_grade_twelve(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $gradeTwelve = $this->schoolClass('XII RPL 1', '12');
        $student = $this->student();
        $this->place($student, $gradeTwelve, $originYear);

        $this->actingAs($admin)->get(route('admin.academic-years.student-transitions', [
            'tahunAjaran' => $originYear,
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
        ]))->assertOk()
            ->assertSeeText('Tidak ada kelas yang dapat dinaikkan')
            ->assertSeeText('Tahun ajaran asal ini tidak memiliki siswa aktif kelas X atau XI yang memenuhi syarat.')
            ->assertSeeText('Siswa kelas XII diproses melalui kelulusan massal.')
            ->assertDontSee('data-transition-map="'.$gradeTwelve->id.'"', false);
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
        $this->assertSame(StudentTransitionBatch::STATUS_COMPLETED, StudentTransitionBatch::query()->sole()->status);
    }

    public function test_admin_can_undo_promotion_and_restore_original_placement_without_deleting_audit(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $student = $this->student();
        $sourcePlacement = $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ])->assertRedirect()->assertSessionHas('success');

        $batch = StudentTransitionBatch::query()->sole();
        $targetPlacementId = (int) $batch->data_snapshot[0]['penempatan_tujuan']['id'];
        $snapshot = $batch->data_snapshot;
        $snapshot[0]['penempatan_asal_sebelum'] = array_reverse($snapshot[0]['penempatan_asal_sebelum']);
        $snapshot[0]['penempatan_asal_sesudah'] = array_reverse($snapshot[0]['penempatan_asal_sesudah']);
        $snapshot[0]['penempatan_tujuan'] = array_reverse($snapshot[0]['penempatan_tujuan']);
        $batch->update(['data_snapshot' => $snapshot]);

        $this->patch(route('admin.academic-years.student-transitions.undo', [
            'tahunAjaran' => $originYear,
            'batchId' => $batch->batch_id,
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
        ]))->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'aktif']);
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $sourcePlacement->id,
            'kelas_id' => $sourceClass->id,
            'tahun_ajaran_id' => $originYear->id,
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $this->assertDatabaseMissing('t_kelas_siswa', ['id' => $targetPlacementId]);
        $this->assertSame(StudentTransitionBatch::STATUS_REVERTED, $batch->refresh()->status);
        $this->assertSame($admin->id, $batch->dibatalkan_oleh_user_id);
        $this->assertNotNull($batch->dibatalkan_pada);
        $this->assertDatabaseHas('t_log_aktivitas', ['user_id' => $admin->id, 'aktivitas' => 'Kenaikan kelas massal']);
        $this->assertDatabaseHas('t_log_aktivitas', ['user_id' => $admin->id, 'aktivitas' => 'Pembatalan kenaikan kelas massal']);
        $this->assertDatabaseCount('t_log_aktivitas', 2);

        $this->patch(route('admin.academic-years.student-transitions.undo', [
            'tahunAjaran' => $originYear,
            'batchId' => $batch->batch_id,
        ]))->assertSessionHasErrors('batch');
        $this->assertDatabaseCount('t_log_aktivitas', 2);
    }

    public function test_admin_can_undo_graduation_and_restore_active_status_and_placement(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear] = $this->years();
        $sourceClass = $this->schoolClass('XII RPL 1', 'XII');
        $student = $this->student();
        $sourcePlacement = $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'lulus',
        ])->assertRedirect()->assertSessionHas('success');

        $batch = StudentTransitionBatch::query()->sole();
        $this->patch(route('admin.academic-years.student-transitions.undo', [
            'tahunAjaran' => $originYear,
            'batchId' => $batch->batch_id,
            'mode' => 'lulus',
        ]))->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'aktif']);
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $sourcePlacement->id,
            'kelas_id' => $sourceClass->id,
            'tahun_ajaran_id' => $originYear->id,
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $this->assertSame(StudentTransitionBatch::STATUS_REVERTED, $batch->refresh()->status);
        $this->assertDatabaseHas('t_log_aktivitas', ['user_id' => $admin->id, 'aktivitas' => 'Kelulusan massal']);
        $this->assertDatabaseHas('t_log_aktivitas', ['user_id' => $admin->id, 'aktivitas' => 'Pembatalan kelulusan massal']);
        $this->assertDatabaseCount('t_kelas_siswa', 1);
    }

    public function test_undo_is_blocked_when_student_or_placement_changed_after_batch(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $correctedTargetClass = $this->schoolClass('XI RPL 2', 'XI');
        $student = $this->student();
        $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ])->assertRedirect();

        $batch = StudentTransitionBatch::query()->sole();
        $targetPlacement = KelasSiswa::query()->where('siswa_id', $student->id)->where('tahun_ajaran_id', $targetYear->id)->firstOrFail();
        $targetPlacement->update(['kelas_id' => $correctedTargetClass->id]);

        $this->patch(route('admin.academic-years.student-transitions.undo', [
            'tahunAjaran' => $originYear,
            'batchId' => $batch->batch_id,
        ]))->assertSessionHasErrors('batch');

        $this->assertSame(StudentTransitionBatch::STATUS_COMPLETED, $batch->refresh()->status);
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'aktif']);
        $this->assertDatabaseHas('t_kelas_siswa', ['id' => $targetPlacement->id, 'kelas_id' => $correctedTargetClass->id]);
        $this->assertDatabaseCount('t_log_aktivitas', 1);
    }

    public function test_teacher_cannot_undo_student_transition(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $teacher = Guru::factory()->create();
        [$originYear, $targetYear] = $this->years();
        $sourceClass = $this->schoolClass('X RPL 1', 'X');
        $targetClass = $this->schoolClass('XI RPL 1', 'XI');
        $student = $this->student();
        $this->place($student, $sourceClass, $originYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'naik',
            'tahun_ajaran_tujuan_id' => $targetYear->id,
            'pemetaan' => [$sourceClass->id => $targetClass->id],
        ])->assertRedirect();

        $batch = StudentTransitionBatch::query()->sole();
        $this->actingAs($teacher->user)->patch(route('admin.academic-years.student-transitions.undo', [
            'tahunAjaran' => $originYear,
            'batchId' => $batch->batch_id,
        ]))->assertForbidden();

        $this->assertSame(StudentTransitionBatch::STATUS_COMPLETED, $batch->refresh()->status);
    }

    public function test_graduation_is_rejected_if_student_has_another_active_placement(): void
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        [$originYear, $targetYear] = $this->years();
        $gradeTwelveClass = $this->schoolClass('XII RPL 1', 'XII');
        $otherClass = $this->schoolClass('XII RPL 2', 'XII');
        $student = $this->student();
        $this->place($student, $gradeTwelveClass, $originYear);
        $this->place($student, $otherClass, $targetYear);

        $this->actingAs($admin)->post(route('admin.academic-years.student-transitions.store', $originYear), [
            'mode' => 'lulus',
        ])->assertSessionHasErrors('siswa');

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'aktif']);
        $this->assertDatabaseCount('t_proses_transisi_siswa', 0);
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
