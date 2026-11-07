<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentTransitionService
{
    public const MODE_PROMOTE = 'naik';

    public const MODE_REPEAT = 'tinggal';

    public const MODE_GRADUATE = 'lulus';

    public function __construct(private ActivityLogger $activityLogger) {}

    /**
     * @return array{items: Collection<int, array{student: Siswa, placement: KelasSiswa, source_class: Kelas, eligible: bool, reason: string|null}>, source_classes: Collection<int, array{class: Kelas, total_count: int, eligible_count: int, target_classes: Collection<int, Kelas>}>}
     */
    public function preview(TahunAjaran $originYear, ?TahunAjaran $targetYear, string $mode): array
    {
        if ($mode !== self::MODE_GRADUATE && $targetYear !== null) {
            $this->ensureTargetYearFollowsOrigin($originYear, $targetYear);
        }

        $originEnd = CarbonImmutable::parse($originYear->tanggal_selesai)->startOfDay();
        $originStart = CarbonImmutable::parse($originYear->tanggal_mulai)->startOfDay();
        $placements = KelasSiswa::query()
            ->with(['siswa:id,nisn,nama,status_siswa', 'kelas:id,nama_kelas,tingkat,jurusan'])
            ->where('tahun_ajaran_id', $originYear->id)
            ->whereDate('tanggal_mulai', '<=', $originEnd->toDateString())
            ->where(static function (Builder $query) use ($originStart): void {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $originStart->toDateString());
            })
            ->orderBy('siswa_id')
            ->orderBy('tanggal_mulai')
            ->orderBy('id')
            ->get();

        $placementsByStudent = $placements->groupBy('siswa_id');
        $existingTargetPlacementIds = $targetYear === null
            ? []
            : KelasSiswa::query()
                ->where('tahun_ajaran_id', $targetYear->id)
                ->whereIn('siswa_id', $placementsByStudent->keys())
                ->pluck('siswa_id')
                ->map(static fn ($studentId): int => (int) $studentId)
                ->all();

        $items = $placementsByStudent->map(function (Collection $studentPlacements) use (
            $mode,
            $originEnd,
            $existingTargetPlacementIds,
        ): ?array {
            $student = $studentPlacements->first()->siswa;

            if (! $student instanceof Siswa) {
                return null;
            }

            $effectiveAtYearEnd = $studentPlacements->filter(static function (KelasSiswa $placement) use ($originEnd): bool {
                $startDate = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
                $endDate = $placement->tanggal_selesai !== null
                    ? CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()
                    : null;

                return $startDate->lte($originEnd) && ($endDate === null || $endDate->gte($originEnd));
            });

            $placement = $effectiveAtYearEnd->count() === 1
                ? $effectiveAtYearEnd->first()
                : $studentPlacements->sortByDesc(static fn (KelasSiswa $item): string => $item->tanggal_mulai->toDateString().'|'.str_pad((string) $item->id, 12, '0', STR_PAD_LEFT))->first();
            $sourceClass = $placement->kelas;
            $reason = null;

            if ($student->status_siswa !== 'aktif') {
                $reason = match ($student->status_siswa) {
                    'lulus' => 'Siswa sudah lulus.',
                    'pindah' => 'Siswa berstatus pindah.',
                    'keluar' => 'Siswa berstatus keluar.',
                    'nonaktif' => 'Siswa berstatus nonaktif.',
                    default => 'Status siswa tidak dapat diproses otomatis.',
                };
            } elseif ($effectiveAtYearEnd->isEmpty()) {
                $reason = 'Tidak ada penempatan yang berlaku pada akhir tahun ajaran asal.';
            } elseif ($effectiveAtYearEnd->count() > 1) {
                $reason = 'Ada lebih dari satu penempatan yang berlaku pada akhir tahun ajaran asal.';
            } elseif (! $placement->status_aktif && $placement->tanggal_selesai === null) {
                $reason = 'Status penempatan tidak konsisten; periksa tanggal selesai terlebih dahulu.';
            } elseif (in_array($student->id, $existingTargetPlacementIds, true)) {
                $reason = 'Siswa sudah memiliki penempatan pada tahun ajaran tujuan.';
            } elseif (! $sourceClass instanceof Kelas) {
                $reason = 'Kelas asal pada penempatan tidak ditemukan.';
            } elseif ($mode === self::MODE_GRADUATE && ! $this->isGradeTwelve($sourceClass)) {
                $reason = 'Kelulusan massal hanya mencakup siswa kelas XII.';
            } elseif ($mode === self::MODE_PROMOTE && $this->gradeNumber($sourceClass->tingkat) === 12) {
                $reason = 'Siswa kelas XII harus diproses melalui kelulusan atau tinggal kelas.';
            } elseif ($mode !== self::MODE_GRADUATE && $this->gradeNumber($sourceClass->tingkat) === null) {
                $reason = 'Tingkat kelas asal tidak dikenali untuk proses otomatis.';
            }

            return [
                'student' => $student,
                'placement' => $placement,
                'source_class' => $sourceClass,
                'eligible' => $reason === null,
                'reason' => $reason,
            ];
        })->filter()->values();

        $sourceClasses = $items
            ->filter(static fn (array $item): bool => $item['source_class'] instanceof Kelas)
            ->groupBy(static fn (array $item): int => $item['source_class']->id)
            ->map(function (Collection $classItems) use ($mode): array {
                /** @var Kelas $sourceClass */
                $sourceClass = $classItems->first()['source_class'];
                $eligibleCount = $classItems->where('eligible', true)->count();
                $targetLevel = $this->targetGradeNumber($sourceClass, $mode);
                $targetClasses = $targetLevel === null
                    ? collect()
                    : Kelas::query()
                        ->whereIn('tingkat', $this->levelAliases($targetLevel))
                        ->orderBy('nama_kelas')
                        ->get(['id', 'nama_kelas', 'tingkat', 'jurusan']);

                return [
                    'class' => $sourceClass,
                    'total_count' => $classItems->count(),
                    'eligible_count' => $eligibleCount,
                    'target_classes' => $targetClasses,
                ];
            })
            ->sortBy(static fn (array $summary): string => $summary['class']->tingkat.'|'.$summary['class']->nama_kelas)
            ->values();

        return ['items' => $items, 'source_classes' => $sourceClasses];
    }

    /**
     * @param  array<int|string, int|string>  $classMapping
     * @param  list<int|string>  $excludedStudentIds
     * @return array{processed: int, excluded: int, batch_id: string}
     */
    public function process(
        TahunAjaran $originYear,
        ?TahunAjaran $targetYear,
        string $mode,
        array $classMapping,
        array $excludedStudentIds,
        User $actor,
        Request $request,
    ): array {
        return DB::transaction(function () use (
            $originYear,
            $targetYear,
            $mode,
            $classMapping,
            $excludedStudentIds,
            $actor,
            $request,
        ): array {
            $yearIds = array_values(array_filter([$originYear->id, $targetYear?->id]));
            $lockedYears = TahunAjaran::query()
                ->whereKey($yearIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $lockedOrigin = $lockedYears->get($originYear->id);
            $lockedTarget = $targetYear === null ? null : $lockedYears->get($targetYear->id);

            if (! $lockedOrigin instanceof TahunAjaran || ($targetYear !== null && ! $lockedTarget instanceof TahunAjaran)) {
                throw ValidationException::withMessages(['tahun_ajaran_tujuan_id' => 'Tahun ajaran asal atau tujuan tidak ditemukan.']);
            }

            $originEnd = CarbonImmutable::parse($lockedOrigin->tanggal_selesai)->startOfDay();

            if ($originEnd->gt(today())) {
                throw ValidationException::withMessages([
                    'mode' => 'Proses hanya dapat dijalankan setelah tahun ajaran asal selesai.',
                ]);
            }

            if ($mode !== self::MODE_GRADUATE) {
                if (! $lockedTarget instanceof TahunAjaran) {
                    throw ValidationException::withMessages(['tahun_ajaran_tujuan_id' => 'Tahun ajaran tujuan wajib dipilih.']);
                }

                $this->ensureTargetYearFollowsOrigin($lockedOrigin, $lockedTarget);
            }

            $preview = $this->preview($lockedOrigin, $lockedTarget, $mode);
            $eligibleItems = $preview['items']->where('eligible', true);

            if ($eligibleItems->isEmpty()) {
                throw ValidationException::withMessages(['siswa' => 'Tidak ada siswa yang memenuhi syarat untuk proses ini.']);
            }

            $eligibleStudentIds = $eligibleItems->map(static fn (array $item): int => $item['student']->id)->all();
            $excludedIds = array_values(array_unique(array_map('intval', $excludedStudentIds)));
            $unknownExcludedIds = array_diff($excludedIds, $eligibleStudentIds);

            if ($unknownExcludedIds !== []) {
                throw ValidationException::withMessages(['dikecualikan' => 'Daftar pengecualian berisi siswa yang bukan calon proses yang memenuhi syarat. Muat ulang pratinjau.']);
            }

            $selectedItems = $eligibleItems
                ->reject(static fn (array $item): bool => in_array($item['student']->id, $excludedIds, true))
                ->values();

            if ($selectedItems->isEmpty()) {
                throw ValidationException::withMessages(['dikecualikan' => 'Pilih setidaknya satu siswa untuk diproses.']);
            }

            $mappings = $mode === self::MODE_GRADUATE
                ? []
                : $this->validateClassMappings($selectedItems, $preview['source_classes'], $classMapping, $mode);
            $studentIds = $selectedItems->map(static fn (array $item): int => $item['student']->id)->all();
            $placementIds = $selectedItems->map(static fn (array $item): int => $item['placement']->id)->all();
            $students = Siswa::query()->whereKey($studentIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $placements = KelasSiswa::query()->whereKey($placementIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            if ($students->count() !== count($studentIds) || $placements->count() !== count($placementIds)) {
                throw ValidationException::withMessages(['siswa' => 'Data siswa atau penempatan berubah. Muat ulang pratinjau sebelum mencoba kembali.']);
            }

            if ($lockedTarget instanceof TahunAjaran) {
                $hasTargetPlacements = KelasSiswa::query()
                    ->where('tahun_ajaran_id', $lockedTarget->id)
                    ->whereIn('siswa_id', $studentIds)
                    ->exists();

                if ($hasTargetPlacements) {
                    throw ValidationException::withMessages(['siswa' => 'Sebagian siswa sudah memiliki penempatan pada tahun tujuan. Tidak ada perubahan yang disimpan.']);
                }

                $sourcePlacementIds = $selectedItems->map(static fn (array $item): int => $item['placement']->id)->all();
                $hasConflictingFuturePlacement = KelasSiswa::query()
                    ->whereIn('siswa_id', $studentIds)
                    ->whereNotIn('id', $sourcePlacementIds)
                    ->where(static function (Builder $query) use ($lockedTarget): void {
                        $query->whereNull('tanggal_selesai')
                            ->orWhereDate('tanggal_selesai', '>=', $lockedTarget->tanggal_mulai->toDateString());
                    })
                    ->exists();

                if ($hasConflictingFuturePlacement) {
                    throw ValidationException::withMessages(['siswa' => 'Sebagian siswa memiliki penempatan lain yang bertabrakan dengan tanggal mulai tahun tujuan. Tidak ada perubahan yang disimpan.']);
                }
            }

            $batchId = (string) Str::uuid();
            $activity = match ($mode) {
                self::MODE_PROMOTE => 'Kenaikan kelas massal',
                self::MODE_REPEAT => 'Proses tinggal kelas massal',
                self::MODE_GRADUATE => 'Kelulusan massal',
            };

            foreach ($selectedItems as $item) {
                /** @var Siswa $student */
                $student = $students->get($item['student']->id);
                /** @var KelasSiswa $placement */
                $placement = $placements->get($item['placement']->id);
                $sourceClass = $item['source_class'];

                if ($student->status_siswa !== 'aktif'
                    || $placement->siswa_id !== $student->id
                    || $placement->tahun_ajaran_id !== $lockedOrigin->id
                    || $placement->kelas_id !== $sourceClass->id
                    || CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay()->gt($originEnd)
                    || ($placement->tanggal_selesai !== null
                        && CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()->lt($originEnd))) {
                    throw ValidationException::withMessages(['siswa' => 'Status atau penempatan siswa berubah setelah pratinjau. Tidak ada perubahan yang disimpan.']);
                }

                $hasLateViolation = $placement->catatanPelanggaran()
                    ->whereDate('tanggal_pelanggaran', '>', $originEnd->toDateString())
                    ->exists();

                if ($hasLateViolation) {
                    throw ValidationException::withMessages(['siswa' => 'Ada catatan pelanggaran setelah akhir tahun asal yang terkait dengan penempatan. Tidak ada perubahan yang disimpan.']);
                }

                $placement->update([
                    'tanggal_selesai' => $originEnd->toDateString(),
                    'status_aktif' => false,
                ]);

                $targetClass = null;

                if ($lockedTarget instanceof TahunAjaran) {
                    $targetClass = $mappings[$sourceClass->id];
                    KelasSiswa::query()->create([
                        'siswa_id' => $student->id,
                        'kelas_id' => $targetClass->id,
                        'tahun_ajaran_id' => $lockedTarget->id,
                        'tanggal_mulai' => $lockedTarget->tanggal_mulai->toDateString(),
                        'tanggal_selesai' => null,
                        'status_aktif' => true,
                    ]);
                } else {
                    $student->update(['status_siswa' => 'lulus']);
                }

                $targetClassDescription = $targetClass === null
                    ? 'tidak ada (lulus)'
                    : sprintf('ID #%d (%s)', $targetClass->id, $targetClass->nama_kelas);
                $targetYearDescription = $lockedTarget === null
                    ? 'tidak ada (lulus)'
                    : sprintf('ID #%d (%s)', $lockedTarget->id, $lockedTarget->nama_tahun_ajaran);
                $description = sprintf(
                    'Batch %s; tahun asal ID #%d (%s); tahun tujuan %s; siswa ID #%d, NISN %s, nama %s; kelas asal ID #%d (%s); kelas tujuan %s; jenis proses %s.',
                    $batchId,
                    $lockedOrigin->id,
                    $lockedOrigin->nama_tahun_ajaran,
                    $targetYearDescription,
                    $student->id,
                    $student->nisn,
                    $student->nama,
                    $sourceClass->id,
                    $sourceClass->nama_kelas,
                    $targetClassDescription,
                    $activity,
                );

                $this->activityLogger->record($actor, $request, $activity, $description);
            }

            return [
                'processed' => $selectedItems->count(),
                'excluded' => count($excludedIds),
                'batch_id' => $batchId,
            ];
        });
    }

    private function ensureTargetYearFollowsOrigin(TahunAjaran $originYear, TahunAjaran $targetYear): void
    {
        $originEnd = CarbonImmutable::parse($originYear->tanggal_selesai)->startOfDay();
        $targetStart = CarbonImmutable::parse($targetYear->tanggal_mulai)->startOfDay();

        if ($originYear->id === $targetYear->id || $targetStart->lte($originEnd)) {
            throw ValidationException::withMessages([
                'tahun_ajaran_tujuan_id' => 'Tahun ajaran tujuan harus berbeda dan dimulai setelah tahun ajaran asal berakhir.',
            ]);
        }
    }

    /**
     * @param  Collection<int, array{student: Siswa, placement: KelasSiswa, source_class: Kelas, eligible: bool, reason: string|null}>  $selectedItems
     * @param  Collection<int, array{class: Kelas, total_count: int, eligible_count: int, target_classes: Collection<int, Kelas>}>  $sourceClasses
     * @param  array<int|string, int|string>  $classMapping
     * @return array<int, Kelas>
     */
    private function validateClassMappings(
        Collection $selectedItems,
        Collection $sourceClasses,
        array $classMapping,
        string $mode,
    ): array {
        $sourceClassIds = $selectedItems
            ->map(static fn (array $item): int => $item['source_class']->id)
            ->unique()
            ->values();
        $unknownMappingIds = array_diff(array_map('intval', array_keys($classMapping)), $sourceClassIds->all());

        if ($unknownMappingIds !== []) {
            throw ValidationException::withMessages(['pemetaan' => 'Pemetaan memuat kelas asal yang tidak termasuk dalam pratinjau. Muat ulang halaman.']);
        }

        $targetIds = [];
        foreach ($sourceClassIds as $sourceClassId) {
            if (! array_key_exists($sourceClassId, $classMapping)) {
                throw ValidationException::withMessages(['pemetaan.'.$sourceClassId => 'Pilih kelas tujuan untuk kelas asal ini.']);
            }

            $targetIds[$sourceClassId] = (int) $classMapping[$sourceClassId];
        }

        $targetClasses = Kelas::query()->whereKey(array_values($targetIds))->get(['id', 'nama_kelas', 'tingkat', 'jurusan'])->keyBy('id');
        $sourceClassSummaries = $sourceClasses->keyBy(static fn (array $summary): int => $summary['class']->id);

        $mapping = [];
        foreach ($targetIds as $sourceClassId => $targetId) {
            $sourceClass = $sourceClassSummaries->get($sourceClassId)['class'] ?? null;
            $targetClass = $targetClasses->get($targetId);

            if (! $sourceClass instanceof Kelas || ! $targetClass instanceof Kelas) {
                throw ValidationException::withMessages(['pemetaan.'.$sourceClassId => 'Kelas asal atau tujuan tidak ditemukan.']);
            }

            $expectedLevel = $this->targetGradeNumber($sourceClass, $mode);

            if ($expectedLevel === null || $this->gradeNumber($targetClass->tingkat) !== $expectedLevel) {
                $expectedLabel = $mode === self::MODE_PROMOTE ? 'satu tingkat di atas' : 'tingkat yang sama';
                throw ValidationException::withMessages([
                    'pemetaan.'.$sourceClassId => 'Kelas tujuan harus berada pada '.$expectedLabel.' kelas asal.',
                ]);
            }

            $mapping[$sourceClassId] = $targetClass;
        }

        return $mapping;
    }

    private function targetGradeNumber(Kelas $sourceClass, string $mode): ?int
    {
        $sourceLevel = $this->gradeNumber($sourceClass->tingkat);

        if ($sourceLevel === null) {
            return null;
        }

        return match ($mode) {
            self::MODE_PROMOTE => $sourceLevel < 12 ? $sourceLevel + 1 : null,
            self::MODE_REPEAT => $sourceLevel,
            default => null,
        };
    }

    /** @return list<string> */
    private function levelAliases(int $level): array
    {
        return match ($level) {
            10 => ['10', 'X'],
            11 => ['11', 'XI'],
            12 => ['12', 'XII'],
            default => [(string) $level],
        };
    }

    private function gradeNumber(string $level): ?int
    {
        return match (mb_strtoupper(trim($level))) {
            '10', 'X' => 10,
            '11', 'XI' => 11,
            '12', 'XII' => 12,
            default => null,
        };
    }

    private function isGradeTwelve(Kelas $kelas): bool
    {
        return $this->gradeNumber($kelas->tingkat) === 12;
    }
}
