<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('guru')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'string', 'max:255', Rule::unique('t_guru', 'nip')->ignore($this->route('guru'))],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nip' => trim((string) $this->input('nip', '')),
            'nama' => trim((string) $this->input('nama', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
        ]);
    }

    public function messages(): array
    {
        return [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan Guru lain.',
            'nama.required' => 'Nama Guru wajib diisi.',
            'email.required' => 'Email identitas Guru wajib diisi.',
            'email.email' => 'Email identitas Guru tidak valid.',
        ];
    }
}
