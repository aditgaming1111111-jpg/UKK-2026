<?php

namespace App\Models;

use Database\Factories\StudentTransitionBatchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'batch_id',
    'tahun_ajaran_asal_id',
    'tahun_ajaran_tujuan_id',
    'jenis_proses',
    'status',
    'jumlah_diproses',
    'jumlah_dikecualikan',
    'user_id',
    'data_snapshot',
    'dibatalkan_oleh_user_id',
    'dibatalkan_pada',
])]
class StudentTransitionBatch extends Model
{
    /** @use HasFactory<StudentTransitionBatchFactory> */
    use HasFactory;

    public const STATUS_COMPLETED = 'selesai';

    public const STATUS_REVERTED = 'dibatalkan';

    protected $table = 't_proses_transisi_siswa';

    protected function casts(): array
    {
        return [
            'jumlah_diproses' => 'integer',
            'jumlah_dikecualikan' => 'integer',
            'data_snapshot' => 'array',
            'dibatalkan_pada' => 'datetime',
        ];
    }

    public function tahunAjaranAsal(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_asal_id');
    }

    public function tahunAjaranTujuan(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_tujuan_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembatal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh_user_id');
    }
}
