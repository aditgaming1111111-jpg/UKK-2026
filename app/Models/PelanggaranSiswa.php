<?php

namespace App\Models;

use Database\Factories\PelanggaranSiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

#[Fillable(['kelas_siswa_id', 'pelanggaran_id', 'guru_id', 'tanggal_pelanggaran', 'tanggal_dicatat', 'poin_saat_dicatat', 'keterangan', 'tindakan', 'status'])]
class PelanggaranSiswa extends Model
{
    /** @use HasFactory<PelanggaranSiswaFactory> */
    use HasFactory;

    protected $table = 't_pelanggaran_siswa';

    protected function casts(): array
    {
        return [
            'tanggal_pelanggaran' => 'date',
            'tanggal_dicatat' => 'datetime',
            'poin_saat_dicatat' => 'integer',
        ];
    }

    public function penempatanKelas(): BelongsTo
    {
        return $this->belongsTo(KelasSiswa::class, 'kelas_siswa_id');
    }

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class, 'pelanggaran_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function logPerubahan(): HasMany
    {
        return $this->hasMany(LogPelanggaran::class, 'pelanggaran_siswa_id');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->role === 'admin') {
            return $query;
        }

        $guruId = $user->guru()->where('status_aktif', true)->value('id');

        if ($user->role !== 'guru' || $guruId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(static function (Builder $visibleQuery) use ($guruId): void {
            $visibleQuery->where('guru_id', $guruId)
                ->orWhereHas('penempatanKelas', static fn (Builder $placementQuery): Builder => $placementQuery
                    ->whereExists(static function (QueryBuilder $assignmentQuery) use ($guruId): void {
                        $assignmentQuery->selectRaw('1')
                            ->from('t_wali_kelas')
                            ->whereColumn('t_wali_kelas.kelas_id', 't_kelas_siswa.kelas_id')
                            ->whereColumn('t_wali_kelas.tahun_ajaran_id', 't_kelas_siswa.tahun_ajaran_id')
                            ->where('t_wali_kelas.guru_id', $guruId)
                            ->where('t_wali_kelas.status_aktif', true)
                            ->whereColumn('t_wali_kelas.tanggal_mulai', '<=', 't_pelanggaran_siswa.tanggal_pelanggaran')
                            ->where(static function (QueryBuilder $periodQuery): void {
                                $periodQuery->whereNull('t_wali_kelas.tanggal_selesai')
                                    ->orWhereColumn('t_wali_kelas.tanggal_selesai', '>=', 't_pelanggaran_siswa.tanggal_pelanggaran');
                            });
                    }));
        });
    }
}
