<?php

namespace App\Http\Requests\Admin;

use App\Models\Pelanggaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePelanggaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Pelanggaran::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:30', Rule::unique('t_pelanggaran', 'kode')],
            'nama_pelanggaran' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'integer', Rule::exists('t_pelanggaran_kategori', 'id')->where('status_aktif', true)],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'poin' => ['required', 'integer', 'min:1', 'max:65535'],
            'status_aktif' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode' => mb_strtoupper(trim((string) $this->input('kode', ''))),
            'nama_pelanggaran' => trim((string) $this->input('nama_pelanggaran', '')),
            'status_aktif' => $this->boolean('status_aktif'),
        ]);
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode pelanggaran wajib diisi.',
            'kode.max' => 'Kode pelanggaran maksimal 30 karakter.',
            'kode.unique' => 'Kode pelanggaran sudah digunakan.',
            'nama_pelanggaran.required' => 'Nama pelanggaran wajib diisi.',
            'nama_pelanggaran.max' => 'Nama pelanggaran maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori pelanggaran wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak ditemukan atau sedang nonaktif.',
            'deskripsi.max' => 'Deskripsi maksimal 10.000 karakter.',
            'poin.required' => 'Nilai poin wajib diisi.',
            'poin.integer' => 'Nilai poin harus berupa bilangan bulat.',
            'poin.min' => 'Nilai poin minimal 1.',
            'poin.max' => 'Nilai poin maksimal 65.535.',
            'status_aktif.required' => 'Status jenis pelanggaran wajib ditentukan.',
            'status_aktif.boolean' => 'Status jenis pelanggaran tidak valid.',
        ];
    }
}
