<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Guru::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'string', 'max:255', 'unique:t_guru,nip'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:t_guru,email', Rule::unique('t_users', 'email')],
            'password_login' => ['required', 'string', 'min:8', 'confirmed'],
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
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama Guru wajib diisi.',
            'email.required' => 'Email Guru wajib diisi.',
            'email.email' => 'Email Guru tidak valid.',
            'email.unique' => 'Email sudah digunakan pada data Guru atau akun pengguna.',
            'password_login.required' => 'Kata sandi wajib diisi.',
            'password_login.min' => 'Kata sandi minimal 8 karakter.',
            'password_login.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
