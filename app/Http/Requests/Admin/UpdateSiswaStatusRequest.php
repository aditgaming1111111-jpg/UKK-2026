<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk perubahan status siswa.
 */
class UpdateSiswaStatusRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        $student = $this->route('siswa');

        return $student instanceof Siswa && ($this->user()?->can('changeStatus', $student) ?? false);
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['status_siswa' => ['required', 'string', Rule::in(['aktif', 'nonaktif', 'pindah', 'keluar', 'lulus'])]];
    }

    /**
     * Memeriksa konsistensi lintas-field setelah validasi dasar selesai.
     */
    public function after(): array
    {
        return [function (Validator $validator): void {
            $student = $this->route('siswa');

            if (
                $validator->errors()->has('status_siswa')
                || $this->input('status_siswa') !== 'lulus'
                || ! $student instanceof Siswa
                || $student->hasCompletedGradeTwelvePlacement()
            ) {
                return;
            }

            $validator->errors()->add(
                'status_siswa',
                'Siswa hanya dapat ditandai lulus setelah penempatan kelas XII selesai dan tahun ajaran berakhir.'
            );
        }];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'status_siswa.required' => 'Status siswa wajib dipilih.',
            'status_siswa.in' => 'Status siswa tidak valid.',
        ];
    }
}
