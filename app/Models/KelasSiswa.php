<?php

namespace App\Models;

use Database\Factories\KelasSiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['siswa_id', 'kelas_id', 'tahun_ajaran_id', 'tanggal_mulai', 'tanggal_selesai', 'status_aktif'])]
class KelasSiswa extends Model
{
    /** @use HasFactory<KelasSiswaFactory> */
    use HasFactory;

    protected $table = 't_kelas_siswa';

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'status_aktif' => 'boolean',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function catatanPelanggaran(): HasMany
    {
        return $this->hasMany(PelanggaranSiswa::class, 'kelas_siswa_id');
    }
}
