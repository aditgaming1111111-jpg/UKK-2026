<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk pembatalan catatan pelanggaran siswa.
 */
class CancelPelanggaranSiswaRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('cancel', $this->route('pelanggaranSiswa')) ?? false;
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['alasan' => ['required', 'string', 'min:3', 'max:1000']];
    }

    /**
     * Menormalkan masukan sebelum aturan validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['alasan' => $this->filled('alasan') ? trim((string) $this->input('alasan')) : null]);
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'alasan.required' => 'Alasan pembatalan wajib diisi.',
            'alasan.min' => 'Alasan pembatalan minimal 3 karakter.',
            'alasan.max' => 'Alasan pembatalan maksimal 1.000 karakter.',
        ];
    }
}
