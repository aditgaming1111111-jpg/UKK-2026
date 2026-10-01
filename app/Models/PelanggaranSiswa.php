<?php

namespace App\Models;

use Database\Factories\PelanggaranSiswaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
