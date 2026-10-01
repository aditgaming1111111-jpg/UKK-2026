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

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_dashboard_shows_database_totals_and_excludes_cancelled_points(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create(['nama' => 'Guru Aktif']);
        $inactiveTeacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($inactiveTeacherUser, 'user')->create(['status_aktif' => false]);

        $activeStudent = Siswa::factory()->create(['nama' => 'Siswa Aktif']);
        Siswa::factory()->create(['status_siswa' => 'nonaktif']);
        $class = Kelas::factory()->create(['nama_kelas' => 'VIII A']);
        $selectedYear = TahunAjaran::factory()->create([
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
        $selectedPlacement = KelasSiswa::factory()
            ->for($activeStudent, 'siswa')
            ->for($class, 'kelas')
            ->for($selectedYear, 'tahunAjaran')
            ->create();
        $previousPlacement = KelasSiswa::factory()
            ->for($activeStudent, 'siswa')
            ->for($class, 'kelas')
            ->for($previousYear, 'tahunAjaran')
            ->create(['status_aktif' => false]);

        $uniformCategory = PelanggaranKategori::factory()->create(['nama_kategori' => 'Atribut']);
        $disciplineCategory = PelanggaranKategori::factory()->create(['nama_kategori' => 'Ketertiban']);
        $uniformViolation = Pelanggaran::factory()->for($uniformCategory, 'kategori')->create([
            'nama_pelanggaran' => 'Tidak memakai atribut',
            'poin' => 2,
        ]);
        $disciplineViolation = Pelanggaran::factory()->for($disciplineCategory, 'kategori')->create([
            'nama_pelanggaran' => 'Terlambat masuk',
            'poin' => 3,
        ]);

        $firstActiveRecord = PelanggaranSiswa::factory()
            ->for($selectedPlacement, 'penempatanKelas')
            ->for($uniformViolation, 'pelanggaran')
            ->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 5, 'status' => 'aktif']);
        PelanggaranSiswa::factory()
            ->for($selectedPlacement, 'penempatanKelas')
            ->for($disciplineViolation, 'pelanggaran')
            ->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 3, 'status' => 'aktif']);
        PelanggaranSiswa::factory()
            ->for($selectedPlacement, 'penempatanKelas')
            ->for($uniformViolation, 'pelanggaran')
            ->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 90, 'status' => 'dibatalkan']);
        PelanggaranSiswa::factory()
            ->for($previousPlacement, 'penempatanKelas')
            ->for($uniformViolation, 'pelanggaran')
            ->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 12, 'status' => 'aktif']);
        LogPelanggaran::factory()
            ->for($firstActiveRecord, 'catatanPelanggaran')
            ->for($administrator, 'pengguna')
            ->create(['aksi' => 'edit', 'alasan' => 'Koreksi data']);

        $response = $this->actingAs($administrator)->get(route('admin.dashboard'));

        $response->assertViewIs('admin.dashboard')
            ->assertViewHas('stats', fn (array $stats): bool => $stats === [
                'active_students' => 1,
                'active_teachers' => 1,
                'active_violations' => 2,
                'active_points' => 8,
            ])
            ->assertViewHas('categorySummary', fn ($categories): bool => $categories->count() === 2
                && (int) $categories->firstWhere('nama_kategori', 'Atribut')->total_poin === 5
                && (int) $categories->firstWhere('nama_kategori', 'Ketertiban')->total_poin === 3)
            ->assertViewHas('recentViolations', fn ($records): bool => $records->count() === 3)
            ->assertViewHas('recentActivities', fn ($activities): bool => $activities->count() === 1)
            ->assertSee('Siswa Aktif')
            ->assertSee('Terlambat masuk')
            ->assertSee('Dibatalkan')
            ->assertSee('Koreksi data');
    }

    public function test_selected_academic_year_filters_violation_statistics_and_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $student = Siswa::factory()->create();
        $class = Kelas::factory()->create();
        $activeYear = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2026/2027', 'status_aktif' => true]);
        $previousYear = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2025/2026', 'status_aktif' => false]);
        $category = PelanggaranKategori::factory()->create(['nama_kategori' => 'Kedisiplinan']);
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();

        $activePlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($activeYear, 'tahunAjaran')->create();
        $previousPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($class, 'kelas')->for($previousYear, 'tahunAjaran')->create(['status_aktif' => false]);
        PelanggaranSiswa::factory()->for($activePlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 4]);
        $previousRecord = PelanggaranSiswa::factory()->for($previousPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 11]);
        LogPelanggaran::factory()->for($previousRecord, 'catatanPelanggaran')->for($administrator, 'pengguna')->create();

        $response = $this->actingAs($administrator)->get(route('admin.dashboard', [
            'tahun_ajaran_id' => $previousYear->id,
        ]));

        $response->assertViewHas('selectedAcademicYear', fn (TahunAjaran $year): bool => $year->is($previousYear))
            ->assertViewHas('stats', fn (array $stats): bool => $stats['active_violations'] === 1
                && $stats['active_points'] === 11)
            ->assertViewHas('recentViolations', fn ($records): bool => $records->count() === 1)
            ->assertViewHas('recentActivities', fn ($activities): bool => $activities->count() === 1)
            ->assertSee('2025/2026');
    }

    public function test_dashboard_shows_empty_states_when_school_has_no_academic_year(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($administrator)->get(route('admin.dashboard'));

        $response->assertSee('Tahun ajaran belum tersedia')
            ->assertSee('Belum ada catatan')
            ->assertSee('Belum ada ringkasan')
            ->assertSee('Belum ada aktivitas');
    }

    public function test_dashboard_redirects_guests_and_forbids_guru(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        $guruUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($guruUser, 'user')->create();

        $this->actingAs($guruUser)->get(route('admin.dashboard'))->assertForbidden();
    }
}
