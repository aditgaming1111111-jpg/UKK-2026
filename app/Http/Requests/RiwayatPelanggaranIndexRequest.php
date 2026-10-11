<?php

namespace App\Http\Requests;

use App\Models\PelanggaranSiswa;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RiwayatPelanggaranIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', PelanggaranSiswa::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'tahun_ajaran_id' => ['nullable', 'integer', Rule::exists('t_tahun_ajaran', 'id')],
            'kelas_id' => ['nullable', 'integer', Rule::exists('t_kelas', 'id')],
            'pelanggaran_id' => ['nullable', 'integer', Rule::exists('t_pelanggaran', 'id')],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'guru_id' => ['nullable', 'integer', Rule::exists('t_guru', 'id')],
            'status' => ['nullable', Rule::in(['aktif', 'dibatalkan'])],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['tanggal_mulai', 'tanggal_selesai'])) {
                return;
            }

            $start = $this->date('tanggal_mulai');
            $end = $this->date('tanggal_selesai');

            if ($start !== null && $end !== null && $end->lt($start)) {
                $validator->errors()->add('tanggal_selesai', 'Tanggal akhir harus sama atau setelah tanggal mulai.');
            }
        }];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'pelanggaran_id.exists' => 'Jenis pelanggaran yang dipilih tidak ditemukan.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Tanggal akhir tidak valid.',
            'guru_id.exists' => 'Guru pencatat tidak ditemukan.',
            'status.in' => 'Status catatan tidak valid.',
        ];
    }
}
