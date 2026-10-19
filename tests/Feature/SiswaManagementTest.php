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

class SiswaManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_create_view_update_and_deactivate_a_student_without_deleting_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($administrator)->post(route('admin.students.store'), [
            'nisn' => '1234567890',
            'nama' => 'Ayu Lestari',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka',
            'tahun_masuk' => 2026,
        ]);

        $student = Siswa::query()->where('nisn', '1234567890')->firstOrFail();
        $response->assertRedirect(route('admin.students.show', $student));
        $this->assertModelExists($student);

        $response = $this->actingAs($administrator)->put(route('admin.students.update', $student), [
            'nisn' => '1234567890',
            'nama' => 'Ayu Lestari Putri',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Pendidikan',
            'tahun_masuk' => 2026,
        ]);

        $response->assertRedirect(route('admin.students.show', $student));
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'nama' => 'Ayu Lestari Putri']);

        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create();
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();

        $this->actingAs($administrator)->delete(route('admin.students.destroy', $student))
            ->assertRedirect(route('admin.students.show', $student));

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'nonaktif']);
        $this->assertDatabaseHas('t_kelas_siswa', ['id' => $placement->id, 'siswa_id' => $student->id]);
    }

    public function test_duplicate_nisn_is_rejected_with_an_indonesian_validation_message(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        Siswa::factory()->create(['nisn' => '1234567890']);

        $this->actingAs($administrator)
            ->from(route('admin.students.create'))
            ->post(route('admin.students.store'), [
                'nisn' => '1234567890',
                'nama' => 'Siswa Duplikat',
                'jenis_kelamin' => 'L',
                'tahun_masuk' => 2026,
            ])
            ->assertSessionHasErrors(['nisn' => 'NISN sudah terdaftar.']);

        $this->assertDatabaseCount('t_siswa', 1);
    }

    public function test_admin_can_reactivate_student_and_status_changes_preserve_class_and_violation_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'aktif']);
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create();
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $teacher = Guru::factory()->create();
        $category = PelanggaranKategori::factory()->create();
        $violationType = Pelanggaran::factory()->for($category, 'kategori')->create();
        $violation = PelanggaranSiswa::factory()
            ->for($placement, 'penempatanKelas')
            ->for($violationType, 'pelanggaran')
            ->for($teacher, 'guru')
            ->create();

        $this->actingAs($administrator)
            ->patch(route('admin.students.status', $student), ['status_siswa' => 'nonaktif'])
            ->assertRedirect(route('admin.students.show', $student))
            ->assertSessionHas('success', 'Siswa dinonaktifkan. Riwayat siswa tetap tersimpan.');
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'nonaktif']);

        $this->actingAs($administrator)->get(route('admin.students.show', $student))
            ->assertOk()
            ->assertSee('Aktifkan')
            ->assertSee('activate-student');

        $this->actingAs($administrator)
            ->patch(route('admin.students.status', $student), ['status_siswa' => 'aktif'])
            ->assertRedirect(route('admin.students.show', $student))
            ->assertSessionHas('success', 'Siswa berhasil diaktifkan. Riwayat siswa tetap tersimpan.');

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'aktif']);
        $this->assertDatabaseHas('t_kelas_siswa', ['id' => $placement->id, 'siswa_id' => $student->id]);
        $this->assertDatabaseHas('t_pelanggaran_siswa', ['id' => $violation->id, 'kelas_siswa_id' => $placement->id]);
    }

    public function test_student_status_change_rejects_invalid_values_and_non_admin_users(): void
    {
        $student = Siswa::factory()->create(['status_siswa' => 'nonaktif']);
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->patch(route('admin.students.status', $student), ['status_siswa' => 'unknown'])
            ->assertSessionHasErrors('status_siswa');
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'nonaktif']);

        $teacher = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($teacher, 'user')->create();
        $this->actingAs($teacher)
            ->patch(route('admin.students.status', $student), ['status_siswa' => 'aktif'])
            ->assertForbidden();
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'nonaktif']);
    }

    public function test_admin_can_move_a_student_and_keep_the_previous_class_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $oldClass = Kelas::factory()->create(['nama_kelas' => 'VII A']);
        $newClass = Kelas::factory()->create(['nama_kelas' => 'VII B']);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $oldPlacement = KelasSiswa::factory()
            ->for($student, 'siswa')->for($oldClass, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2026-07-01', 'tanggal_selesai' => null, 'status_aktif' => true]);

        $this->actingAs($administrator)->post(route('admin.students.placements.store', $student), [
            'kelas_id' => $newClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_mulai' => '2026-10-01',
        ])->assertRedirect(route('admin.students.show', $student));

        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $oldPlacement->id,
            'kelas_id' => $oldClass->id,
            'status_aktif' => false,
        ]);
        $this->assertSame('2026-09-30', $oldPlacement->refresh()->tanggal_selesai->toDateString());
        $this->assertDatabaseHas('t_kelas_siswa', [
            'siswa_id' => $student->id,
            'kelas_id' => $newClass->id,
            'tahun_ajaran_id' => $year->id,
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $this->assertSame('2026-10-01', $student->penempatanKelas()->where('kelas_id', $newClass->id)->firstOrFail()->tanggal_mulai->toDateString());

        $this->actingAs($administrator)->get(route('admin.students.show', $student))
            ->assertSee('VII A')
            ->assertSee('VII B')
            ->assertSee('Riwayat kelas');
    }

    public function test_conflicting_placement_is_rejected_without_changing_existing_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2026-07-01', 'status_aktif' => true]);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->post(route('admin.students.placements.store', $student), [
                'kelas_id' => Kelas::factory()->create()->id,
                'tahun_ajaran_id' => $year->id,
                'tanggal_mulai' => '2026-06-15',
            ])
            ->assertSessionHasErrors('tanggal_mulai');

        $this->assertDatabaseCount('t_kelas_siswa', 1);
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $placement->id,
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
    }

    public function test_student_detail_shows_class_history_and_active_snapshot_points_by_year(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $student = Siswa::factory()->create(['nama' => 'Siswa Rekap']);
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2026/2027']);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create(['nama_pelanggaran' => 'Terlambat']);
        PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 7, 'status' => 'aktif']);
        PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 12, 'status' => 'dibatalkan']);

        $this->actingAs($administrator)->get(route('admin.students.show', $student))
            ->assertSee('2026/2027')
            ->assertSee('Terlambat')
            ->assertSee('7 poin')
            ->assertSee('Dibatalkan');
    }

    public function test_guru_can_read_students_but_cannot_manage_student_or_placement_data(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($teacherUser, 'user')->create();
        $student = Siswa::factory()->create(['nama' => 'Siswa Terlihat']);

        $this->actingAs($teacherUser)->get(route('guru.students.index'))->assertOk()->assertSee('Siswa Terlihat');
        $this->actingAs($teacherUser)->get(route('guru.students.show', $student))->assertSee('Identitas siswa');
        $this->actingAs($teacherUser)->get(route('admin.students.index'))->assertForbidden();
        $this->actingAs($teacherUser)->post(route('admin.students.store'), [])->assertForbidden();
        $this->actingAs($teacherUser)->put(route('admin.students.update', $student), [])->assertForbidden();
        $this->actingAs($teacherUser)->delete(route('admin.students.destroy', $student))->assertForbidden();
    }

    public function test_admin_can_search_and_filter_students_by_current_class_level_major_and_status(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['nisn' => '9876543210', 'nama' => 'Nadia Utami']);
        $class = Kelas::factory()->create(['nama_kelas' => 'XI RPL 1', 'tingkat' => 'XI', 'jurusan' => 'RPL']);
        $year = TahunAjaran::factory()->create();
        KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();

        $this->actingAs($administrator)->get(route('admin.students.index', [
            'q' => 'Nadia',
            'kelas_id' => $class->id,
            'tingkat' => 'XI',
            'jurusan' => 'RPL',
            'status' => 'aktif',
        ]))->assertOk()->assertSee('Nadia Utami')->assertSee('XI RPL 1');

        $this->actingAs($administrator)->get(route('admin.students.index', ['q' => 'tidak ada']))
            ->assertOk()
            ->assertSee('Siswa tidak ditemukan');
    }

    public function test_teacher_cannot_access_admin_student_routes_and_guest_is_redirected(): void
    {
        $this->get(route('guru.students.index'))->assertRedirect(route('login'));

        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator)->get(route('guru.students.index'))->assertForbidden();
    }

    public function test_teacher_sees_own_violation_history_and_assigned_homeroom_history_only(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $otherTeacherUser = User::factory()->create(['role' => 'guru']);
        $otherTeacher = Guru::factory()->for($otherTeacherUser, 'user')->create();
        $student = Siswa::factory()->create();
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();
        PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 4]);
        PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($otherTeacher, 'guru')
            ->create(['poin_saat_dicatat' => 9]);

        $this->actingAs($teacherUser)->get(route('guru.students.show', $student))
            ->assertViewHas('violations', fn ($records): bool => $records->count() === 1)
            ->assertViewHas('pointRecap', fn ($recap): bool => $recap->first()['total_points'] === 4);

        WaliKelas::factory()->for($teacher, 'guru')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2026-07-01', 'tanggal_selesai' => null, 'status_aktif' => true]);

        $this->actingAs($teacherUser)->get(route('guru.students.show', $student))
            ->assertViewHas('violations', fn ($records): bool => $records->count() === 2)
            ->assertViewHas('pointRecap', fn ($recap): bool => $recap->first()['total_points'] === 13);
    }

    public function test_student_directory_paginates_ten_results_and_preserves_search_and_status_filters(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        Siswa::factory()->count(12)->sequence(fn ($sequence): array => [
            'nama' => 'Target Siswa '.str_pad((string) $sequence->index, 2, '0', STR_PAD_LEFT),
            'status_siswa' => 'aktif',
        ])->create();
        Siswa::factory()->create(['nama' => 'Siswa Lain', 'status_siswa' => 'aktif']);

        $filters = ['q' => 'Target Siswa', 'status' => 'aktif'];
        $firstPage = $this->actingAs($administrator)->get(route('admin.students.index', $filters));

        $firstPage->assertOk()
            ->assertSee('Target Siswa 00')
            ->assertDontSee('Target Siswa 11')
            ->assertSeeText('')
            ->assertViewHas('students', fn ($students): bool => $students->count() === 10 && $students->total() === 12);

        $secondPage = $this->get(route('admin.students.index', [...$filters, 'page' => 2]));

        $secondPage->assertOk()
            ->assertSee('Target Siswa 10')
            ->assertSee('Target Siswa 11')
            ->assertDontSee('Target Siswa 00')
            ->assertViewHas('students', fn ($students): bool => $students->count() === 2 && $students->total() === 12)
            ->assertSee('q=Target%20Siswa')
            ->assertSee('status=aktif');
    }

    public function test_student_detail_paginates_violation_history_without_losing_records_or_points(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create();
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();
        $teacher = Guru::factory()->create();

        PelanggaranSiswa::factory()->count(18)
            ->for($placement, 'penempatanKelas')
            ->for($violation, 'pelanggaran')
            ->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 2]);

        $this->actingAs($administrator)
            ->get(route('admin.students.show', $student))
            ->assertOk()
            ->assertViewHas('violations', fn ($records): bool => $records->count() === 10 && $records->total() === 18)
            ->assertViewHas('pointRecap', fn ($recap): bool => $recap->first()['total_points'] === 36);

        $this->get(route('admin.students.show', [$student, 'page' => 2]))
            ->assertOk()
            ->assertViewHas('violations', fn ($records): bool => $records->count() === 8 && $records->total() === 18);
    }
}
