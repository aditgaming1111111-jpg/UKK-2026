<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk filter daftar siswa.
 */
class SiswaIndexRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:100'],
            'kelas_id' => ['nullable', 'integer', 'exists:t_kelas,id'],
            'tahun_ajaran_id' => ['nullable', 'integer', 'exists:t_tahun_ajaran,id'],
            'tingkat' => ['nullable', 'string', 'max:20', 'exists:t_kelas,tingkat'],
            'jurusan' => ['nullable', 'string', 'max:255', 'exists:t_kelas,jurusan'],
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif', 'pindah', 'keluar', 'lulus'])],
        ];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
            'tingkat.exists' => 'Tingkat yang dipilih tidak ditemukan.',
            'jurusan.exists' => 'Jurusan yang dipilih tidak ditemukan.',
            'status.in' => 'Status siswa tidak valid.',
        ];
    }
}
