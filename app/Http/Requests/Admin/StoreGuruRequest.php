<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => ['required', 'string', 'email', 'max:255'],
            'email_login' => ['required', 'string', 'email', 'max:255', 'unique:t_users,email'],
            'password_login' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nip' => trim((string) $this->input('nip', '')),
            'nama' => trim((string) $this->input('nama', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
            'email_login' => strtolower(trim((string) $this->input('email_login', ''))),
        ]);
    }

    public function messages(): array
    {
        return [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama Guru wajib diisi.',
            'email.required' => 'Email identitas Guru wajib diisi.',
            'email.email' => 'Email identitas Guru tidak valid.',
            'email_login.required' => 'Email login wajib diisi.',
            'email_login.email' => 'Email login tidak valid.',
            'email_login.unique' => 'Email login sudah digunakan.',
            'password_login.required' => 'Kata sandi wajib diisi.',
            'password_login.min' => 'Kata sandi minimal 8 karakter.',
            'password_login.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
