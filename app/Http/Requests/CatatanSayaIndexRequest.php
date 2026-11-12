<?php

namespace App\Http\Requests;

use App\Models\PelanggaranSiswa;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk filter daftar catatan pelanggaran milik guru.
 */
class CatatanSayaIndexRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', PelanggaranSiswa::class)
            && $this->user()?->role === 'guru';
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
            'tahun_ajaran_id' => ['nullable', 'integer', Rule::exists('t_tahun_ajaran', 'id')],
            'kelas_id' => ['nullable', 'integer', Rule::exists('t_kelas', 'id')],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['aktif', 'dibatalkan'])],
        ];
    }

    /**
     * Memeriksa konsistensi lintas-field setelah validasi dasar selesai.
     */
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['tanggal_mulai', 'tanggal_selesai'])) {
                return;
            }

            $start = $this->date('tanggal_mulai');
            $end = $this->date('tanggal_selesai');

            if ($start !== null && $end !== null && $end->lt($start)) {
                $validator->errors()->add('tanggal_selesai', 'Tanggal akhir harus sama atau setelah tanggal mulai.');
            }
        }];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tahun_ajaran_id.integer' => 'Tahun ajaran tidak valid.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
            'kelas_id.integer' => 'Kelas tidak valid.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Tanggal akhir tidak valid.',
            'status.in' => 'Status catatan tidak valid.',
        ];
    }
}
