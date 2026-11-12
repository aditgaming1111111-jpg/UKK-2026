<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Merepresentasikan siswa beserta relasi dan aturan datanya.
 */
#[Fillable(['nis', 'nisn', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'tahun_masuk', 'status_siswa'])]
class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasFactory;

    protected $table = 't_siswa';

    protected function casts(): array
    {
        return [
            'tahun_masuk' => 'integer',
            'tanggal_lahir' => 'date',
        ];
    }

    public function penempatanKelas(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'siswa_id');
    }

    public function catatanPelanggaran(): HasManyThrough
    {
        return $this->hasManyThrough(PelanggaranSiswa::class, KelasSiswa::class, 'siswa_id', 'kelas_siswa_id');
    }

    /**
     * Memeriksa apakah siswa telah menyelesaikan penempatan pada tingkat XII.
     */
    public function hasCompletedGradeTwelvePlacement(): bool
    {
        return $this->penempatanKelas()
            ->whereHas('kelas', static fn (Builder $query): Builder => $query->whereIn('tingkat', ['12', 'XII']))
            ->whereHas('tahunAjaran', static fn (Builder $query): Builder => $query
                ->whereDate('tanggal_selesai', '<=', today())
                ->whereColumn('t_kelas_siswa.tanggal_mulai', '>=', 't_tahun_ajaran.tanggal_mulai')
                ->whereColumn('t_kelas_siswa.tanggal_mulai', '<=', 't_tahun_ajaran.tanggal_selesai')
                ->where(static function (Builder $query): void {
                    $query->where(static fn (Builder $placementQuery): Builder => $placementQuery
                        ->where('t_kelas_siswa.status_aktif', true)
                        ->whereNull('t_kelas_siswa.tanggal_selesai'))
                        ->orWhere(static fn (Builder $placementQuery): Builder => $placementQuery
                            ->where('t_kelas_siswa.status_aktif', false)
                            ->whereNotNull('t_kelas_siswa.tanggal_selesai')
                            ->whereColumn('t_kelas_siswa.tanggal_selesai', '>=', 't_tahun_ajaran.tanggal_selesai'));
                }))
            ->exists();
    }
}
