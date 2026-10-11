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
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruPencatatanPelanggaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_teacher_can_record_violation_using_server_owned_teacher_points_and_status(): void
    {
        [$teacherUser, $teacher, $student, $placement, $violation] = $this->recordingContext();
        $otherTeacher = Guru::factory()->create();

        $response = $this->actingAs($teacherUser)->post(route('guru.violations.store'), [
            'kelas_siswa_id' => $placement->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => now()->toDateString(),
            'keterangan' => 'Terlambat mengikuti pelajaran.',
            'tindakan' => 'Diberi pembinaan.',
            'guru_id' => $otherTeacher->id,
            'poin_saat_dicatat' => 999,
            'status' => 'dibatalkan',
            'kelas_id' => 999,
            'tahun_ajaran_id' => 999,
        ]);

        $response->assertRedirect(route('guru.students.show', $student))
            ->assertSessionHas('success', 'Catatan pelanggaran berhasil disimpan.');

        $record = PelanggaranSiswa::query()->sole();
        $this->assertSame($teacher->id, $record->guru_id);
        $this->assertSame($placement->id, $record->kelas_siswa_id);
        $this->assertSame($violation->poin, $record->poin_saat_dicatat);
        $this->assertSame('aktif', $record->status);
        $this->assertSame(now()->toDateString(), $record->tanggal_pelanggaran->toDateString());
        $this->assertSame(now()->toDateString(), $record->tanggal_pelanggaran->format('Y-m-d'));
        $this->assertSame(now()->format('Y-m-d H:i:s'), $record->tanggal_dicatat->format('Y-m-d H:i:s'));
        $this->assertDatabaseHas('t_log_pelanggaran', [
            'pelanggaran_siswa_id' => $record->id,
            'user_id' => $teacherUser->id,
            'aksi' => 'tambah',
        ]);
        $this->assertSame(1, LogPelanggaran::query()->count());
    }

    public function test_invalid_fields_are_rejected_without_creating_a_record_or_log(): void
    {
        [$teacherUser] = $this->recordingContext();

        $response = $this->actingAs($teacherUser)->from(route('guru.violations.create'))->post(route('guru.violations.store'), [
            'kelas_siswa_id' => 999999,
            'pelanggaran_id' => 999999,
            'tanggal_pelanggaran' => 'tanggal-salah',
            'poin_saat_dicatat' => -10,
        ]);

        $response->assertRedirect(route('guru.violations.create'))
            ->assertSessionHasErrors(['kelas_siswa_id', 'pelanggaran_id', 'tanggal_pelanggaran']);
        $this->assertDatabaseCount('t_pelanggaran_siswa', 0);
        $this->assertDatabaseCount('t_log_pelanggaran', 0);
    }

    public function test_student_without_placement_valid_for_incident_date_cannot_be_selected_or_saved(): void
    {
        [$teacherUser, , , $placement, $violation] = $this->recordingContext();
        $incidentDate = CarbonImmutable::parse($placement->tanggal_mulai)->subDay()->toDateString();

        $this->actingAs($teacherUser)->getJson(route('guru.violations.student-search', [
            'q' => 'Siswa',
            'tanggal_pelanggaran' => $incidentDate,
        ]))->assertOk()->assertExactJson([]);

        $this->from(route('guru.violations.create'))
            ->post(route('guru.violations.store'), [
                'kelas_siswa_id' => $placement->id,
                'pelanggaran_id' => $violation->id,
                'tanggal_pelanggaran' => $incidentDate,
            ])
            ->assertRedirect(route('guru.violations.create'))
            ->assertSessionHasErrors('kelas_siswa_id');
        $this->assertDatabaseCount('t_pelanggaran_siswa', 0);
    }

    public function test_active_categories_and_their_active_violation_types_are_available_for_recording(): void
    {
        [$teacherUser, , $student, $placement, $firstViolation] = $this->recordingContext();
        $firstCategory = $firstViolation->kategori;
        $secondCategory = PelanggaranKategori::factory()->create([
            'nama_kategori' => 'Keterlambatan Uji',
            'status_aktif' => true,
        ]);
        $secondViolation = Pelanggaran::factory()->for($secondCategory, 'kategori')->create([
            'nama_pelanggaran' => 'Terlambat Masuk Kelas',
            'poin' => 12,
            'status_aktif' => true,
        ]);
        $activeCategoryWithoutActiveTypes = PelanggaranKategori::factory()->create([
            'nama_kategori' => 'Kategori Tanpa Jenis Aktif',
            'status_aktif' => true,
        ]);
        Pelanggaran::factory()->for($activeCategoryWithoutActiveTypes, 'kategori')->create(['status_aktif' => false]);
        $inactiveCategory = PelanggaranKategori::factory()->create([
            'nama_kategori' => 'Kategori Nonaktif',
            'status_aktif' => false,
        ]);
        $inactiveCategoryViolation = Pelanggaran::factory()->for($inactiveCategory, 'kategori')->create(['status_aktif' => true]);

        $this->actingAs($teacherUser)->get(route('guru.violations.create'))
            ->assertOk()
            ->assertSee($firstCategory->nama_kategori)
            ->assertSee($secondCategory->nama_kategori)
            ->assertSee($activeCategoryWithoutActiveTypes->nama_kategori)
            ->assertDontSee($inactiveCategory->nama_kategori)
            ->assertSee($firstViolation->nama_pelanggaran)
            ->assertSee($secondViolation->nama_pelanggaran)
            ->assertDontSee($inactiveCategoryViolation->nama_pelanggaran);

        foreach ([$firstViolation, $secondViolation] as $violation) {
            $this->post(route('guru.violations.store'), [
                'kelas_siswa_id' => $placement->id,
                'pelanggaran_id' => $violation->id,
                'tanggal_pelanggaran' => today()->toDateString(),
            ])->assertRedirect(route('guru.students.show', $student));
        }

        $this->assertDatabaseCount('t_pelanggaran_siswa', 2);
        $this->assertDatabaseHas('t_pelanggaran_siswa', [
            'pelanggaran_id' => $firstViolation->id,
            'poin_saat_dicatat' => $firstViolation->poin,
        ]);
        $this->assertDatabaseHas('t_pelanggaran_siswa', [
            'pelanggaran_id' => $secondViolation->id,
            'poin_saat_dicatat' => $secondViolation->poin,
        ]);
    }

    public function test_inactive_violation_or_category_is_not_available_for_recording(): void
    {
        [$teacherUser, , , $placement] = $this->recordingContext();
        $inactiveCategory = PelanggaranKategori::factory()->create(['status_aktif' => false]);
        $inactiveViolation = Pelanggaran::factory()->for($inactiveCategory, 'kategori')->create(['status_aktif' => true]);
        $activeCategory = PelanggaranKategori::factory()->create(['status_aktif' => true]);
        $inactiveTypeInActiveCategory = Pelanggaran::factory()->for($activeCategory, 'kategori')->create(['status_aktif' => false]);

        $this->actingAs($teacherUser)->get(route('guru.violations.create'))
            ->assertOk()
            ->assertDontSee($inactiveCategory->nama_kategori)
            ->assertDontSee($inactiveViolation->nama_pelanggaran)
            ->assertDontSee($inactiveTypeInActiveCategory->nama_pelanggaran);

        $this->from(route('guru.violations.create'))
            ->post(route('guru.violations.store'), [
                'kelas_siswa_id' => $placement->id,
                'pelanggaran_id' => $inactiveViolation->id,
                'tanggal_pelanggaran' => now()->toDateString(),
            ])
            ->assertRedirect(route('guru.violations.create'))
            ->assertSessionHasErrors('pelanggaran_id');
        $this->assertDatabaseCount('t_pelanggaran_siswa', 0);

        $this->from(route('guru.violations.create'))
            ->post(route('guru.violations.store'), [
                'kelas_siswa_id' => $placement->id,
                'pelanggaran_id' => $inactiveTypeInActiveCategory->id,
                'tanggal_pelanggaran' => now()->toDateString(),
            ])
            ->assertRedirect(route('guru.violations.create'))
            ->assertSessionHasErrors('pelanggaran_id');
        $this->assertDatabaseCount('t_pelanggaran_siswa', 0);
    }

    public function test_record_keeps_point_snapshot_after_master_point_changes(): void
    {
        [$teacherUser, , , $placement, $violation] = $this->recordingContext();

        $this->actingAs($teacherUser)->post(route('guru.violations.store'), [
            'kelas_siswa_id' => $placement->id,
            'pelanggaran_id' => $violation->id,
            'tanggal_pelanggaran' => now()->toDateString(),
        ])->assertRedirect();

        $record = PelanggaranSiswa::query()->sole();
        $violation->update(['poin' => $violation->poin + 17]);
        $record->refresh();

        $this->assertSame(7, $record->poin_saat_dicatat);
        $this->assertSame(24, $violation->fresh()->poin);
    }

    public function test_recording_routes_require_teacher_role_and_authenticated_account(): void
    {
        $this->get(route('guru.violations.create'))->assertRedirect(route('login'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('guru.violations.create'))->assertForbidden();
    }

    /** @return array{User, Guru, Siswa, KelasSiswa, Pelanggaran} */
    private function recordingContext(): array
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $student = Siswa::factory()->create(['nama' => 'Siswa Uji']);
        $class = Kelas::factory()->create(['nama_kelas' => 'VIII Uji']);
        $start = CarbonImmutable::now()->subYear()->startOfDay();
        $end = CarbonImmutable::now()->addYear()->endOfDay();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => $start->toDateString(),
            'tanggal_selesai' => $end->toDateString(),
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()
            ->for($student, 'siswa')
            ->for($class, 'kelas')
            ->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => $start->toDateString(), 'tanggal_selesai' => null]);
        $category = PelanggaranKategori::factory()->create(['status_aktif' => true]);
        $violation = Pelanggaran::factory()
            ->for($category, 'kategori')
            ->create(['poin' => 7, 'status_aktif' => true]);

        return [$teacherUser, $teacher, $student, $placement, $violation];
    }
}
