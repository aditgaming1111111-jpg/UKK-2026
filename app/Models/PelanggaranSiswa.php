<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\PelanggaranSiswaFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kelas_siswa_id', 'pelanggaran_id', 'guru_id', 'tanggal_pelanggaran', 'tanggal_dicatat', 'poin_saat_dicatat', 'keterangan', 'tindakan', 'status', 'idempotency_key'])]
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

    /**
     * Menyimpan waktu pencatatan dalam UTC dan menampilkannya menurut zona waktu aplikasi.
     */
    protected function tanggalDicatat(): Attribute
    {
        return Attribute::make(
            get: static fn (?string $value): ?CarbonImmutable => $value === null
                ? null
                : CarbonImmutable::parse($value, 'UTC')->setTimezone(config('app.timezone')),
            set: static function (DateTimeInterface|string|null $value): ?string {
                if ($value === null) {
                    return null;
                }

                $dateTime = $value instanceof DateTimeInterface
                    ? CarbonImmutable::instance($value)
                    : CarbonImmutable::parse($value, config('app.timezone'));

                return $dateTime->utc()->format('Y-m-d H:i:s');
            },
        );
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
        if (in_array($user->role, ['admin', 'guru'], true)) {
            return $query;
        }

        return $query->whereRaw('1 = 0');
    }
}
