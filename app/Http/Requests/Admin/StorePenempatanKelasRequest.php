<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePenempatanKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $siswa = $this->route('siswa');

        return $siswa?->status_siswa === 'aktif'
            && ($this->user()?->can('managePlacements', $siswa) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kelas_id' => ['required', 'integer', 'exists:t_kelas,id'],
            'tahun_ajaran_id' => ['required', 'integer', 'exists:t_tahun_ajaran,id'],
            'tanggal_mulai' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran yang dipilih tidak ditemukan.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
        ];
    }
}
