<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_search_and_update_class(): void
    {
        $this->actingAs(User::factory()->state(['role' => 'admin'])->create());

        $this->post(route('admin.classes.store'), ['nama_kelas' => 'VII A', 'tingkat' => 'VII', 'jurusan' => null])
            ->assertRedirect();
        $kelas = Kelas::query()->firstOrFail();
        $this->get(route('admin.classes.index', ['q' => 'VII']))->assertOk()->assertSee('VII A');
        $this->get(route('admin.classes.show', $kelas))->assertOk();
        $this->get(route('admin.classes.edit', $kelas))->assertOk();
        $this->put(route('admin.classes.update', $kelas), ['nama_kelas' => 'VII B', 'tingkat' => 'VII', 'jurusan' => null])
            ->assertRedirect(route('admin.classes.show', $kelas));
        $this->assertDatabaseHas('t_kelas', ['id' => $kelas->id, 'nama_kelas' => 'VII B']);
    }

    public function test_class_with_placement_history_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->state(['role' => 'admin'])->create());
        $kelas = Kelas::factory()->create();
        $tahunAjaran = TahunAjaran::factory()->create();
        $siswa = Siswa::factory()->create();
        KelasSiswa::factory()->create([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'status_aktif' => false,
            'tanggal_mulai' => $tahunAjaran->tanggal_mulai,
            'tanggal_selesai' => $tahunAjaran->tanggal_selesai,
        ]);

        $this->delete(route('admin.classes.destroy', $kelas))->assertSessionHasErrors('kelas');
        $this->assertDatabaseHas('t_kelas', ['id' => $kelas->id]);
    }

    public function test_class_directory_shows_at_most_ten_records_per_page(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        Kelas::factory()->count(11)->sequence(fn ($sequence): array => [
            'nama_kelas' => 'Kelas Pagination '.$sequence->index,
        ])->create();

        $firstPage = $this->actingAs($administrator)->get(route('admin.classes.index'));
        $firstPage->assertViewHas('classes', fn ($classes): bool => $classes->count() === 10 && $classes->total() === 11)
            ->assertSeeText('');

        $this->get(route('admin.classes.index', ['page' => 2]))
            ->assertViewHas('classes', fn ($classes): bool => $classes->count() === 1 && $classes->total() === 11);
    }

    public function test_class_detail_shows_only_active_students_placed_in_the_active_academic_year(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $kelas = Kelas::factory()->create(['nama_kelas' => 'XI RPL 2']);
        $activeYear = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2026/2027', 'status_aktif' => true]);
        $oldYear = TahunAjaran::factory()->create(['nama_tahun_ajaran' => '2025/2026', 'status_aktif' => false]);
        $currentStudent = Siswa::factory()->create(['nama' => 'Siswa Tahun Aktif']);
        $oldStudent = Siswa::factory()->create(['nama' => 'Siswa Riwayat Lama']);
        $inactiveStudent = Siswa::factory()->create(['nama' => 'Siswa Nonaktif', 'status_siswa' => 'nonaktif']);

        KelasSiswa::factory()->for($currentStudent, 'siswa')->for($kelas, 'kelas')->for($activeYear, 'tahunAjaran')->create();
        KelasSiswa::factory()->for($oldStudent, 'siswa')->for($kelas, 'kelas')->for($oldYear, 'tahunAjaran')->create([
            'status_aktif' => false,
            'tanggal_selesai' => $oldYear->tanggal_selesai,
        ]);
        KelasSiswa::factory()->for($inactiveStudent, 'siswa')->for($kelas, 'kelas')->for($activeYear, 'tahunAjaran')->create();

        $this->actingAs($administrator)->get(route('admin.classes.show', $kelas))
            ->assertOk()
            ->assertSee('Siswa Tahun Aktif')
            ->assertSee('2026/2027')
            ->assertDontSee('Siswa Riwayat Lama')
            ->assertDontSee('Siswa Nonaktif')
            ->assertViewHas('placements', fn ($placements): bool => $placements->total() === 1);
    }

    public function test_class_detail_roster_search_and_pagination_keep_the_search_query(): void
    {
        $administrator = User::factory()->state(['role' => 'admin'])->create();
        $kelas = Kelas::factory()->create();
        $activeYear = TahunAjaran::factory()->create();
        Siswa::factory()->count(12)->sequence(fn ($sequence): array => [
            'nama' => 'Siswa Roster '.str_pad((string) $sequence->index, 2, '0', STR_PAD_LEFT),
        ])->create()->each(fn (Siswa $siswa) => KelasSiswa::factory()
            ->for($siswa, 'siswa')->for($kelas, 'kelas')->for($activeYear, 'tahunAjaran')->create());

        $firstPage = $this->actingAs($administrator)->get(route('admin.classes.show', $kelas));
        $firstPage->assertViewHas('placements', fn ($placements): bool => $placements->count() === 10 && $placements->total() === 12);

        $this->get(route('admin.classes.show', ['kelas' => $kelas, 'q' => 'Siswa Roster', 'page' => 2]))
            ->assertOk()
            ->assertSee('Siswa Roster 10')
            ->assertSee('Siswa Roster 11')
            ->assertDontSee('Siswa Roster 00')
            ->assertSee('q=Siswa%20Roster')
            ->assertViewHas('placements', fn ($placements): bool => $placements->count() === 2 && $placements->total() === 12);
    }

    public function test_teacher_cannot_access_class_management(): void
    {
        $teacher = Guru::factory()->create();
        $this->actingAs($teacher->user);

        $this->get(route('admin.classes.index'))->assertForbidden();
    }
}
