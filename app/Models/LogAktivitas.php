<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'aktivitas', 'deskripsi', 'ip_address', 'user_agent'])]
class LogAktivitas extends Model
{
    public const UPDATED_AT = null;

    protected $table = 't_log_aktivitas';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
