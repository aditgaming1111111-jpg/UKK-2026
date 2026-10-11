<?php

namespace App\Http\Requests;

use App\Models\KelasSiswa;
use App\Models\Pelanggaran;
use App\Models\PelanggaranSiswa;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdatePelanggaranSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('pelanggaranSiswa')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pelanggaran_id' => ['required', 'integer', Rule::exists('t_pelanggaran', 'id')],
            'tanggal_pelanggaran' => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:10000'],
            'tindakan' => ['nullable', 'string', 'max:10000'],
            'alasan' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['pelanggaran_id', 'tanggal_pelanggaran'])) {
                return;
            }

            /** @var PelanggaranSiswa $record */
            $record = $this->route('pelanggaranSiswa');
            $date = CarbonImmutable::parse($this->string('tanggal_pelanggaran')->toString())->startOfDay();
            $placement = KelasSiswa::query()->with('tahunAjaran')->find($record->kelas_siswa_id);

            if ($placement === null || $placement->tahunAjaran === null) {
                $validator->errors()->add('tanggal_pelanggaran', 'Penempatan kelas atau tahun ajaran catatan tidak tersedia.');

                return;
            }

            $year = $placement->tahunAjaran;
            $start = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
            $placementEnd = $placement->tanggal_selesai !== null
                ? CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()
                : CarbonImmutable::parse($year->tanggal_selesai)->startOfDay();
            $yearStart = CarbonImmutable::parse($year->tanggal_mulai)->startOfDay();
            $yearEnd = CarbonImmutable::parse($year->tanggal_selesai)->startOfDay();

            if ($date->lt($start) || $date->gt($placementEnd) || $date->lt($yearStart) || $date->gt($yearEnd)) {
                $validator->errors()->add('tanggal_pelanggaran', 'Tanggal kejadian harus berada pada periode kelas dan tahun ajaran catatan.');
            }

            $selectedViolationId = $this->integer('pelanggaran_id');

            if ($selectedViolationId !== $record->pelanggaran_id
                && ! Pelanggaran::availableForRecording()->whereKey($selectedViolationId)->exists()) {
                $validator->errors()->add('pelanggaran_id', 'Jenis pengganti harus aktif dan berada dalam kategori aktif.');
            }
        }];
    }

    protected function prepareForValidation(): void
    {
        foreach (['keterangan', 'tindakan', 'alasan'] as $field) {
            $this->merge([$field => $this->filled($field) ? trim((string) $this->input($field)) : null]);
        }
    }

    public function messages(): array
    {
        return [
            'pelanggaran_id.required' => 'Jenis pelanggaran wajib dipilih.',
            'pelanggaran_id.exists' => 'Jenis pelanggaran tidak ditemukan.',
            'tanggal_pelanggaran.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_pelanggaran.date' => 'Tanggal kejadian tidak valid.',
            'tanggal_pelanggaran.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
            'keterangan.max' => 'Keterangan maksimal 10.000 karakter.',
            'tindakan.max' => 'Tindakan maksimal 10.000 karakter.',
            'alasan.required' => 'Alasan koreksi wajib diisi.',
            'alasan.min' => 'Alasan koreksi minimal 3 karakter.',
            'alasan.max' => 'Alasan koreksi maksimal 1.000 karakter.',
        ];
    }
}
