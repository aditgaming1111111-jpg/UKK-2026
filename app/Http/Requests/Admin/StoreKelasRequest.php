<?php

namespace App\Http\Requests\Admin;

use App\Models\Kelas;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk pembuatan data kelas.
 */
class StoreKelasRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Kelas::class) ?? false;
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'string', 'max:20'],
            'jurusan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Menormalkan masukan sebelum aturan validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_kelas' => trim((string) $this->input('nama_kelas', '')),
            'tingkat' => trim((string) $this->input('tingkat', '')),
            'jurusan' => $this->filled('jurusan') ? trim((string) $this->input('jurusan')) : null,
        ]);
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.max' => 'Nama kelas maksimal 50 karakter.',
            'tingkat.required' => 'Tingkat wajib diisi.',
            'tingkat.max' => 'Tingkat maksimal 20 karakter.',
            'jurusan.max' => 'Jurusan maksimal 255 karakter.',
        ];
    }
}
