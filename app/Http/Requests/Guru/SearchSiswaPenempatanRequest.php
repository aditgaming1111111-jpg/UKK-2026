<?php

namespace App\Http\Requests\Guru;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk pencarian siswa untuk penempatan kelas.
 */
class SearchSiswaPenempatanRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Siswa::class) ?? false;
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'tanggal_pelanggaran' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'q.required' => 'Masukkan NISN atau nama siswa.',
            'q.min' => 'Masukkan minimal 2 karakter untuk mencari siswa.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tanggal_pelanggaran.required' => 'Tanggal kejadian wajib dipilih.',
            'tanggal_pelanggaran.date' => 'Tanggal kejadian tidak valid.',
            'tanggal_pelanggaran.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
        ];
    }
}
