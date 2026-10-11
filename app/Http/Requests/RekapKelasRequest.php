<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RekapKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'guru'
            && $this->user()->guru()->where('status_aktif', true)->exists();
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
            'q' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak ditemukan.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
        ];
    }
}
