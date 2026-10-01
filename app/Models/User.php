<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 't_users';

    /**
     * Get the attributes that should be cast.
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

    public function hasSupportedRole(): bool
    {
        return in_array($this->role, ['admin', 'guru'], true);
    }

    public function isActiveAccount(): bool
    {
        return match ($this->role) {
            'admin' => true,
            'guru' => $this->guru()->where('status_aktif', true)->exists(),
            default => false,
        };
    }

    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard',
            default => throw new \LogicException('Role pengguna tidak dikenal.'),
        };
    }
}
