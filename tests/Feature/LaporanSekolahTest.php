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
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class LaporanSekolahTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_report_filters_database_and_excludes_cancelled_points(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$year, $class, $placement, $teacher, $violation] = $this->recordContext();
        $student = $placement->siswa;

        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => now()->toDateString(),
            'poin_saat_dicatat' => 7,
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => now()->toDateString(),
            'poin_saat_dicatat' => 99,
            'status' => 'dibatalkan',
        ]);

        $this->actingAs($admin)->get(route('admin.reports.index', [
            'tahun_ajaran_id' => $year->id,
            'kelas_id' => $class->id,
            'q' => $student->nisn,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'pelanggaran_id' => $violation->id,
        ]))
            ->assertOk()
            ->assertSee($student->nama)
            ->assertSee('Dibatalkan')
            ->assertSeeInOrder(['Catatan aktif', '>1<', 'Total poin aktif', '>7<', 'Catatan dibatalkan', '>1<']);
    }

    public function test_admin_can_download_valid_pdf_for_filtered_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$year, , $placement, $teacher, $violation] = $this->recordContext();
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'poin_saat_dicatat' => 8,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.pdf', ['tahun_ajaran_id' => $year->id]));

        $response->assertOk();
        $pdfContent = $response->getContent();
        $this->assertIsString($pdfContent);
        $this->assertStringStartsWith('%PDF-', $pdfContent);
        $this->assertStringEndsWith('%%EOF', trim($pdfContent));
        $this->assertGreaterThan(500, strlen($pdfContent));
    }

    public function test_admin_print_report_includes_all_filtered_records_across_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$year, , $placement, $teacher, $violation] = $this->recordContext();
        $student = $placement->siswa;
        PelanggaranSiswa::factory()->count(12)->sequence(fn ($sequence): array => [
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => now()->subDays($sequence->index)->toDateString(),
            'keterangan' => 'Catatan laporan '.$sequence->index,
        ])->create();

        $this->actingAs($admin)->get(route('admin.reports.print', [
            'tahun_ajaran_id' => $year->id,
            'q' => $student->nisn,
        ]))->assertOk()
            ->assertViewHas('records', fn ($records): bool => $records->count() === 12)
            ->assertSee('08/10/2026')
            ->assertSee('19/10/2026');
    }

    public function test_guru_cannot_access_admin_reports(): void
    {
        $teacher = User::factory()->create(['role' => 'guru']);
        Guru::factory()->create(['user_id' => $teacher->id]);

        $this->actingAs($teacher)->get(route('admin.reports.index'))->assertForbidden();
        $this->actingAs($teacher)->get(route('admin.reports.pdf'))->assertForbidden();
    }

    public function test_wali_kelas_recap_only_includes_assigned_class_and_active_points(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => now()->subMonths(4)->toDateString(),
            'tanggal_selesai' => now()->addMonths(4)->toDateString(),
            'status_aktif' => true,
        ]);
        $assignedClass = Kelas::factory()->create(['nama_kelas' => 'Assigned Class']);
        $otherClass = Kelas::factory()->create(['nama_kelas' => 'Outside Class']);
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $assignedClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => $year->tanggal_mulai,
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $assignedPlacement = KelasSiswa::factory()->create([
            'kelas_id' => $assignedClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => $year->tanggal_mulai,
        ]);
        $otherPlacement = KelasSiswa::factory()->create([
            'kelas_id' => $otherClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => $year->tanggal_mulai,
        ]);
        $violation = Pelanggaran::factory()->create();
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $assignedPlacement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'poin_saat_dicatat' => 6,
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $assignedPlacement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'poin_saat_dicatat' => 4,
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $assignedPlacement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'poin_saat_dicatat' => 70,
            'status' => 'dibatalkan',
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $otherPlacement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'poin_saat_dicatat' => 90,
        ]);

        DB::connection()->enableQueryLog();

        $response = $this->actingAs($teacherUser)->get(route('guru.class-recap.index', ['tahun_ajaran_id' => $year->id]))
            ->assertOk()
            ->assertSee($assignedPlacement->siswa->nama)
            ->assertDontSee($otherClass->nama_kelas)
            ->assertSee('Catatan aktif')
            ->assertSee('10');

        $response->assertViewHas('summary', static fn (array $summary): bool => $summary['student_count'] === 1
            && $summary['students_with_active_records'] === 1
            && $summary['active_records'] === 2
            && $summary['active_points'] === 10);
        $response->assertViewHas('students', static fn ($students): bool => $students->total() === 1
            && $students->first()->active_records === 2
            && $students->first()->active_points === 10);

        $this->assertAggregationQueriesAreStrictModeSafe();
    }

    public function test_teacher_without_assignment_cannot_access_class_recap(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->create(['user_id' => $teacherUser->id]);

        $this->actingAs($teacherUser)->get(route('guru.class-recap.index'))->assertForbidden();
    }

    public function test_recap_keeps_historical_year_and_closed_homeroom_assignment_available(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2023/2024',
            'tanggal_mulai' => '2023-07-01',
            'tanggal_selesai' => '2024-06-30',
            'status_aktif' => false,
        ]);
        $class = Kelas::factory()->create(['nama_kelas' => 'Historical Class']);
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2023-07-01',
            'tanggal_selesai' => '2024-02-01',
            'status_aktif' => false,
        ]);
        $placement = KelasSiswa::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2023-07-01',
            'tanggal_selesai' => '2024-06-30',
            'status_aktif' => false,
        ]);
        $violation = Pelanggaran::factory()->create();
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2023-11-15',
            'poin_saat_dicatat' => 11,
        ]);

        $this->actingAs($teacherUser)->get(route('guru.class-recap.index', ['tahun_ajaran_id' => $year->id]))
            ->assertOk()
            ->assertSee('2023/2024')
            ->assertSee($placement->siswa->nama)
            ->assertSee('11');
    }

    public function test_recap_lists_students_without_active_points_and_filters_events_by_date(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);
        $class = Kelas::factory()->create();
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
            'status_aktif' => true,
        ]);
        $withIncident = KelasSiswa::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $withoutIncident = KelasSiswa::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $violation = Pelanggaran::factory()->create();
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $withIncident->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-12',
            'poin_saat_dicatat' => 9,
        ]);

        $this->actingAs($teacherUser)->get(route('guru.class-recap.index', [
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
        ]))
            ->assertOk()
            ->assertSee($withIncident->siswa->nama)
            ->assertSee($withoutIncident->siswa->nama)
            ->assertSee('Jumlah siswa')
            ->assertSee('Catatan aktif')
            ->assertSee('>0<', false);
    }

    public function test_homeroom_student_detail_is_scoped_and_shows_cancelled_audit_without_counting_it(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);
        $class = Kelas::factory()->create();
        $otherClass = Kelas::factory()->create();
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $otherPlacement = KelasSiswa::factory()->create([
            'kelas_id' => $otherClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $violation = Pelanggaran::factory()->create();
        $activeRecord = PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-12',
            'poin_saat_dicatat' => 5,
        ]);
        $cancelledRecord = PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-13',
            'poin_saat_dicatat' => 40,
            'status' => 'dibatalkan',
        ]);
        LogPelanggaran::factory()->create([
            'pelanggaran_siswa_id' => $cancelledRecord->id,
            'user_id' => $teacherUser->id,
            'aksi' => 'batal',
            'alasan' => 'Catatan salah siswa',
        ]);

        DB::connection()->enableQueryLog();

        $this->actingAs($teacherUser)->get(route('guru.class-recap.show', [$year->id, $class->id, $placement->siswa_id]))
            ->assertOk()
            ->assertSee($activeRecord->keterangan)
            ->assertSee($cancelledRecord->keterangan)
            ->assertSee('Catatan salah siswa')
            ->assertSee('2026/2027')
            ->assertSee('>5<', false)
            ->assertDontSee('>45<', false);

        $this->assertAggregationQueriesAreStrictModeSafe();

        $this->actingAs($teacherUser)->get(route('guru.class-recap.show', [$year->id, $otherClass->id, $otherPlacement->siswa_id]))
            ->assertForbidden();
    }

    public function test_assigned_teacher_can_export_filtered_pdf_and_two_sheet_excel(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);
        $class = Kelas::factory()->create(['nama_kelas' => 'XI RPL 2']);
        $outsideClass = Kelas::factory()->create(['nama_kelas' => 'XII AKL 1']);
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $outsidePlacement = KelasSiswa::factory()->create([
            'kelas_id' => $outsideClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $violation = Pelanggaran::factory()->create();
        $active = PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-12',
            'poin_saat_dicatat' => 8,
        ]);
        $cancelled = PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-13',
            'poin_saat_dicatat' => 90,
            'status' => 'dibatalkan',
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $outsidePlacement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-14',
            'poin_saat_dicatat' => 100,
        ]);

        $parameters = [$year->id, $class->id, 'tanggal_mulai' => '2026-08-01', 'tanggal_selesai' => '2026-08-31'];
        DB::connection()->enableQueryLog();

        $pdfResponse = $this->actingAs($teacherUser)->get(route('guru.class-recap.pdf', $parameters))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdfResponse->getContent());
        $this->assertStringEndsWith('%%EOF', trim($pdfResponse->getContent()));

        $excelResponse = $this->actingAs($teacherUser)->get(route('guru.class-recap.excel', $parameters));
        $excelResponse->assertOk();
        $this->assertStringContainsString('spreadsheetml.sheet', (string) $excelResponse->headers->get('content-type'));
        $spreadsheet = IOFactory::load($excelResponse->baseResponse->getFile()->getPathname());
        $this->assertSame(['Ringkasan', 'Rincian Kejadian'], $spreadsheet->getSheetNames());

        $summaryValues = implode(' ', array_map('strval', $spreadsheet->getSheetByName('Ringkasan')->toArray(null, true, true, false)[1]));
        $detailRows = $spreadsheet->getSheetByName('Rincian Kejadian')->toArray(null, true, true, false);
        $detailValues = implode(' ', array_map('strval', $detailRows[1]));
        $this->assertStringContainsString($placement->siswa->nama, $summaryValues);
        $this->assertStringContainsString($active->keterangan, $detailValues);
        $this->assertStringContainsString($cancelled->keterangan, implode(' ', array_map('strval', $detailRows[2])));
        $this->assertStringNotContainsString('XII AKL 1', implode(' ', array_map('strval', $detailRows[1])));
        $this->assertCount(3, $detailRows);
        $this->assertAggregationQueriesAreStrictModeSafe();
    }

    public function test_homeroom_excel_export_includes_all_filtered_records_beyond_ten_per_page(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $class = Kelas::factory()->create();
        $student = Siswa::factory()->create();
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()->create([
            'siswa_id' => $student->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $violation = Pelanggaran::factory()->create();
        PelanggaranSiswa::factory()->count(12)->sequence(fn ($sequence): array => [
            'kelas_siswa_id' => $placement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-'.str_pad((string) ($sequence->index + 1), 2, '0', STR_PAD_LEFT),
            'keterangan' => 'Rekap ekspor '.$sequence->index,
        ])->create();

        $response = $this->actingAs($teacherUser)->get(route('guru.class-recap.excel', [
            $year->id,
            $class->id,
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-31',
        ]));
        $response->assertOk();
        $spreadsheet = IOFactory::load($response->baseResponse->getFile()->getPathname());
        $detailRows = $spreadsheet->getSheetByName('Rincian Kejadian')->toArray(null, true, true, false);

        $this->assertCount(13, $detailRows);
        $detailValues = implode(' ', array_map('strval', array_column(array_slice($detailRows, 1), 9)));
        $this->assertStringContainsString('Rekap ekspor 0', $detailValues);
        $this->assertStringContainsString('Rekap ekspor 11', $detailValues);
    }

    public function test_teacher_cannot_export_an_unassigned_class_by_changing_url(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->create(['user_id' => $teacherUser->id]);
        $year = TahunAjaran::factory()->create();
        $class = Kelas::factory()->create();
        WaliKelas::factory()->create([
            'guru_id' => $teacher->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => $year->tanggal_mulai,
            'status_aktif' => true,
        ]);
        $otherClass = Kelas::factory()->create();

        $this->actingAs($teacherUser)->get(route('guru.class-recap.pdf', [$year->id, $otherClass->id]))->assertForbidden();
        $this->actingAs($teacherUser)->get(route('guru.class-recap.excel', [$year->id, $otherClass->id]))->assertForbidden();
    }

    public function test_class_transfer_keeps_old_violation_context_and_new_recap_separate(): void
    {
        $previousTeacherUser = User::factory()->create(['role' => 'guru']);
        $previousTeacher = Guru::factory()->create(['user_id' => $previousTeacherUser->id]);
        $currentTeacherUser = User::factory()->create(['role' => 'guru']);
        $currentTeacher = Guru::factory()->create(['user_id' => $currentTeacherUser->id]);
        $previousYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'status_aktif' => false,
        ]);
        $currentYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);
        $previousClass = Kelas::factory()->create(['nama_kelas' => 'X RPL 1']);
        $currentClass = Kelas::factory()->create(['nama_kelas' => 'XI RPL 2']);
        WaliKelas::factory()->create([
            'guru_id' => $previousTeacher->id,
            'kelas_id' => $previousClass->id,
            'tahun_ajaran_id' => $previousYear->id,
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'status_aktif' => false,
        ]);
        WaliKelas::factory()->create([
            'guru_id' => $currentTeacher->id,
            'kelas_id' => $currentClass->id,
            'tahun_ajaran_id' => $currentYear->id,
            'tanggal_mulai' => '2026-07-01',
            'status_aktif' => true,
        ]);
        $previousPlacement = KelasSiswa::factory()->create([
            'kelas_id' => $previousClass->id,
            'tahun_ajaran_id' => $previousYear->id,
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
        ]);
        $currentPlacement = KelasSiswa::factory()->create([
            'siswa_id' => $previousPlacement->siswa_id,
            'kelas_id' => $currentClass->id,
            'tahun_ajaran_id' => $currentYear->id,
            'tanggal_mulai' => '2026-07-01',
        ]);
        $violation = Pelanggaran::factory()->create();
        $previousRecord = PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $previousPlacement->id,
            'guru_id' => $previousTeacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-03-10',
            'poin_saat_dicatat' => 4,
        ]);
        $currentRecord = PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $currentPlacement->id,
            'guru_id' => $currentTeacher->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => '2026-08-10',
            'poin_saat_dicatat' => 7,
        ]);

        $this->actingAs($previousTeacherUser)->get(route('guru.class-recap.show', [
            $previousYear->id,
            $previousClass->id,
            $previousPlacement->siswa_id,
        ]))
            ->assertOk()
            ->assertSee($previousRecord->keterangan)
            ->assertSee('X RPL 1')
            ->assertSee('2025/2026')
            ->assertDontSee($currentRecord->keterangan);

        $this->actingAs($currentTeacherUser)->get(route('guru.class-recap.show', [
            $currentYear->id,
            $currentClass->id,
            $currentPlacement->siswa_id,
        ]))
            ->assertOk()
            ->assertSee($currentRecord->keterangan)
            ->assertSee('XI RPL 2')
            ->assertSee('2026/2027')
            ->assertDontSee($previousRecord->keterangan);
    }

    /** @return array{TahunAjaran, Kelas, KelasSiswa, Guru, Pelanggaran} */
    private function recordContext(): array
    {
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => now()->subMonths(4)->toDateString(),
            'tanggal_selesai' => now()->addMonths(4)->toDateString(),
            'status_aktif' => true,
        ]);
        $class = Kelas::factory()->create();
        $placement = KelasSiswa::factory()->create([
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => $year->tanggal_mulai,
        ]);
        $teacher = Guru::factory()->create();
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->create(['kategori_id' => $category->id]);

        return [$year, $class, $placement, $teacher, $violation];
    }

    private function assertAggregationQueriesAreStrictModeSafe(): void
    {
        $queryLog = DB::getQueryLog();
        DB::connection()->disableQueryLog();
        $aggregateQueries = collect($queryLog)
            ->pluck('query')
            ->filter(static fn (string $sql): bool => preg_match('/count\s*\(|sum\s*\(/i', $sql) === 1
                && str_contains(strtolower($sql), 't_pelanggaran_siswa'));

        $this->assertNotEmpty($aggregateQueries);

        foreach ($aggregateQueries as $sql) {
            $this->assertDoesNotMatchRegularExpression('/select\s+(?:distinct\s+)?t_pelanggaran_siswa\.\*/i', $sql);
        }
    }
}
