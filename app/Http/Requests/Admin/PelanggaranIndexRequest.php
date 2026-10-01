<?php

namespace App\Http\Requests\Admin;

use App\Models\Pelanggaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PelanggaranIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Pelanggaran::class) ?? false;
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
            'kategori_id' => ['nullable', 'integer', 'exists:t_pelanggaran_kategori,id'],
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'status.in' => 'Filter status jenis pelanggaran tidak valid.',
        ];
    }
}
