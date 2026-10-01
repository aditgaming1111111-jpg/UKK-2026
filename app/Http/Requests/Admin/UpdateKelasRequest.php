<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('kelas')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'string', 'max:20'],
            'jurusan' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_kelas' => trim((string) $this->input('nama_kelas', '')),
            'tingkat' => trim((string) $this->input('tingkat', '')),
            'jurusan' => $this->filled('jurusan') ? trim((string) $this->input('jurusan')) : null,
        ]);
    }

    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.max' => 'Nama kelas maksimal 50 karakter.',
            'tingkat.required' => 'Tingkat wajib diisi.',
            'tingkat.max' => 'Tingkat maksimal 20 karakter.',
            'jurusan.max' => 'Jurusan maksimal 255 karakter.',
        ];
    }
}
