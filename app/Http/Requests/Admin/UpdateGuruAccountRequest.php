<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $guru = $this->route('guru');

        return $guru instanceof Guru
            && $guru->user_id !== null
            && ($this->user()?->can('manageAccount', $guru) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email_login' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('t_users', 'email')->ignore($this->route('guru')->user_id),
            ],
            'password_login' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email_login' => strtolower(trim((string) $this->input('email_login', '')))]);
    }

    public function messages(): array
    {
        return [
            'email_login.required' => 'Email login wajib diisi.',
            'email_login.email' => 'Email login tidak valid.',
            'email_login.unique' => 'Email login sudah digunakan.',
            'password_login.min' => 'Kata sandi minimal 8 karakter.',
            'password_login.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
