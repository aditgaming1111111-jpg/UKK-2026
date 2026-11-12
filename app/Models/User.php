<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Merepresentasikan akun pengguna, perannya, dan status akses beserta relasi datanya.
 */
#[Fillable(['nama', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 't_users';

    /**
     * Menentukan atribut model yang perlu dikonversi ke tipe data tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    /**
     * Memeriksa apakah peran akun termasuk peran aplikasi yang didukung.
     */
    public function hasSupportedRole(): bool
    {
        return in_array($this->role, ['admin', 'guru'], true);
    }

    /**
     * Menentukan status aktif akun berdasarkan peran dan status guru terkait.
     */
    public function isActiveAccount(): bool
    {
        return match ($this->role) {
            'admin' => true,
            'guru' => $this->guru()->where('status_aktif', true)->exists(),
            default => false,
        };
    }

    /**
     * Memetakan peran pengguna ke nama rute dashboard yang sesuai.
     */
    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard',
            default => throw new \LogicException('Role pengguna tidak dikenal.'),
        };
    }
}
