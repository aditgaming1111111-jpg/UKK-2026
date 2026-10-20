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
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('t_guru', 'email')->ignore($this->route('guru')),
                Rule::unique('t_users', 'email')->ignore($this->route('guru')->user_id),
            ],
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
            'email.required' => 'Email Guru wajib diisi.',
            'email.email' => 'Email Guru tidak valid.',
            'email.unique' => 'Email sudah digunakan pada data Guru atau akun pengguna lain.',
        ];
    }
}
