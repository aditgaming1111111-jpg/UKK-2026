<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_dashboard_shows_only_the_teachers_records_and_active_homeroom_assignment(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create(['nama' => 'Guru Utama']);
        $otherTeacherUser = User::factory()->create(['role' => 'guru']);
        $otherTeacher = Guru::factory()->for($otherTeacherUser, 'user')->create();
        $student = Siswa::factory()->create(['nama' => 'Siswa Contoh']);
        $class = Kelas::factory()->create(['nama_kelas' => 'VIII A']);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
            'status_aktif' => true,
        ]);
        $previousYear = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'status_aktif' => false,
        ]);
        $teacherPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $otherTeacherPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $previousPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($previousYear, 'tahunAjaran')->create(['status_aktif' => false]);
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create(['nama_pelanggaran' => 'Terlambat']);

        $activeRecord = PelanggaranSiswa::factory()
            ->for($teacherPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['status' => 'aktif', 'poin_saat_dicatat' => 5]);
        PelanggaranSiswa::factory()
            ->for($teacherPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['status' => 'dibatalkan', 'poin_saat_dicatat' => 8]);
        PelanggaranSiswa::factory()
            ->for($teacherPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['status' => 'dibatalkan', 'poin_saat_dicatat' => 3]);
        PelanggaranSiswa::factory()
            ->for($otherTeacherPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($otherTeacher, 'guru')
            ->create(['status' => 'aktif', 'poin_saat_dicatat' => 40]);
        PelanggaranSiswa::factory()
            ->for($previousPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['status' => 'aktif', 'poin_saat_dicatat' => 17]);
        $assignment = WaliKelas::factory()
            ->for($teacher, 'guru')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2026-07-01', 'status_aktif' => true]);

        $response = $this->actingAs($teacherUser)->get(route('guru.dashboard'));

        $response->assertViewIs('guru.dashboard')
            ->assertViewHas('stats', fn (array $stats): bool => $stats === [
                'total_records' => 3,
                'active_records' => 1,
                'cancelled_records' => 2,
            ])
            ->assertViewHas('recentRecords', fn ($records): bool => $records->count() === 3)
            ->assertViewHas('homeroomAssignment', fn (?WaliKelas $homeroomAssignment): bool => $homeroomAssignment?->is($assignment) ?? false)
            ->assertSee('Guru Utama')
            ->assertSee('Siswa Contoh')
            ->assertSee('Rekap Kelas Saya')
            ->assertSee('VIII A')
            ->assertSee('Dibatalkan')
            ->assertDontSee('Data Guru')
            ->assertDontSee('Kategori Pelanggaran')
            ->assertDontSee('Log Aktivitas')
            ->assertDontSee('Laporan');
    }

    public function test_selected_year_filters_the_teacher_records_and_hides_unassigned_class_recap(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $student = Siswa::factory()->create();
        $class = Kelas::factory()->create();
        $currentYear = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2026/2027', 'status_aktif' => true]);
        $previousYear = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2025/2026', 'status_aktif' => false]);
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();
        $currentPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($currentYear, 'tahunAjaran')->create();
        $previousPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($previousYear, 'tahunAjaran')->create(['status_aktif' => false]);
        PelanggaranSiswa::factory()
            ->for($currentPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 4]);
        PelanggaranSiswa::factory()
            ->for($previousPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 9, 'status' => 'dibatalkan']);

        $response = $this->actingAs($teacherUser)->get(route('guru.dashboard', [
            'tahun_ajaran_id' => $previousYear->id,
        ]));

        $response->assertViewHas('selectedAcademicYear', fn (TahunAjaran $year): bool => $year->is($previousYear))
            ->assertViewHas('stats', fn (array $stats): bool => $stats === [
                'total_records' => 1,
                'active_records' => 0,
                'cancelled_records' => 1,
            ])
            ->assertViewHas('hasActiveWaliKelas', false)
            ->assertSee('2025/2026')
            ->assertDontSee('Rekap Kelas Saya');
    }

    public function test_dashboard_shows_empty_state_when_no_year_or_teacher_records_exist(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($teacherUser, 'user')->create();

        $response = $this->actingAs($teacherUser)->get(route('guru.dashboard'));

        $response->assertSee('Tahun ajaran belum tersedia')
            ->assertSee('Belum ada catatan');
    }

    public function test_dashboard_redirects_guests_and_forbids_admin(): void
    {
        $this->get(route('guru.dashboard'))->assertRedirect(route('login'));

        $administrator = User::factory()->create(['role' => 'admin']);

        $this->actingAs($administrator)->get(route('guru.dashboard'))->assertForbidden();
    }
}
