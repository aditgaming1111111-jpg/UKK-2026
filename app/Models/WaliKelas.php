<?php

namespace App\Models;

use Database\Factories\WaliKelasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['guru_id', 'kelas_id', 'tahun_ajaran_id', 'tanggal_mulai', 'tanggal_selesai', 'status_aktif'])]
class WaliKelas extends Model
{
    /** @use HasFactory<WaliKelasFactory> */
    use HasFactory;

    protected $table = 't_wali_kelas';

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'status_aktif' => 'boolean',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
