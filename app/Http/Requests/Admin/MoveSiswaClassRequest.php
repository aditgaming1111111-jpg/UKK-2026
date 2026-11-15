<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoveSiswaClassRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna berwenang memindahkan kelas siswa.
     */
    public function authorize(): bool
    {
        $student = $this->route('siswa');

        return $student instanceof Siswa
            && ($this->user()?->can('managePlacements', $student) ?? false);
    }

    /**
     * Menetapkan aturan validasi untuk perpindahan kelas.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kelas_id' => ['required', 'integer', Rule::exists('t_kelas', 'id')],
            'tanggal_pindah' => ['required', 'date'],
            'alasan' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * Menyediakan pesan validasi berbahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kelas_id.required' => 'Kelas tujuan wajib dipilih.',
            'kelas_id.exists' => 'Kelas tujuan yang dipilih tidak ditemukan.',
            'tanggal_pindah.required' => 'Tanggal pindah wajib diisi.',
            'tanggal_pindah.date' => 'Tanggal pindah tidak valid.',
            'alasan.required' => 'Alasan pindah kelas wajib diisi.',
            'alasan.max' => 'Alasan pindah kelas maksimal 2.000 karakter.',
        ];
    }
}
