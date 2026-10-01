<?php

namespace App\Models;

use Database\Factories\GuruFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nip', 'nama', 'email', 'status_aktif', 'user_id'])]
class Guru extends Model
{
    /** @use HasFactory<GuruFactory> */
    use HasFactory;

    protected $table = 't_guru';

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function waliKelas(): HasMany
    {
        return $this->hasMany(WaliKelas::class, 'guru_id');
    }

    public function catatanPelanggaran(): HasMany
    {
        return $this->hasMany(PelanggaranSiswa::class, 'guru_id');
    }
}
