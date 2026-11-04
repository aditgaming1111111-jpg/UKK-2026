<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['nis', 'nisn', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'tahun_masuk', 'status_siswa'])]
class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasFactory;

    protected $table = 't_siswa';

    protected function casts(): array
    {
        return [
            'tahun_masuk' => 'integer',
            'tanggal_lahir' => 'date',
        ];
    }

    public function penempatanKelas(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'siswa_id');
    }

    public function catatanPelanggaran(): HasManyThrough
    {
        return $this->hasManyThrough(PelanggaranSiswa::class, KelasSiswa::class, 'siswa_id', 'kelas_siswa_id');
    }
}
