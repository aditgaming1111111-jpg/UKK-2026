<?php

namespace App\Http\Requests\Admin;

use App\Models\WaliKelas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WaliKelasIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', WaliKelas::class) ?? false;
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
            'kelas_id' => ['nullable', 'integer', 'exists:t_kelas,id'],
            'tahun_ajaran_id' => ['nullable', 'integer', 'exists:t_tahun_ajaran,id'],
            'status' => ['nullable', Rule::in(['aktif', 'selesai'])],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
            'status.in' => 'Status penugasan tidak valid.',
        ];
    }
}
