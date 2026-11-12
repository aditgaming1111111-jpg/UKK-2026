<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk penutupan penugasan wali kelas.
 */
class CloseWaliKelasRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('close', $this->route('waliKelas')) ?? false;
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_selesai' => ['required', 'date'],
        ];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
        ];
    }
}
