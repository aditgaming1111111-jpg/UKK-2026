<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Siswa::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'regex:/\A[0-9]{10}\z/', Rule::unique('t_siswa', 'nis')],
            'nisn' => ['required', 'string', 'regex:/\A[0-9]{10}\z/', Rule::unique('t_siswa', 'nisn')],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:5000'],
            'tahun_masuk' => ['required', 'integer', 'between:1900,2100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.required' => 'NIS wajib diisi.',
            'nis.regex' => 'NIS harus terdiri dari tepat 10 digit angka (0–9).',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.regex' => 'NISN harus terdiri dari tepat 10 digit angka (0–9).',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'nama.max' => 'Nama siswa maksimal 100 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'alamat.max' => 'Alamat maksimal 5000 karakter.',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi.',
            'tahun_masuk.integer' => 'Tahun masuk harus berupa angka.',
            'tahun_masuk.between' => 'Tahun masuk harus antara 1900 dan 2100.',
        ];
    }
}
