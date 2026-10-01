<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelanggaranKategoriRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('pelanggaranKategori')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kategori' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['nama_kategori' => trim((string) $this->input('nama_kategori', ''))]);
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi kategori maksimal 10.000 karakter.',
        ];
    }
}
