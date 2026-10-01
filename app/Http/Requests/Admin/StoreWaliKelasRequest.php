<?php

namespace App\Http\Requests\Admin;

use App\Models\WaliKelas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWaliKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', WaliKelas::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guru_id' => [
                'required', 'integer', Rule::exists('t_guru', 'id')->where(static fn ($query) => $query->where('status_aktif', true)),
            ],
            'kelas_id' => ['required', 'integer', 'exists:t_kelas,id'],
            'tahun_ajaran_id' => ['required', 'integer', 'exists:t_tahun_ajaran,id'],
            'tanggal_mulai' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'guru_id.required' => 'Guru wajib dipilih.',
            'guru_id.exists' => 'Guru tidak ditemukan atau berstatus nonaktif.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
        ];
    }
}
