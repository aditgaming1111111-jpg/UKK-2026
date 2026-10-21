<?php

namespace App\Http\Requests\Admin;

use App\Models\PelanggaranKategori;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdatePelanggaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('pelanggaran')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pelanggaran = $this->route('pelanggaran');

        return [
            'kode' => ['required', 'string', 'max:30', Rule::unique('t_pelanggaran', 'kode')->ignore($pelanggaran)],
            'nama_pelanggaran' => ['required', 'string', 'max:100'],
            'kategori_id' => [
                'required',
                'integer',
                Rule::exists('t_pelanggaran_kategori', 'id')->where(static fn ($query) => $query
                    ->where('status_aktif', true)
                    ->orWhere('id', $pelanggaran->kategori_id)),
            ],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
            'status_aktif' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['kategori_id', 'status_aktif']) || ! $this->boolean('status_aktif')) {
                return;
            }

            $categoryIsActive = PelanggaranKategori::query()
                ->whereKey($this->integer('kategori_id'))
                ->where('status_aktif', true)
                ->exists();

            if (! $categoryIsActive) {
                $validator->errors()->add('kategori_id', 'Jenis pelanggaran hanya dapat diaktifkan pada kategori yang aktif.');
            }
        }];
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
            'nama_pelanggaran.max' => 'Nama pelanggaran maksimal 100 karakter.',
            'kategori_id.required' => 'Kategori pelanggaran wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak ditemukan atau tidak aktif untuk jenis baru.',
            'deskripsi.max' => 'Deskripsi maksimal 10.000 karakter.',
            'poin.required' => 'Nilai poin wajib diisi.',
            'poin.integer' => 'Nilai poin harus berupa bilangan bulat.',
            'poin.min' => 'Nilai poin harus antara 1 dan 100.',
            'poin.max' => 'Nilai poin harus antara 1 dan 100.',
            'status_aktif.required' => 'Status jenis pelanggaran wajib ditentukan.',
            'status_aktif.boolean' => 'Status jenis pelanggaran tidak valid.',
        ];
    }
}
