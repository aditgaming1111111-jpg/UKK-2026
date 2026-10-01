<?php

namespace App\Models;

use Database\Factories\LogPelanggaranFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pelanggaran_siswa_id', 'user_id', 'aksi', 'data_sebelum', 'data_sesudah', 'alasan'])]
class LogPelanggaran extends Model
{
    /** @use HasFactory<LogPelanggaranFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    protected $table = 't_log_pelanggaran';

    public function catatanPelanggaran(): BelongsTo
    {
        return $this->belongsTo(PelanggaranSiswa::class, 'pelanggaran_siswa_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
