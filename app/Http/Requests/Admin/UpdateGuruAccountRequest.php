<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk perubahan akun guru.
 */
class UpdateGuruAccountRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        $guru = $this->route('guru');

        return $guru instanceof Guru
            && $guru->user_id !== null
            && ($this->user()?->can('manageAccount', $guru) ?? false);
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password_login' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'password_login.min' => 'Kata sandi minimal 8 karakter.',
            'password_login.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
