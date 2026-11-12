<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk filter daftar guru.
 */
class GuruIndexRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Guru::class) ?? false;
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    /**
     * Menormalkan masukan sebelum aturan validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $search = trim((string) $this->input('q', ''));

        $this->merge(['q' => $search !== '' ? $search : null]);
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'status.in' => 'Status Guru tidak valid.',
        ];
    }
}
