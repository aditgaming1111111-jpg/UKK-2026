<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class LaporanIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['nullable', 'integer', Rule::exists('t_tahun_ajaran', 'id')],
            'kelas_id' => ['nullable', 'integer', Rule::exists('t_kelas', 'id')],
            'q' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'pelanggaran_id' => ['nullable', 'integer', Rule::exists('t_pelanggaran', 'id')],
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
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak ditemukan.',
            'kelas_id.exists' => 'Kelas tidak ditemukan.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tanggal_mulai.date' => 'Tanggal mulai kejadian tidak valid.',
            'tanggal_selesai.date' => 'Tanggal akhir kejadian tidak valid.',
            'pelanggaran_id.exists' => 'Jenis pelanggaran tidak ditemukan.',
            'status.in' => 'Status catatan tidak valid.',
        ];
    }
}
