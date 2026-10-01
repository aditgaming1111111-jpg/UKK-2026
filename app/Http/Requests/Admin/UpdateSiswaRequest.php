<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('siswa')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Siswa $siswa */
        $siswa = $this->route('siswa');

        return [
            'nisn' => ['required', 'digits:10', Rule::unique('t_siswa', 'nisn')->ignore($siswa)],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'alamat' => ['nullable', 'string', 'max:5000'],
            'tahun_masuk' => ['required', 'integer', 'between:1900,2100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus terdiri dari 10 angka.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'nama.max' => 'Nama siswa maksimal 255 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',
            'alamat.max' => 'Alamat maksimal 5000 karakter.',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi.',
            'tahun_masuk.integer' => 'Tahun masuk harus berupa angka.',
            'tahun_masuk.between' => 'Tahun masuk harus antara 1900 dan 2100.',
        ];
    }
}
