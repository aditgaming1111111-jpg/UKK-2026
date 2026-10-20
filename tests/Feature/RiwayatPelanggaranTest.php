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
use Tests\TestCase;

class RiwayatPelanggaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_search_and_filter_records_by_all_history_fields(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.student-violations.index', [
            'q' => $context['student']->nisn,
            'tahun_ajaran_id' => $context['year']->id,
            'kelas_id' => $context['class']->id,
            'pelanggaran_id' => $context['violation']->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'guru_id' => $context['teacher']->id,
            'status' => 'aktif',
        ]));

        $response->assertOk()
            ->assertViewHas('records', fn ($records): bool => $records->total() === 1 && $records->getCollection()->first()->is($record))
            ->assertSee($context['student']->nama)
            ->assertSee($context['student']->nisn)
            ->assertSee($context['class']->nama_kelas)
            ->assertSee($context['year']->nama_tahun_ajaran);
    }

    public function test_violation_history_paginates_ten_records_without_duplicates(): void
    {
        $context = $this->recordingContext();
        $records = collect(range(1, 12))->map(fn (): PelanggaranSiswa => $this->createRecord($context));
        $administrator = User::factory()->create(['role' => 'admin']);

        $firstPage = $this->actingAs($administrator)->get(route('admin.student-violations.index'));
        $firstPage->assertViewHas('records', fn ($page): bool => $page->count() === 10 && $page->total() === 12)
            ->assertSeeText('');
        $firstPageIds = $firstPage->viewData('records')->getCollection()->modelKeys();

        $secondPage = $this->get(route('admin.student-violations.index', ['page' => 2]));
        $secondPage->assertViewHas('records', fn ($page): bool => $page->count() === 2 && $page->total() === 12);
        $secondPageIds = $secondPage->viewData('records')->getCollection()->modelKeys();

        $this->assertSame([], array_values(array_intersect($firstPageIds, $secondPageIds)));
        $this->assertCount(12, $records);
    }

    public function test_audit_log_directory_paginates_ten_records_and_preserves_search(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context);
        $administrator = User::factory()->create(['role' => 'admin', 'nama' => 'Admin Pagination']);
        LogPelanggaran::factory()->count(12)
            ->for($record, 'catatanPelanggaran')
            ->for($administrator, 'pengguna')
            ->create();
        $filters = ['q' => 'Admin Pagination'];

        $firstPage = $this->actingAs($administrator)->get(route('admin.audit-logs.index', $filters));
        $firstPage->assertViewHas('logs', fn ($logs): bool => $logs->count() === 10 && $logs->total() === 12)
            ->assertSeeText('');

        $this->get(route('admin.audit-logs.index', [...$filters, 'page' => 2]))
            ->assertViewHas('logs', fn ($logs): bool => $logs->count() === 2 && $logs->total() === 12)
            ->assertSee('q=Admin%20Pagination');
    }

    public function test_all_teachers_can_view_full_history_and_other_authors_records_without_homeroom_assignment(): void
    {
        $context = $this->recordingContext();
        $ownRecord = $this->createRecord($context);
        $otherContext = $this->recordingContext();
        $recordByOtherTeacher = $this->createRecord($context, $otherContext['teacher']);
        $recordByFirstTeacherInOtherClass = $this->createRecord($otherContext, $context['teacher']);
        $unrelatedContext = $this->recordingContext();
        $unrelatedRecord = $this->createRecord($unrelatedContext);
        $this->assertDatabaseCount('t_wali_kelas', 0);

        $this->actingAs($context['user'])->get(route('guru.violations.index'))
            ->assertOk()
            ->assertViewHas('records', fn ($records): bool => $records->total() === 4
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($ownRecord))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($recordByOtherTeacher))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($recordByFirstTeacherInOtherClass))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($unrelatedRecord)));

        $this->get(route('guru.violations.show', $recordByOtherTeacher))->assertOk();
        $this->get(route('guru.violations.show', $unrelatedRecord))->assertOk();
        $this->put(route('guru.violations.update', $recordByOtherTeacher), $this->updatePayload($recordByOtherTeacher))
            ->assertForbidden();

        $this->actingAs($otherContext['user'])->get(route('guru.violations.index'))
            ->assertViewHas('records', fn ($records): bool => $records->total() === 4
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($recordByOtherTeacher))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($recordByFirstTeacherInOtherClass))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($unrelatedRecord)));
        $this->get(route('guru.violations.show', $recordByFirstTeacherInOtherClass))->assertOk();
    }

    public function test_teacher_history_search_filters_and_pagination_include_other_teachers_and_cancelled_notes(): void
    {
        $context = $this->recordingContext();
        $otherContext = $this->recordingContext();
        $records = collect(range(1, 12))->map(fn (): PelanggaranSiswa => $this->createRecord($context, $otherContext['teacher']));
        $cancelledRecord = $this->createRecord($context, $otherContext['teacher'], status: 'dibatalkan');
        $filters = [
            'q' => $context['student']->nisn,
            'tahun_ajaran_id' => $context['year']->id,
            'kelas_id' => $context['class']->id,
            'guru_id' => $otherContext['teacher']->id,
            'status' => 'aktif',
        ];

        $firstPage = $this->actingAs($context['user'])->get(route('guru.violations.index', $filters));
        $firstPage->assertOk()
            ->assertViewHas('records', fn ($page): bool => $page->count() === 10 && $page->total() === 12)
            ->assertSee($otherContext['teacher']->nama);

        $this->get(route('guru.violations.index', [...$filters, 'page' => 2]))
            ->assertOk()
            ->assertViewHas('records', fn ($page): bool => $page->count() === 2 && $page->total() === 12)
            ->assertSee('q='.$context['student']->nisn)
            ->assertSee('tahun_ajaran_id='.$context['year']->id)
            ->assertSee('kelas_id='.$context['class']->id)
            ->assertSee('guru_id='.$otherContext['teacher']->id)
            ->assertViewHas('records', fn ($page): bool => ! $page->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($cancelledRecord)));

        $this->get(route('guru.violations.index', [...$filters, 'status' => 'dibatalkan']))
            ->assertViewHas('records', fn ($page): bool => $page->total() === 1
                && $page->getCollection()->first()->is($cancelledRecord))
            ->assertSee('Dibatalkan');
        $this->assertCount(12, $records);
    }

    public function test_new_teacher_without_homeroom_assignment_can_view_all_older_history(): void
    {
        $authorContext = $this->recordingContext();
        $permittedRecord = $this->createRecord($authorContext);
        $permittedRecord->update(['tanggal_pelanggaran' => now()->subDays(5)->toDateString()]);
        $outsidePeriodRecord = $this->createRecord($authorContext);
        $outsidePeriodRecord->update(['tanggal_pelanggaran' => now()->subDays(20)->toDateString()]);

        $newTeacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($newTeacherUser, 'user')->create();
        $this->assertDatabaseCount('t_wali_kelas', 0);

        $this->actingAs($newTeacherUser)->get(route('guru.violations.index'))
            ->assertOk()
            ->assertViewHas('records', fn ($records): bool => $records->total() === 2
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($permittedRecord))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($outsidePeriodRecord)))
            ->assertSee($permittedRecord->penempatanKelas->siswa->nama);
    }

    public function test_teacher_can_correct_own_record_and_audit_before_after_data_and_reason(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context);
        $oldDescription = $record->keterangan;
        $reason = 'Tanggal diverifikasi dari laporan guru.';

        $this->actingAs($context['user'])->put(route('guru.violations.update', $record), $this->updatePayload($record, [
            'tanggal_pelanggaran' => now()->subDay()->toDateString(),
            'keterangan' => 'Keterangan hasil koreksi.',
            'alasan' => $reason,
            'guru_id' => 999999,
            'poin_saat_dicatat' => 999,
            'status' => 'dibatalkan',
            'kelas_siswa_id' => 999999,
        ]))->assertRedirect(route('guru.violations.show', $record));

        $record->refresh();
        $this->assertSame('Keterangan hasil koreksi.', $record->keterangan);
        $this->assertSame(now()->subDay()->toDateString(), $record->tanggal_pelanggaran->toDateString());
        $this->assertSame($context['teacher']->id, $record->guru_id);
        $this->assertSame(7, $record->poin_saat_dicatat);
        $this->assertSame('aktif', $record->status);
        $log = LogPelanggaran::query()->where('aksi', 'edit')->sole();
        $this->assertSame($context['user']->id, $log->user_id);
        $this->assertSame($reason, $log->alasan);
        $this->assertSame($oldDescription, json_decode($log->data_sebelum, true, flags: JSON_THROW_ON_ERROR)['keterangan']);
        $this->assertSame('Keterangan hasil koreksi.', json_decode($log->data_sesudah, true, flags: JSON_THROW_ON_ERROR)['keterangan']);
    }

    public function test_admin_can_correct_another_teachers_record_and_missing_reason_is_rejected(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context);
        $admin = User::factory()->create(['role' => 'admin']);
        $payload = $this->updatePayload($record);
        unset($payload['alasan']);

        $this->actingAs($admin)->from(route('admin.student-violations.edit', $record))
            ->put(route('admin.student-violations.update', $record), $payload)
            ->assertRedirect(route('admin.student-violations.edit', $record))
            ->assertSessionHasErrors('alasan');
        $this->assertSame(0, LogPelanggaran::query()->count());
        $this->assertSame('Catatan awal', $record->fresh()->keterangan);

        $this->put(route('admin.student-violations.update', $record), $this->updatePayload($record, [
            'keterangan' => 'Dikoreksi Admin.',
            'alasan' => 'Koreksi berdasarkan dokumen sekolah.',
        ]))->assertRedirect(route('admin.student-violations.show', $record));

        $this->assertSame('Dikoreksi Admin.', $record->fresh()->keterangan);
        $this->assertDatabaseHas('t_log_pelanggaran', [
            'pelanggaran_siswa_id' => $record->id,
            'user_id' => $admin->id,
            'aksi' => 'edit',
            'alasan' => 'Koreksi berdasarkan dokumen sekolah.',
        ]);
    }

    public function test_correction_rejects_inactive_replacement_type_without_changing_record_or_log(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context);
        $inactiveCategory = PelanggaranKategori::factory()->create(['status_aktif' => false]);
        $inactiveViolation = Pelanggaran::factory()->for($inactiveCategory, 'kategori')->create(['status_aktif' => false]);

        $this->actingAs($context['user'])->from(route('guru.violations.edit', $record))
            ->put(route('guru.violations.update', $record), $this->updatePayload($record, [
                'pelanggaran_id' => $inactiveViolation->id,
                'keterangan' => 'Perubahan yang harus ditolak.',
            ]))
            ->assertRedirect(route('guru.violations.edit', $record))
            ->assertSessionHasErrors('pelanggaran_id');

        $this->assertSame($context['violation']->id, $record->fresh()->pelanggaran_id);
        $this->assertSame('Catatan awal', $record->fresh()->keterangan);
        $this->assertSame(0, LogPelanggaran::query()->count());
    }

    public function test_cancellation_keeps_record_and_log_and_excludes_points_from_admin_totals(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context, status: 'aktif', points: 7);
        $activeRecord = $this->createRecord($context, points: 3);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch(route('admin.student-violations.cancel', $record), [
            'alasan' => 'Catatan ganda setelah pemeriksaan.',
        ])->assertRedirect(route('admin.student-violations.show', $record));

        $this->assertModelExists($record);
        $this->assertSame('dibatalkan', $record->fresh()->status);
        $this->assertSame('aktif', $activeRecord->fresh()->status);
        $cancelLog = LogPelanggaran::query()->where('aksi', 'batal')->sole();
        $this->assertSame($admin->id, $cancelLog->user_id);
        $this->assertSame('Catatan ganda setelah pemeriksaan.', $cancelLog->alasan);
        $this->assertSame('aktif', json_decode($cancelLog->data_sebelum, true, flags: JSON_THROW_ON_ERROR)['status']);
        $this->assertSame('dibatalkan', json_decode($cancelLog->data_sesudah, true, flags: JSON_THROW_ON_ERROR)['status']);

        $this->get(route('admin.dashboard', ['tahun_ajaran_id' => $context['year']->id]))
            ->assertViewHas('stats', fn (array $stats): bool => $stats['active_violations'] === 1 && $stats['active_points'] === 3);
        $this->get(route('admin.student-violations.index', ['status' => 'dibatalkan']))
            ->assertViewHas('records', fn ($records): bool => $records->total() === 1);

        $this->put(route('admin.student-violations.update', $record), $this->updatePayload($record, [
            'keterangan' => 'Keterangan dikoreksi sesudah pembatalan.',
            'alasan' => 'Memperjelas arsip yang dibatalkan.',
        ]))->assertRedirect(route('admin.student-violations.show', $record));
        $this->assertSame('dibatalkan', $record->fresh()->status);
        $this->get(route('admin.dashboard', ['tahun_ajaran_id' => $context['year']->id]))
            ->assertViewHas('stats', fn (array $stats): bool => $stats['active_violations'] === 1 && $stats['active_points'] === 3);
    }

    public function test_teacher_can_cancel_own_record_but_cannot_change_another_users_record(): void
    {
        $context = $this->recordingContext();
        $ownRecord = $this->createRecord($context);
        $otherContext = $this->recordingContext();
        $otherRecord = $this->createRecord($otherContext);

        $this->actingAs($context['user'])->patch(route('guru.violations.cancel', $otherRecord), [
            'alasan' => 'Tidak memiliki akses.',
        ])->assertForbidden();

        $this->patch(route('guru.violations.cancel', $ownRecord), ['alasan' => 'Salah pilih siswa saat input.'])
            ->assertRedirect(route('guru.violations.show', $ownRecord));
        $this->assertSame('dibatalkan', $ownRecord->fresh()->status);
        $this->assertSame(1, LogPelanggaran::query()->where('aksi', 'batal')->count());
    }

    public function test_history_and_audit_routes_enforce_roles_and_audit_view_is_read_only(): void
    {
        $context = $this->recordingContext();
        $record = $this->createRecord($context);
        LogPelanggaran::factory()->for($record, 'catatanPelanggaran')->for($context['user'], 'pengguna')->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->get(route('admin.student-violations.index'))->assertRedirect(route('login'));
        $this->actingAs($context['user'])->get(route('admin.student-violations.index'))->assertForbidden();
        $this->actingAs($context['user'])->get(route('admin.audit-logs.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertSee($context['student']->nama)
            ->assertDontSee('Hapus log');
        $this->assertSame(0, LogPelanggaran::query()->where('aksi', 'hapus')->count());
        $this->assertModelExists($record);
    }

    /** @return array{user: User, teacher: Guru, student: Siswa, class: Kelas, year: TahunAjaran, placement: KelasSiswa, category: PelanggaranKategori, violation: Pelanggaran} */
    private function recordingContext(): array
    {
        $user = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($user, 'user')->create();
        $student = Siswa::factory()->create();
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => now()->format('Y').'/'.now()->addYear()->format('Y'),
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => now()->subMonth()->toDateString(), 'tanggal_selesai' => null]);
        $category = PelanggaranKategori::factory()->create(['status_aktif' => true]);
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create(['poin' => 7, 'status_aktif' => true]);

        return compact('user', 'teacher', 'student', 'class', 'year', 'placement', 'category', 'violation');
    }

    private function createRecord(array $context, ?Guru $teacher = null, string $status = 'aktif', int $points = 7): PelanggaranSiswa
    {
        return PelanggaranSiswa::factory()
            ->for($context['placement'], 'penempatanKelas')
            ->for($context['violation'], 'pelanggaran')
            ->for($teacher ?? $context['teacher'], 'guru')
            ->create([
                'tanggal_pelanggaran' => today()->toDateString(),
                'status' => $status,
                'poin_saat_dicatat' => $points,
                'keterangan' => 'Catatan awal',
            ]);
    }

    /** @return array{pelanggaran_id: int, tanggal_pelanggaran: string, keterangan: string, tindakan: ?string, alasan: string} */
    private function updatePayload(PelanggaranSiswa $record, array $overrides = []): array
    {
        return array_merge([
            'pelanggaran_id' => $record->pelanggaran_id,
            'tanggal_pelanggaran' => $record->tanggal_pelanggaran->toDateString(),
            'keterangan' => $record->keterangan,
            'tindakan' => $record->tindakan,
            'alasan' => 'Data diverifikasi ulang.',
        ], $overrides);
    }
}
