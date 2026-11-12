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

/**
 * Menguji pengelolaan siswa, validasi dan perubahan status, riwayat penempatan, detail, serta hak akses.
 */
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
            'nis' => '0123456789',
            'nisn' => '1234567890',
            'nama' => 'Ayu Lestari',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2010-04-21',
            'alamat' => 'Jl. Merdeka',
            'tahun_masuk' => 2026,
        ]);

        $student = Siswa::query()->where('nisn', '1234567890')->firstOrFail();
        $response->assertRedirect(route('admin.students.show', $student));
        $this->assertModelExists($student);

        $response = $this->actingAs($administrator)->put(route('admin.students.update', $student), [
            'nis' => '0123456789',
            'nisn' => '1234567890',
            'nama' => 'Ayu Lestari Putri',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2010-04-21',
            'alamat' => 'Jl. Pendidikan',
            'tahun_masuk' => 2026,
        ]);

        $response->assertRedirect(route('admin.students.show', $student));
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'nama' => 'Ayu Lestari Putri']);
        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'nis' => '0123456789']);
        $this->assertSame('2010-04-21', $student->fresh()->tanggal_lahir->toDateString());
        $this->get(route('admin.students.show', $student))
            ->assertSee('0123456789')
            ->assertSee('21/04/2010');
        $this->get(route('admin.students.create'))
            ->assertSee('name="nis"', false)
            ->assertSee('name="nisn"', false)
            ->assertSee('name="tanggal_lahir"', false);

        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create();
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create();

        $this->actingAs($administrator)->patch(route('admin.students.status', $student), ['status_siswa' => 'nonaktif'])
            ->assertRedirect(route('admin.students.show', $student));

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'nonaktif']);
        $this->assertDatabaseHas('t_kelas_siswa', ['id' => $placement->id, 'siswa_id' => $student->id]);
    }

    public function test_admin_can_permanently_delete_a_student_without_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();

        $this->actingAs($administrator)->delete(route('admin.students.destroy', $student))
            ->assertRedirect(route('admin.students.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($student);
    }

    public function test_student_with_history_cannot_be_permanently_deleted(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $placement = KelasSiswa::factory()->create(['siswa_id' => $student->id]);

        $this->actingAs($administrator)->delete(route('admin.students.destroy', $student))
            ->assertSessionHasErrors('siswa');

        $this->assertModelExists($student);
        $this->assertModelExists($placement);
    }

    public function test_duplicate_nisn_is_rejected_with_an_indonesian_validation_message(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        Siswa::factory()->create(['nisn' => '1234567890']);

        $this->actingAs($administrator)
            ->from(route('admin.students.create'))
            ->post(route('admin.students.store'), [
                'nis' => '2234567890',
                'nisn' => '1234567890',
                'nama' => 'Siswa Duplikat',
                'jenis_kelamin' => 'L',
                'tahun_masuk' => 2026,
            ])
            ->assertSessionHasErrors(['nisn' => 'NISN sudah terdaftar.']);

        $this->assertDatabaseCount('t_siswa', 1);
    }

    public function test_nis_requires_exactly_ten_ascii_digits_on_create_and_update(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator);
        $validPayload = [
            'nisn' => '1234567890',
            'nama' => 'Siswa Validasi NIS',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ];

        $this->post(route('admin.students.store'), $validPayload)
            ->assertSessionHasErrors(['nis' => 'NIS wajib diisi.']);

        foreach (['123456789', '12345678901', '12345abcde'] as $invalidNis) {
            $this->from(route('admin.students.create'))->post(route('admin.students.store'), [
                ...$validPayload,
                'nis' => $invalidNis,
            ])->assertSessionHasErrors(['nis' => 'NIS harus terdiri dari tepat 10 digit angka (0–9).']);
        }

        $student = Siswa::factory()->create(['nis' => '0123456789']);
        $this->put(route('admin.students.update', $student), [
            ...$validPayload,
            'nisn' => $student->nisn,
        ])->assertSessionHasErrors(['nis' => 'NIS wajib diisi.']);

        foreach (['123456789', '12345678901', '12345abcde'] as $invalidNis) {
            $this->from(route('admin.students.edit', $student))->put(route('admin.students.update', $student), [
                ...$validPayload,
                'nis' => $invalidNis,
            ])->assertSessionHasErrors(['nis' => 'NIS harus terdiri dari tepat 10 digit angka (0–9).']);
        }
    }

    public function test_duplicate_nis_is_rejected_on_create_and_update(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $firstStudent = Siswa::factory()->create(['nis' => '0123456789']);
        $secondStudent = Siswa::factory()->create(['nis' => '9876543210']);
        $this->actingAs($administrator);

        $this->post(route('admin.students.store'), [
            'nis' => $firstStudent->nis,
            'nisn' => '5555555555',
            'nama' => 'NIS Duplikat',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertSessionHasErrors(['nis' => 'NIS sudah terdaftar.']);

        $this->put(route('admin.students.update', $secondStudent), [
            'nis' => $firstStudent->nis,
            'nisn' => $secondStudent->nisn,
            'nama' => $secondStudent->nama,
            'jenis_kelamin' => $secondStudent->jenis_kelamin,
            'tahun_masuk' => $secondStudent->tahun_masuk,
        ])->assertSessionHasErrors(['nis' => 'NIS sudah terdaftar.']);

        $this->assertDatabaseCount('t_siswa', 2);
        $this->assertDatabaseHas('t_siswa', ['id' => $secondStudent->id, 'nis' => '9876543210']);
    }

    public function test_nis_and_nisn_can_match_across_or_within_student_records(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator);

        $this->post(route('admin.students.store'), [
            'nis' => '0123456789',
            'nisn' => '1111111111',
            'nama' => 'Identitas Sama',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertRedirect();

        $this->post(route('admin.students.store'), [
            'nis' => '2222222222',
            'nisn' => '0123456789',
            'nama' => 'Identitas Silang',
            'jenis_kelamin' => 'P',
            'tahun_masuk' => 2026,
        ])->assertRedirect();

        $this->post(route('admin.students.store'), [
            'nis' => '3333333333',
            'nisn' => '3333333333',
            'nama' => 'Identitas Dalam Satu Siswa',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertRedirect();

        $this->assertDatabaseCount('t_siswa', 3);
    }

    public function test_nisn_requires_exactly_ten_ascii_digits_on_create_and_update(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator);
        $validPayload = [
            'nis' => '1234567890',
            'nama' => 'Siswa Validasi NISN',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ];

        foreach (['123456789', '12345678901', '12345abcde'] as $invalidNisn) {
            $this->from(route('admin.students.create'))->post(route('admin.students.store'), [
                ...$validPayload,
                'nisn' => $invalidNisn,
            ])->assertSessionHasErrors(['nisn' => 'NISN harus terdiri dari tepat 10 digit angka (0–9).']);
        }

        $student = Siswa::factory()->create(['nisn' => '9876543210']);
        foreach (['123456789', '12345678901', '12345abcde'] as $invalidNisn) {
            $this->from(route('admin.students.edit', $student))->put(route('admin.students.update', $student), [
                ...$validPayload,
                'nis' => $student->nis,
                'nisn' => $invalidNisn,
            ])->assertSessionHasErrors(['nisn' => 'NISN harus terdiri dari tepat 10 digit angka (0–9).']);
        }
    }

    public function test_legacy_student_can_remain_without_nis_or_birth_date(): void
    {
        $legacyStudent = Siswa::factory()->create(['nis' => null, 'tanggal_lahir' => null]);

        $this->assertDatabaseHas('t_siswa', [
            'id' => $legacyStudent->id,
            'nis' => null,
            'tanggal_lahir' => null,
        ]);
    }

    public function test_student_name_is_limited_to_one_hundred_characters_on_create_and_update(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator);
        $name = str_repeat('S', 100);

        $this->post(route('admin.students.store'), [
            'nis' => '1111111111',
            'nisn' => '1234567890',
            'nama' => $name,
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertRedirect();

        $student = Siswa::query()->where('nisn', '1234567890')->firstOrFail();
        $this->assertSame($name, $student->nama);

        $this->from(route('admin.students.create'))->post(route('admin.students.store'), [
            'nis' => '1111111112',
            'nisn' => '1234567891',
            'nama' => str_repeat('S', 101),
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertSessionHasErrors(['nama' => 'Nama siswa maksimal 100 karakter.']);

        $this->from(route('admin.students.edit', $student))->put(route('admin.students.update', $student), [
            'nis' => $student->nis,
            'nisn' => $student->nisn,
            'nama' => str_repeat('S', 101),
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertSessionHasErrors(['nama' => 'Nama siswa maksimal 100 karakter.']);

        $this->put(route('admin.students.update', $student), [
            'nis' => $student->nis,
            'nisn' => $student->nisn,
            'nama' => $name,
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2026,
        ])->assertRedirect(route('admin.students.show', $student));
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

    public function test_admin_can_mark_a_student_as_graduated_after_completing_grade_twelve_and_keep_archive_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['nama' => 'Alumni Dicari', 'status_siswa' => 'aktif']);
        $class = Kelas::factory()->create(['nama_kelas' => 'XII RPL 1', 'tingkat' => '12']);
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2024-07-01', 'tanggal_selesai' => null, 'status_aktif' => true]);

        $this->actingAs($administrator)->get(route('admin.students.show', $student))
            ->assertOk()
            ->assertSee('Tandai lulus');

        $this->patch(route('admin.students.status', $student), ['status_siswa' => 'lulus'])
            ->assertRedirect(route('admin.students.show', $student))
            ->assertSessionHas('success', 'Siswa ditandai lulus dan tersimpan sebagai arsip alumni. Riwayat tetap tersimpan.');

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'lulus']);
        $this->assertModelExists($placement);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Status siswa diubah',
        ]);

        $this->get(route('admin.students.index', ['q' => 'Alumni Dicari', 'status' => 'lulus']))
            ->assertOk()
            ->assertSee('Alumni Dicari')
            ->assertSee('Lulus');
        $this->get(route('admin.students.show', $student))->assertSee('arsip alumni');

        $this->delete(route('admin.students.destroy', $student))->assertSessionHasErrors('siswa');
        $this->assertModelExists($student);
        $this->assertModelExists($placement);
    }

    public function test_student_cannot_be_marked_graduated_without_a_completed_grade_twelve_year(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'aktif']);
        $class = Kelas::factory()->create(['tingkat' => 'XI']);
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2025-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2024-07-01', 'tanggal_selesai' => '2025-06-30', 'status_aktif' => false]);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->patch(route('admin.students.status', $student), ['status_siswa' => 'lulus'])
            ->assertSessionHasErrors([
                'status_siswa' => 'Siswa hanya dapat ditandai lulus setelah penempatan kelas XII selesai dan tahun ajaran berakhir.',
            ]);

        $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => 'aktif']);
        $this->assertModelExists($placement);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_existing_transfer_and_exit_statuses_remain_available(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'aktif']);

        foreach (['pindah', 'keluar'] as $status) {
            $this->actingAs($administrator)
                ->patch(route('admin.students.status', $student), ['status_siswa' => $status])
                ->assertRedirect(route('admin.students.show', $student));

            $this->assertDatabaseHas('t_siswa', ['id' => $student->id, 'status_siswa' => $status]);
        }
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

    public function test_admin_can_correct_placement_start_date_backward_without_matching_itself_as_overlap(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-12-31',
            'status_aktif' => false,
        ]);

        $this->actingAs($administrator)
            ->patch(route('admin.students.placements.update', [$student, $placement->id]), [
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-12-31',
            ])
            ->assertRedirect(route('admin.students.show', $student))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $placement->id,
            'tanggal_mulai' => '2026-08-01 00:00:00',
            'tanggal_selesai' => '2026-12-31 00:00:00',
        ]);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Tanggal penempatan siswa dikoreksi',
            'deskripsi' => sprintf(
                'Admin ID #%d mengoreksi tanggal penempatan #%d siswa %s (NISN %s). Sebelum: mulai 2026-09-01, selesai 2026-12-31. Sesudah: mulai 2026-08-01, selesai 2026-12-31.',
                $administrator->id,
                $placement->id,
                $student->nama,
                $student->nisn,
            ),
        ]);
    }

    public function test_admin_can_correct_placement_start_date_forward_without_matching_itself_as_overlap(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-09-30',
            'status_aktif' => false,
        ]);

        $this->actingAs($administrator)
            ->patch(route('admin.students.placements.update', [$student, $placement->id]), [
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-09-30',
            ])
            ->assertRedirect(route('admin.students.show', $student));

        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $placement->id,
            'tanggal_mulai' => '2026-08-01 00:00:00',
            'tanggal_selesai' => '2026-09-30 00:00:00',
        ]);
        $this->assertDatabaseCount('t_log_aktivitas', 1);
    }

    public function test_admin_cannot_correct_placement_into_another_placement_or_invalid_date_order(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $otherPlacement = KelasSiswa::factory()->for($student, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-08-31',
            'status_aktif' => false,
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-12-31',
            'status_aktif' => false,
        ]);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->patch(route('admin.students.placements.update', [$student, $placement->id]), [
                'tanggal_mulai' => '2026-08-15',
                'tanggal_selesai' => '2026-12-31',
            ])
            ->assertSessionHasErrors([
                'tanggal_mulai' => 'Tanggal penempatan bertumpang tindih dengan riwayat penempatan siswa lainnya.',
            ]);

        $this->assertModelExists($otherPlacement);
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $placement->id,
            'tanggal_mulai' => '2026-09-01 00:00:00',
            'tanggal_selesai' => '2026-12-31 00:00:00',
        ]);
        $this->assertDatabaseCount('t_log_aktivitas', 0);

        $this->patch(route('admin.students.placements.update', [$student, $placement->id]), [
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-09-30',
        ])->assertSessionHasErrors('tanggal_selesai');

        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_admin_cannot_correct_placement_dates_outside_existing_violation_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $teacher = Guru::factory()->create();
        $category = PelanggaranKategori::factory()->create();
        $violationType = Pelanggaran::factory()->for($category, 'kategori')->create();
        $violationRecord = PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')
            ->for($violationType, 'pelanggaran')->for($teacher, 'guru')
            ->create(['tanggal_pelanggaran' => '2026-11-05']);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->patch(route('admin.students.placements.update', [$student, $placement->id]), [
                'tanggal_mulai' => '2026-12-01',
                'tanggal_selesai' => null,
            ])
            ->assertSessionHasErrors([
                'tanggal_mulai' => 'Rentang tanggal baru akan membuat tanggal catatan pelanggaran di luar periode penempatan.',
            ]);

        $this->assertModelExists($placement);
        $this->assertModelExists($violationRecord);
        $this->assertDatabaseHas('t_kelas_siswa', [
            'id' => $placement->id,
            'tanggal_mulai' => '2026-07-01 00:00:00',
            'tanggal_selesai' => null,
        ]);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_admin_can_delete_unused_historical_placement_and_audit_it_without_touching_other_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['nama' => 'Andi Saputra', 'nisn' => '1234567890']);
        $year = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2026/2027']);
        $oldClass = Kelas::factory()->create(['nama_kelas' => 'X RPL 1']);
        $currentClass = Kelas::factory()->create(['nama_kelas' => 'XI RPL 2']);
        $oldPlacement = KelasSiswa::factory()->for($student, 'siswa')->for($oldClass, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2026-07-01', 'tanggal_selesai' => '2026-09-30', 'status_aktif' => false]);
        $currentPlacement = KelasSiswa::factory()->for($student, 'siswa')->for($currentClass, 'kelas')->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => '2026-10-01', 'tanggal_selesai' => null, 'status_aktif' => true]);
        $teacher = Guru::factory()->create();
        $homeroomAssignment = WaliKelas::factory()->for($teacher, 'guru')->for($oldClass, 'kelas')->for($year, 'tahunAjaran')->create();
        $category = PelanggaranKategori::factory()->create();
        $violationType = Pelanggaran::factory()->for($category, 'kategori')->create();
        $violationRecord = PelanggaranSiswa::factory()->for($currentPlacement, 'penempatanKelas')
            ->for($violationType, 'pelanggaran')->for($teacher, 'guru')->create();

        $this->actingAs($administrator)
            ->get(route('admin.students.show', $student))
            ->assertSee('X RPL 1')
            ->assertSee('Andi Saputra')
            ->assertSee('2026/2027')
            ->assertSee('01/07/2026')
            ->assertSee('Hapus riwayat');

        $this->delete(route('admin.students.placements.destroy', [$student, $oldPlacement->id]))
            ->assertRedirect(route('admin.students.show', $student))
            ->assertSessionHas('success');

        $this->assertModelMissing($oldPlacement);
        $this->assertModelExists($student);
        $this->assertModelExists($currentPlacement);
        $this->assertModelExists($homeroomAssignment);
        $this->assertModelExists($violationRecord);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Riwayat penempatan siswa dihapus',
            'deskripsi' => sprintf(
                'Admin ID #%d menghapus penempatan #%d milik siswa Andi Saputra (NISN 1234567890), kelas X RPL 1, tahun ajaran 2026/2027, periode 01/07/2026 sampai 30/09/2026.',
                $administrator->id,
                $oldPlacement->id,
            ),
        ]);
        $this->assertDatabaseCount('t_log_aktivitas', 1);
    }

    public function test_admin_cannot_delete_active_placement_of_an_active_student(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'aktif']);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->create([
            'status_aktif' => true,
            'tanggal_selesai' => null,
        ]);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->delete(route('admin.students.placements.destroy', [$student, $placement->id]))
            ->assertSessionHasErrors([
                'placement' => 'Siswa aktif harus memiliki penempatan pengganti sebelum riwayat ini dihapus. Tambahkan penempatan pengganti atau nonaktifkan siswa melalui tindakan yang tersedia.',
            ]);

        $this->assertModelExists($placement);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_admin_cannot_delete_last_historical_placement_of_an_active_student_without_a_replacement(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'aktif']);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->create([
            'status_aktif' => false,
            'tanggal_selesai' => '2026-08-01',
        ]);

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->delete(route('admin.students.placements.destroy', [$student, $placement->id]))
            ->assertSessionHasErrors('placement');

        $this->assertModelExists($placement);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_admin_cannot_delete_a_placement_referenced_by_violation_history(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'nonaktif']);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->create(['status_aktif' => false]);
        $teacher = Guru::factory()->create();
        $category = PelanggaranKategori::factory()->create();
        $violationType = Pelanggaran::factory()->for($category, 'kategori')->create();
        $violationRecord = PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')
            ->for($violationType, 'pelanggaran')->for($teacher, 'guru')->create();

        $this->actingAs($administrator)
            ->from(route('admin.students.show', $student))
            ->delete(route('admin.students.placements.destroy', [$student, $placement->id]))
            ->assertSessionHasErrors([
                'placement' => 'Riwayat penempatan ini masih terkait dengan catatan pelanggaran dan tidak dapat dihapus.',
            ]);

        $this->assertModelExists($placement);
        $this->assertModelExists($violationRecord);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_only_admin_can_delete_placement_and_another_students_placement_is_not_addressable(): void
    {
        $student = Siswa::factory()->create();
        $otherStudent = Siswa::factory()->create();
        $placement = KelasSiswa::factory()->for($otherStudent, 'siswa')->create(['status_aktif' => false]);
        $teacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($teacherUser, 'user')->create();

        $this->actingAs($teacherUser)
            ->delete(route('admin.students.placements.destroy', [$otherStudent, $placement->id]))
            ->assertForbidden();

        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator)
            ->delete(route('admin.students.placements.destroy', [$student, $placement->id]))
            ->assertNotFound();

        $this->assertModelExists($placement);
        $this->assertDatabaseCount('t_log_aktivitas', 0);
    }

    public function test_admin_can_delete_active_placement_after_student_is_deactivated(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['status_siswa' => 'nonaktif']);
        $placement = KelasSiswa::factory()->for($student, 'siswa')->create([
            'status_aktif' => true,
            'tanggal_selesai' => null,
        ]);

        $this->actingAs($administrator)
            ->delete(route('admin.students.placements.destroy', [$student, $placement->id]))
            ->assertRedirect(route('admin.students.show', $student));

        $this->assertModelMissing($placement);
        $this->assertModelExists($student);
        $this->assertDatabaseHas('t_log_aktivitas', [
            'user_id' => $administrator->id,
            'aktivitas' => 'Riwayat penempatan siswa dihapus',
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

    public function test_guru_cannot_open_student_directory_or_unassigned_student_details_or_manage_data(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($teacherUser, 'user')->create();
        $student = Siswa::factory()->create(['nama' => 'Siswa Terlihat']);

        $this->actingAs($teacherUser)->get('/guru/siswa')->assertNotFound();
        $this->get(route('guru.dashboard'))
            ->assertOk()
            ->assertDontSee('Data Siswa')
            ->assertSee('Rekap Poin');
        $this->actingAs($teacherUser)->get(route('guru.students.show', $student))->assertForbidden();
        $this->actingAs($teacherUser)->get(route('admin.students.index'))->assertForbidden();
        $this->actingAs($teacherUser)->post(route('admin.students.store'), [])->assertForbidden();
        $this->actingAs($teacherUser)->put(route('admin.students.update', $student), [])->assertForbidden();
        $this->actingAs($teacherUser)->delete(route('admin.students.destroy', $student))->assertForbidden();
    }

    public function test_admin_can_search_and_filter_students_by_current_class_level_major_and_status(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $student = Siswa::factory()->create(['nis' => '0987654321', 'nisn' => '9876543210', 'nama' => 'Nadia Utami']);
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

        $this->get(route('admin.students.index', ['q' => $student->nis]))
            ->assertOk()
            ->assertSee('Nadia Utami')
            ->assertSee('0987654321');

        $this->actingAs($administrator)->get(route('admin.students.index', ['q' => 'tidak ada']))
            ->assertOk()
            ->assertSee('Siswa tidak ditemukan');
    }

    public function test_teacher_cannot_access_admin_student_routes_and_guest_is_redirected(): void
    {
        $this->get('/guru/siswa')->assertNotFound();

        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator)->get('/guru/siswa')->assertNotFound();
        $this->get(route('admin.students.index'))
            ->assertOk()
            ->assertSee('Data Siswa');
    }

    public function test_only_assigned_homeroom_teacher_can_open_student_details_and_sees_full_history(): void
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
        $placement = KelasSiswa::factory()->for($student, 'siswa')->for($class, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => $year->tanggal_mulai->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();
        PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($teacher, 'guru')
            ->create(['poin_saat_dicatat' => 4]);
        PelanggaranSiswa::factory()->for($placement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($otherTeacher, 'guru')
            ->create(['poin_saat_dicatat' => 9]);

        $this->actingAs($teacherUser)->get(route('guru.students.show', $student))->assertForbidden();

        WaliKelas::factory()->for($teacher, 'guru')->for($class, 'kelas')->for($year, 'tahunAjaran')
            ->create([
                'tanggal_mulai' => $year->tanggal_mulai->toDateString(),
                'tanggal_selesai' => null,
                'status_aktif' => true,
            ]);

        $this->actingAs($teacherUser)->get(route('guru.students.show', $student))
            ->assertViewHas('violations', fn ($records): bool => $records->count() === 2)
            ->assertViewHas('pointRecap', fn ($recap): bool => $recap->first()['total_points'] === 13);
    }

    public function test_homeroom_teacher_cannot_open_student_detail_from_another_class(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $assignedClass = Kelas::factory()->create();
        $otherClass = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => today()->addMonth()->toDateString(),
            'status_aktif' => true,
        ]);
        $assignedStudent = Siswa::factory()->create(['nama' => 'Siswa Kelas Wali']);
        $otherStudent = Siswa::factory()->create(['nama' => 'Siswa Kelas Lain']);
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
        WaliKelas::factory()->for($teacher, 'guru')->for($assignedClass, 'kelas')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => null,
            'status_aktif' => true,
        ]);

        $this->actingAs($teacherUser)->get(route('guru.students.show', $assignedStudent))
            ->assertOk()
            ->assertSee('Siswa Kelas Wali');
        $this->get(route('guru.students.show', $otherStudent))->assertForbidden();
    }

    public function test_guru_can_see_active_point_recap_for_all_students_without_student_detail_access(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($teacherUser, 'user')->create();
        $recordingTeacher = Guru::factory()->create();
        $category = PelanggaranKategori::factory()->create();
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create();
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => today()->subMonth()->toDateString(),
            'tanggal_selesai' => today()->addMonth()->toDateString(),
            'status_aktif' => true,
        ]);
        $firstStudent = Siswa::factory()->create(['nama' => 'Siswa Rekap A']);
        $secondStudent = Siswa::factory()->create(['nama' => 'Siswa Rekap B']);
        $firstPlacement = KelasSiswa::factory()->for($firstStudent, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
        ]);
        $secondPlacement = KelasSiswa::factory()->for($secondStudent, 'siswa')->for($year, 'tahunAjaran')->create([
            'tanggal_mulai' => today()->subMonth()->toDateString(),
        ]);
        PelanggaranSiswa::factory()->for($firstPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($recordingTeacher, 'guru')
            ->create(['poin_saat_dicatat' => 6]);
        PelanggaranSiswa::factory()->for($firstPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($recordingTeacher, 'guru')
            ->create(['poin_saat_dicatat' => 11, 'status' => 'dibatalkan']);
        PelanggaranSiswa::factory()->for($secondPlacement, 'penempatanKelas')->for($violation, 'pelanggaran')->for($recordingTeacher, 'guru')
            ->create(['poin_saat_dicatat' => 4]);

        $this->actingAs($teacherUser)->get(route('guru.point-recap.index', ['tahun_ajaran_id' => $year->id]))
            ->assertOk()
            ->assertSee('Rekap Poin')
            ->assertSee('Siswa Rekap A')
            ->assertSee('Siswa Rekap B')
            ->assertSee('6 poin')
            ->assertSee('4 poin')
            ->assertDontSee('Detail Siswa');
        $this->get('/guru/siswa')->assertNotFound();
        $this->get(route('guru.students.show', $firstStudent))->assertForbidden();
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
