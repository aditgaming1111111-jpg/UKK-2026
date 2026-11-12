<?php

namespace App\Http\Requests\Admin;

use App\Models\TahunAjaran;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Memeriksa kewenangan dan memvalidasi masukan untuk pemrosesan transisi siswa antar tahun ajaran.
 */
class ProcessStudentTransitionRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna yang sedang masuk berwenang menjalankan permintaan ini.
     */
    public function authorize(): bool
    {
        $academicYear = $this->route('tahunAjaran');

        return $academicYear instanceof TahunAjaran
            && ($this->user()?->can('update', $academicYear) ?? false);
    }

    /**
     * Menetapkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mode' => ['required', Rule::in(['naik', 'tinggal', 'lulus'])],
            'tahun_ajaran_tujuan_id' => [
                Rule::requiredIf(fn (): bool => $this->input('mode') !== 'lulus'),
                'nullable',
                'integer',
                'exists:t_tahun_ajaran,id',
            ],
            'pemetaan' => [Rule::requiredIf(fn (): bool => $this->input('mode') !== 'lulus'), 'array'],
            'pemetaan.*' => ['required', 'integer', 'exists:t_kelas,id'],
            'dikecualikan' => ['nullable', 'array'],
            'dikecualikan.*' => ['integer', 'distinct', 'exists:t_siswa,id'],
        ];
    }

    /**
     * Menyediakan pesan kesalahan validasi yang ditampilkan kepada pengguna.
     */
    public function messages(): array
    {
        return [
            'mode.required' => 'Pilih jenis proses siswa.',
            'mode.in' => 'Jenis proses siswa tidak valid.',
            'tahun_ajaran_tujuan_id.required' => 'Tahun ajaran tujuan wajib dipilih.',
            'tahun_ajaran_tujuan_id.exists' => 'Tahun ajaran tujuan tidak ditemukan.',
            'pemetaan.required' => 'Pemetaan kelas tujuan wajib diisi.',
            'pemetaan.array' => 'Pemetaan kelas tujuan tidak valid.',
            'pemetaan.*.required' => 'Pilih kelas tujuan untuk setiap kelas asal yang diproses.',
            'pemetaan.*.exists' => 'Kelas tujuan tidak ditemukan.',
            'dikecualikan.*.distinct' => 'Siswa tidak boleh dipilih sebagai pengecualian lebih dari sekali.',
            'dikecualikan.*.exists' => 'Siswa yang dikecualikan tidak ditemukan.',
        ];
    }
}
