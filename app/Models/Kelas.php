<?php

namespace App\Models;

use Database\Factories\KelasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_kelas', 'tingkat', 'jurusan'])]
class Kelas extends Model
{
    /** @use HasFactory<KelasFactory> */
    use HasFactory;

    protected $table = 't_kelas';

    public function penempatanSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class, 'kelas_id');
    }

    public function penugasanWaliKelas(): HasMany
    {
        return $this->hasMany(WaliKelas::class, 'kelas_id');
    }
}
