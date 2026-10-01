<?php

namespace App\Http\Requests\Admin;

use App\Models\PelanggaranKategori;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PelanggaranKategoriIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', PelanggaranKategori::class) ?? false;
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
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    public function messages(): array
    {
        return ['q.max' => 'Pencarian maksimal 100 karakter.', 'status.in' => 'Filter status kategori tidak valid.'];
    }
}
