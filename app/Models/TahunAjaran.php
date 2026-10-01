<?php

namespace App\Models;

use Database\Factories\TahunAjaranFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai', 'status_aktif'])]
class TahunAjaran extends Model
{
    /** @use HasFactory<TahunAjaranFactory> */
    use HasFactory;

    protected $table = 't_tahun_ajaran';

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'status_aktif' => 'boolean',
        ];
    }

    public function penempatanSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'tahun_ajaran_id');
    }

    public function penugasanWaliKelas(): HasMany
    {
        return $this->hasMany(WaliKelas::class, 'tahun_ajaran_id');
    }
}
