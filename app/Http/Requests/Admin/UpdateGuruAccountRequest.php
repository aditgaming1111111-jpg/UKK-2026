<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;

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
            'password_login' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password_login.min' => 'Kata sandi minimal 8 karakter.',
            'password_login.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
