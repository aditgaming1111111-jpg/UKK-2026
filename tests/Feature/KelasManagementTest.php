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

    public function test_teacher_cannot_access_class_management(): void
    {
        $teacher = Guru::factory()->create();
        $this->actingAs($teacher->user);

        $this->get(route('admin.classes.index'))->assertForbidden();
    }
}
