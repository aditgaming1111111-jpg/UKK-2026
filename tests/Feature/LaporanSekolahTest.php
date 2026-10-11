<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use App\Models\PelanggaranSiswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'poin_saat_dicatat' => 70,
            'status' => 'dibatalkan',
        ]);
        PelanggaranSiswa::factory()->create([
            'kelas_siswa_id' => $otherPlacement->id,
            'guru_id' => $teacher->id,
            'pelanggaran_id' => $violation->id,
            'poin_saat_dicatat' => 90,
        ]);

        $this->actingAs($teacherUser)->get(route('guru.class-recap.index', ['tahun_ajaran_id' => $year->id]))
            ->assertOk()
            ->assertSee($assignedPlacement->siswa->nama)
            ->assertDontSee($otherClass->nama_kelas)
            ->assertSee('Catatan aktif kelas')
            ->assertSee('6');
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
}
