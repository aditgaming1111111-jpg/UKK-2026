<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk perubahan tahun ajaran.
 */
class UpdateTahunAjaranRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('tahunAjaran')) ?? false;
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_tahun_ajaran' => [
                'required', 'string', 'max:20',
                Rule::unique('t_tahun_ajaran', 'nama_tahun_ajaran')->ignore($this->route('tahunAjaran')),
            ],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status_aktif' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Menormalkan masukan sebelum aturan validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_tahun_ajaran' => trim((string) $this->input('nama_tahun_ajaran', '')),
            'status_aktif' => $this->boolean('status_aktif'),
        ]);
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'nama_tahun_ajaran.required' => 'Nama tahun ajaran wajib diisi.',
            'nama_tahun_ajaran.max' => 'Nama tahun ajaran maksimal 20 karakter.',
            'nama_tahun_ajaran.unique' => 'Nama tahun ajaran sudah digunakan.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'status_aktif.boolean' => 'Status aktif tahun ajaran tidak valid.',
        ];
    }
}
