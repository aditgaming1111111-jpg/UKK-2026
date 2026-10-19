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

class CatatanSayaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guru_sees_only_own_records_including_cancelled_records(): void
    {
        User::factory()->create(['role' => 'guru']);
        $context = $this->recordingContext(['nama' => 'Siswa Milik Saya', 'nisn' => '1000000001']);
        $this->assertNotSame($context['user']->id, $context['teacher']->id);
        $ownRecord = $this->createRecord($context, ['status' => 'aktif', 'poin_saat_dicatat' => 6]);
        $cancelledRecord = $this->createRecord($context, ['status' => 'dibatalkan', 'poin_saat_dicatat' => 11]);

        $otherContext = $this->recordingContext(['nama' => 'Siswa Guru Lain', 'nisn' => '2000000002']);
        $otherRecord = $this->createRecord($otherContext);

        $this->actingAs($context['user'])
            ->get(route('guru.my-notes.index', ['guru_id' => $otherContext['teacher']->id]))
            ->assertViewHas('records', fn ($records): bool => $records->total() === 2
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($ownRecord))
                && $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($cancelledRecord))
                && ! $records->getCollection()->contains(fn (PelanggaranSiswa $record): bool => $record->is($otherRecord)))
            ->assertSee('Siswa Milik Saya')
            ->assertSee('1000000001')
            ->assertSee('6 poin')
            ->assertSee('11 poin')
            ->assertSee('Dibatalkan')
            ->assertSeeInOrder([
                'href="'.route('guru.my-notes.index').'"',
                'aria-current="page"',
                'Catatan Saya',
            ], false)
            ->assertDontSee('Siswa Guru Lain')
            ->assertDontSee('2000000002');
    }

    public function test_guru_can_search_own_notes_by_student_name_or_nisn(): void
    {
        $context = $this->recordingContext(['nama' => 'Mawar Pertiwi', 'nisn' => '1234509876']);
        $ownRecord = $this->createRecord($context);
        $otherStudent = Siswa::factory()->create(['nama' => 'Bunga Melati', 'nisn' => '9876501234']);
        $otherPlacement = KelasSiswa::factory()
            ->for($otherStudent, 'siswa')
            ->for($context['class'], 'kelas')
            ->for($context['year'], 'tahunAjaran')
            ->create(['tanggal_mulai' => now()->subMonth()->toDateString(), 'tanggal_selesai' => null]);
        $otherRecord = PelanggaranSiswa::factory()
            ->for($otherPlacement, 'penempatanKelas')
            ->for($context['violation'], 'pelanggaran')
            ->for($context['teacher'], 'guru')
            ->create();

        $this->actingAs($context['user'])->get(route('guru.my-notes.index', ['q' => 'Mawar Pertiwi']))
            ->assertViewHas('records', fn ($records): bool => $records->total() === 1
                && $records->getCollection()->first()->is($ownRecord))
            ->assertSee('1234509876')
            ->assertDontSee('Bunga Melati');

        $this->get(route('guru.my-notes.index', ['q' => '9876501234']))
            ->assertViewHas('records', fn ($records): bool => $records->total() === 1
                && $records->getCollection()->first()->is($otherRecord))
            ->assertSee('Bunga Melati')
            ->assertDontSee('Mawar Pertiwi');
    }

    public function test_guru_filters_own_records_by_academic_year_class_status_and_incident_date(): void
    {
        $context = $this->recordingContext();
        $todayRecord = $this->createRecord($context, [
            'tanggal_pelanggaran' => today()->toDateString(),
            'status' => 'aktif',
        ]);
        $yesterdayStudent = Siswa::factory()->create();
        $yesterdayPlacement = KelasSiswa::factory()
            ->for($yesterdayStudent, 'siswa')
            ->for($context['class'], 'kelas')
            ->for($context['year'], 'tahunAjaran')
            ->create(['tanggal_mulai' => now()->subMonth()->toDateString(), 'tanggal_selesai' => null]);
        $cancelledRecord = PelanggaranSiswa::factory()
            ->for($yesterdayPlacement, 'penempatanKelas')
            ->for($context['violation'], 'pelanggaran')
            ->for($context['teacher'], 'guru')
            ->create(['tanggal_pelanggaran' => today()->subDay()->toDateString(), 'status' => 'dibatalkan']);

        $this->actingAs($context['user'])->get(route('guru.my-notes.index', [
            'tahun_ajaran_id' => $context['year']->id,
            'kelas_id' => $context['class']->id,
            'tanggal_mulai' => today()->toDateString(),
            'tanggal_selesai' => today()->toDateString(),
            'status' => 'aktif',
        ]))->assertViewHas('records', fn ($records): bool => $records->total() === 1
            && $records->getCollection()->first()->is($todayRecord));

        $this->get(route('guru.my-notes.index', [
            'tahun_ajaran_id' => $context['year']->id,
            'kelas_id' => $context['class']->id,
            'tanggal_mulai' => today()->subDay()->toDateString(),
            'tanggal_selesai' => today()->subDay()->toDateString(),
            'status' => 'dibatalkan',
        ]))->assertViewHas('records', fn ($records): bool => $records->total() === 1
            && $records->getCollection()->first()->is($cancelledRecord))
            ->assertSee('Dibatalkan');
    }

    public function test_my_notes_paginates_ten_records_and_keeps_search_filter_between_pages(): void
    {
        $context = $this->recordingContext(['nama' => 'Siswa Filter Pagination']);
        $records = collect(range(1, 12))->map(fn (): PelanggaranSiswa => $this->createRecord($context));
        $filters = ['q' => 'Siswa Filter Pagination', 'status' => 'aktif'];

        $firstPage = $this->actingAs($context['user'])->get(route('guru.my-notes.index', $filters));
        $firstPage->assertViewHas('records', fn ($page): bool => $page->count() === 10 && $page->total() === 12)
            ->assertSeeText('');
        $firstPageIds = $firstPage->viewData('records')->getCollection()->modelKeys();

        $secondPage = $this->get(route('guru.my-notes.index', [...$filters, 'page' => 2]));
        $secondPage->assertViewHas('records', fn ($page): bool => $page->count() === 2 && $page->total() === 12)
            ->assertSee('q=Siswa%20Filter%20Pagination')
            ->assertSee('status=aktif');
        $secondPageIds = $secondPage->viewData('records')->getCollection()->modelKeys();

        $this->assertSame([], array_values(array_intersect($firstPageIds, $secondPageIds)));
        $this->assertCount(12, $records);
    }

    public function test_guru_can_view_own_detail_and_cancellation_audit_but_not_another_teachers_detail(): void
    {
        $context = $this->recordingContext();
        $ownRecord = $this->createRecord($context, ['status' => 'dibatalkan', 'poin_saat_dicatat' => 9]);
        $reason = 'Catatan dibuat untuk siswa yang keliru.';
        LogPelanggaran::factory()->for($ownRecord, 'catatanPelanggaran')->for($context['user'], 'pengguna')->create([
            'aksi' => 'batal',
            'alasan' => $reason,
            'data_sebelum' => json_encode(['status' => 'aktif'], JSON_THROW_ON_ERROR),
            'data_sesudah' => json_encode(['status' => 'dibatalkan'], JSON_THROW_ON_ERROR),
        ]);

        $otherContext = $this->recordingContext();
        $otherRecord = $this->createRecord($otherContext);

        $this->actingAs($context['user'])->get(route('guru.my-notes.show', $ownRecord))
            ->assertSee($context['student']->nama)
            ->assertSee($context['student']->nisn)
            ->assertSee($context['class']->nama_kelas)
            ->assertSee($context['year']->nama_tahun_ajaran)
            ->assertSee($context['violation']->nama_pelanggaran)
            ->assertSee('9 poin')
            ->assertSee('Tanggal pencatatan')
            ->assertSee('Dibatalkan')
            ->assertSee($reason)
            ->assertSee('Data sebelum')
            ->assertSeeInOrder([
                'href="'.route('guru.my-notes.index').'"',
                'aria-current="page"',
                'Catatan Saya',
            ], false)
            ->assertDontSee('Koreksi catatan')
            ->assertDontSee('Batalkan catatan');

        $this->get(route('guru.my-notes.show', $otherRecord))->assertForbidden();
    }

    public function test_guest_and_admin_cannot_access_teacher_notes_and_empty_state_is_rendered(): void
    {
        $this->get(route('guru.my-notes.index'))->assertRedirect(route('login'));

        $administrator = User::factory()->create(['role' => 'admin']);
        $this->actingAs($administrator)->get(route('guru.my-notes.index'))->assertForbidden();

        $context = $this->recordingContext();
        $this->actingAs($context['user'])->get(route('guru.my-notes.index'))
            ->assertSee('Belum ada catatan yang sesuai')
            ->assertSee('Catatan yang Anda buat akan muncul di sini.');
    }

    public function test_invalid_status_and_reversed_date_range_are_rejected(): void
    {
        $context = $this->recordingContext();

        $this->actingAs($context['user'])
            ->from(route('guru.my-notes.index'))
            ->get(route('guru.my-notes.index', ['status' => 'dihapus']))
            ->assertSessionHasErrors('status');

        $this->get(route('guru.my-notes.index', [
            'tanggal_mulai' => today()->toDateString(),
            'tanggal_selesai' => today()->subDay()->toDateString(),
        ]))->assertSessionHasErrors('tanggal_selesai');
    }

    /** @return array{user: User, teacher: Guru, student: Siswa, class: Kelas, year: TahunAjaran, placement: KelasSiswa, violation: Pelanggaran} */
    private function recordingContext(array $studentAttributes = []): array
    {
        $user = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($user, 'user')->create();
        $student = Siswa::factory()->create($studentAttributes);
        $class = Kelas::factory()->create();
        $year = TahunAjaran::factory()->create([
            'nama_tahun_ajaran' => now()->year.'/'.now()->addYear()->year,
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'status_aktif' => true,
        ]);
        $placement = KelasSiswa::factory()
            ->for($student, 'siswa')
            ->for($class, 'kelas')
            ->for($year, 'tahunAjaran')
            ->create(['tanggal_mulai' => now()->subMonth()->toDateString(), 'tanggal_selesai' => null]);
        $category = PelanggaranKategori::factory()->create(['status_aktif' => true]);
        $violation = Pelanggaran::factory()->for($category, 'kategori')->create(['poin' => 7, 'status_aktif' => true]);

        return compact('user', 'teacher', 'student', 'class', 'year', 'placement', 'violation');
    }

    private function createRecord(array $context, array $attributes = []): PelanggaranSiswa
    {
        return PelanggaranSiswa::factory()
            ->for($context['placement'], 'penempatanKelas')
            ->for($context['violation'], 'pelanggaran')
            ->for($context['teacher'], 'guru')
            ->create(array_merge(['tanggal_pelanggaran' => today()->toDateString()], $attributes));
    }
}
