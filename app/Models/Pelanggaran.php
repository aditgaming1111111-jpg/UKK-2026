<?php

namespace App\Models;

use Database\Factories\PelanggaranFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kategori_id', 'kode', 'nama_pelanggaran', 'deskripsi', 'poin', 'status_aktif'])]
class Pelanggaran extends Model
{
    /** @use HasFactory<PelanggaranFactory> */
    use HasFactory;

    protected $table = 't_pelanggaran';

    protected function casts(): array
    {
        return [
            'poin' => 'integer',
            'status_aktif' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(PelanggaranKategori::class, 'kategori_id');
    }

    public function catatanSiswa(): HasMany
    {
        return $this->hasMany(PelanggaranSiswa::class, 'pelanggaran_id');
    }

    public function scopeAvailableForRecording(Builder $query): Builder
    {
        return $query->where('status_aktif', true)
            ->whereHas('kategori', static fn (Builder $categoryQuery): Builder => $categoryQuery->where('status_aktif', true));
    }
}
