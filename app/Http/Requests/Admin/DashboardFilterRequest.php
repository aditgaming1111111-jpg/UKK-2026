<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DashboardFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['nullable', 'integer', 'exists:t_tahun_ajaran,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun_ajaran_id.integer' => 'Tahun ajaran yang dipilih tidak valid.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
        ];
    }
}
