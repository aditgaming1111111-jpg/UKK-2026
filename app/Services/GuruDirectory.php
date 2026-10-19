<?php

namespace App\Services;

use App\Models\Guru;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class GuruDirectory
{
    /**
     * @param  array{q?: string|null, status?: string|null}  $filters
     * @return LengthAwarePaginator<int, Guru>
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        return Guru::query()
            ->with('user:id,nama,email,role')
            ->when($filters['q'] ?? null, static fn (Builder $query, string $search): Builder => $query
                ->where(static function (Builder $searchQuery) use ($search): void {
                    $searchQuery->where('nip', 'like', '%'.$search.'%')
                        ->orWhere('nama', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhereHas('user', static fn (Builder $userQuery): Builder => $userQuery
                            ->where('email', 'like', '%'.$search.'%'));
                }))
            ->when($filters['status'] ?? null, static fn (Builder $query, string $status): Builder => $query
                ->where('status_aktif', $status === 'aktif'))
            ->orderBy('nama')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();
    }
}
