<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Pelanggaran;
use App\Models\PelanggaranKategori;
use App\Models\PelanggaranSiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelanggaranMasterManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_can_manage_categories_and_search_them(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.violation-categories.store'), [
            'nama_kategori' => '  Kedisiplinan  ',
            'deskripsi' => 'Pelanggaran terkait kedisiplinan.',
        ])->assertRedirect();

        $category = PelanggaranKategori::query()->firstOrFail();
        $this->assertSame('Kedisiplinan', $category->nama_kategori);
        $this->get(route('admin.violation-categories.create'))->assertSee('Tambah Kategori');
        $this->get(route('admin.violation-categories.index', ['q' => 'disiplin']))->assertSee('Kedisiplinan');
        $this->get(route('admin.violation-categories.show', $category))->assertSee('Kedisiplinan');
        $this->get(route('admin.violation-categories.edit', $category))->assertSee('Kedisiplinan');

        $this->put(route('admin.violation-categories.update', $category), [
            'nama_kategori' => 'Kedisiplinan Siswa',
            'deskripsi' => null,
        ])->assertRedirect(route('admin.violation-categories.show', $category));

        $this->assertDatabaseHas('t_pelanggaran_kategori', ['id' => $category->id, 'nama_kategori' => 'Kedisiplinan Siswa']);
    }

    public function test_category_directory_paginates_ten_records(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        PelanggaranKategori::factory()->count(11)->create();

        $this->actingAs($administrator)->get(route('admin.violation-categories.index'))
            ->assertViewHas('categories', fn ($categories): bool => $categories->count() === 10 && $categories->total() === 11)
            ->assertSeeText('');

        $this->get(route('admin.violation-categories.index', ['page' => 2]))
            ->assertViewHas('categories', fn ($categories): bool => $categories->count() === 1 && $categories->total() === 11);
    }

    public function test_admin_can_manage_violation_types_and_search_filter_and_paginate(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = PelanggaranKategori::factory()->create(['nama_kategori' => 'Kedisiplinan']);
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create([
            'kode' => 'PLG-0001',
            'nama_pelanggaran' => 'Terlambat masuk sekolah',
            'poin' => 5,
        ]);
        Pelanggaran::factory()->count(15)->for($category, 'kategori')->create();
        $this->assertDatabaseHas('t_pelanggaran_kategori', ['id' => $category->id, 'status_aktif' => true]);

        $this->actingAs($admin)->get(route('admin.violations.index', [
            'q' => 'PLG-0001',
            'kategori_id' => $category->id,
            'status' => 'aktif',
        ]))->assertSee('Terlambat masuk sekolah');
        $this->get(route('admin.violations.index'))
            ->assertSee('page=2')
            ->assertViewHas('violations', fn ($violations): bool => $violations->count() === 10 && $violations->total() === 16);
        $this->get(route('admin.violations.index', ['page' => 2]))
            ->assertViewHas('violations', fn ($violations): bool => $violations->count() === 6 && $violations->total() === 16);
        $this->get(route('admin.violations.create'))->assertSee('Kedisiplinan');
        $this->get(route('admin.violations.show', $violation))->assertSee('5');
        $this->get(route('admin.violations.edit', $violation))->assertSee('PLG-0001');

        $this->put(route('admin.violations.update', $violation), [
            'kode' => 'PLG-0001',
            'nama_pelanggaran' => 'Terlambat hadir',
            'kategori_id' => $category->id,
            'deskripsi' => null,
            'poin' => 8,
            'status_aktif' => '1',
        ])->assertRedirect(route('admin.violations.show', $violation));

        $this->assertDatabaseHas('t_pelanggaran', ['id' => $violation->id, 'poin' => 8, 'nama_pelanggaran' => 'Terlambat hadir']);

        $this->patch(route('admin.violations.status', $violation))->assertRedirect(route('admin.violations.show', $violation));
        $this->assertDatabaseHas('t_pelanggaran', ['id' => $violation->id, 'status_aktif' => false]);
        $this->patch(route('admin.violations.status', $violation))->assertRedirect(route('admin.violations.show', $violation));
        $this->assertDatabaseHas('t_pelanggaran', ['id' => $violation->id, 'status_aktif' => true]);
    }

    public function test_duplicate_code_and_invalid_points_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = PelanggaranKategori::factory()->create();
        Pelanggaran::factory()->for($category, 'kategori')->create(['kode' => 'PLG-0002']);

        $this->actingAs($admin)->from(route('admin.violations.create'))->post(route('admin.violations.store'), [
            'kode' => 'plg-0002',
            'nama_pelanggaran' => 'Kode duplikat',
            'kategori_id' => $category->id,
            'poin' => 0,
        ])->assertSessionHasErrors(['kode', 'poin']);

        $this->assertDatabaseCount('t_pelanggaran', 1);
    }

    public function test_inactive_category_cannot_be_selected_for_a_new_violation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $inactiveCategory = PelanggaranKategori::factory()->create(['status_aktif' => false]);

        $this->actingAs($admin)->from(route('admin.violations.create'))->post(route('admin.violations.store'), [
            'kode' => 'PLG-0003',
            'nama_pelanggaran' => 'Pelanggaran baru',
            'kategori_id' => $inactiveCategory->id,
            'poin' => 3,
        ])->assertSessionHasErrors('kategori_id');

        $this->assertDatabaseCount('t_pelanggaran', 0);
        $this->assertSame(0, Pelanggaran::availableForRecording()->count());
    }

    public function test_deactivating_master_keeps_old_note_and_snapshot_readable_but_hides_type_from_new_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = PelanggaranKategori::factory()->create(['nama_kategori' => 'Kedisiplinan']);
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create([
            'kode' => 'PLG-0004',
            'nama_pelanggaran' => 'Pelanggaran bersejarah',
            'poin' => 12,
        ]);
        $record = PelanggaranSiswa::factory()->for($violation, 'pelanggaran')->create(['poin_saat_dicatat' => 9]);

        $this->actingAs($admin)->put(route('admin.violations.update', $violation), [
            'kode' => 'PLG-0004',
            'nama_pelanggaran' => 'Pelanggaran bersejarah',
            'kategori_id' => $category->id,
            'deskripsi' => null,
            'poin' => 15,
            'status_aktif' => '1',
        ])->assertRedirect(route('admin.violations.show', $violation));

        $this->actingAs($admin)->patch(route('admin.violation-categories.status', $category))
            ->assertRedirect(route('admin.violation-categories.show', $category));

        $this->assertDatabaseHas('t_pelanggaran_kategori', ['id' => $category->id, 'status_aktif' => false]);
        $this->assertDatabaseHas('t_pelanggaran', ['id' => $violation->id, 'status_aktif' => false, 'poin' => 15]);
        $this->assertModelExists($record);
        $this->assertSame(9, $record->fresh()->poin_saat_dicatat);
        $this->assertSame(0, Pelanggaran::availableForRecording()->count());
        $this->get(route('admin.violations.show', $violation))
            ->assertSee('Pelanggaran bersejarah')
            ->assertSee('9');
    }

    public function test_non_admin_cannot_manage_master_and_recording_scope_only_contains_active_types_of_active_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $activeCategory = PelanggaranKategori::factory()->create(['status_aktif' => true]);
        $inactiveCategory = PelanggaranKategori::factory()->create(['status_aktif' => false]);
        $allowed = Pelanggaran::factory()->for($activeCategory, 'kategori')->create(['status_aktif' => true]);
        Pelanggaran::factory()->for($activeCategory, 'kategori')->create(['status_aktif' => false]);
        Pelanggaran::factory()->for($inactiveCategory, 'kategori')->create(['status_aktif' => true]);
        $teacher = Guru::factory()->create();

        $this->actingAs($admin)->get(route('admin.violations.index'))->assertOk();
        $this->actingAs($teacher->user)->get(route('admin.violations.index'))->assertForbidden();
        $this->assertSame([$allowed->id], Pelanggaran::availableForRecording()->pluck('id')->all());

        $this->assertDatabaseCount('t_pelanggaran', 3);
    }
}
