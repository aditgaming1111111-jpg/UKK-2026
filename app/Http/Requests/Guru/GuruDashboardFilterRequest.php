<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk filter dashboard guru.
 */
class GuruDashboardFilterRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'guru';
    }

    /**
     * Menetapkan aturan validasi untuk masukan permintaan.
     */
    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['nullable', 'integer', 'exists:t_tahun_ajaran,id'],
        ];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'tahun_ajaran_id.integer' => 'Tahun ajaran yang dipilih tidak valid.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
        ];
    }
}
