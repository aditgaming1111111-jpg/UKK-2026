<?php

namespace App\Models;

use Database\Factories\PelanggaranKategoriFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_kategori', 'deskripsi', 'status_aktif'])]
class PelanggaranKategori extends Model
{
    /** @use HasFactory<PelanggaranKategoriFactory> */
    use HasFactory;

    protected $table = 't_pelanggaran_kategori';

    protected function casts(): array
    {
        return ['status_aktif' => 'boolean'];
    }

    public function jenisPelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class, 'kategori_id');
    }
}
