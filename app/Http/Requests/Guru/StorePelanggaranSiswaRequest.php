<?php

namespace App\Http\Requests\Guru;

use App\Models\KelasSiswa;
use App\Models\Pelanggaran;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePelanggaranSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'guru';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kelas_siswa_id' => ['required', 'integer', 'exists:t_kelas_siswa,id'],
            'pelanggaran_id' => ['required', 'integer', Rule::exists('t_pelanggaran', 'id')],
            'tanggal_pelanggaran' => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:10000'],
            'tindakan' => ['nullable', 'string', 'max:10000'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['kelas_siswa_id', 'pelanggaran_id', 'tanggal_pelanggaran'])) {
                return;
            }

            $incidentDate = CarbonImmutable::parse($this->string('tanggal_pelanggaran')->toString())->startOfDay();
            $placement = KelasSiswa::query()
                ->with('tahunAjaran:id,tanggal_mulai,tanggal_selesai')
                ->find($this->integer('kelas_siswa_id'));

            if ($placement === null || $placement->tahunAjaran === null) {
                $validator->errors()->add('kelas_siswa_id', 'Penempatan kelas siswa tidak ditemukan.');

                return;
            }

            $academicYear = $placement->tahunAjaran;
            $placementStart = CarbonImmutable::parse($placement->tanggal_mulai)->startOfDay();
            $placementEnd = $placement->tanggal_selesai !== null
                ? CarbonImmutable::parse($placement->tanggal_selesai)->startOfDay()
                : CarbonImmutable::parse($academicYear->tanggal_selesai)->startOfDay();
            $yearStart = CarbonImmutable::parse($academicYear->tanggal_mulai)->startOfDay();
            $yearEnd = CarbonImmutable::parse($academicYear->tanggal_selesai)->startOfDay();

            if ($incidentDate->lt($placementStart) || $incidentDate->gt($placementEnd)
                || $incidentDate->lt($yearStart) || $incidentDate->gt($yearEnd)) {
                $validator->errors()->add('kelas_siswa_id', 'Penempatan kelas dan tahun ajaran tidak berlaku pada tanggal kejadian. Pilih siswa atau tanggal lain.');
            }

            if (! Pelanggaran::availableForRecording()->whereKey($this->integer('pelanggaran_id'))->exists()) {
                $validator->errors()->add('pelanggaran_id', 'Jenis pelanggaran tidak ditemukan atau sudah nonaktif.');
            }
        }];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'keterangan' => $this->filled('keterangan') ? trim((string) $this->input('keterangan')) : null,
            'tindakan' => $this->filled('tindakan') ? trim((string) $this->input('tindakan')) : null,
        ]);
    }

    public function messages(): array
    {
        return [
            'kelas_siswa_id.required' => 'Pilih siswa beserta penempatan kelas yang berlaku saat kejadian.',
            'kelas_siswa_id.exists' => 'Penempatan kelas siswa tidak ditemukan.',
            'pelanggaran_id.required' => 'Pilih jenis pelanggaran aktif.',
            'pelanggaran_id.exists' => 'Jenis pelanggaran tidak ditemukan.',
            'tanggal_pelanggaran.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_pelanggaran.date' => 'Tanggal kejadian tidak valid.',
            'tanggal_pelanggaran.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
            'keterangan.max' => 'Keterangan maksimal 10.000 karakter.',
            'tindakan.max' => 'Tindakan maksimal 10.000 karakter.',
        ];
    }
}
