<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiswaIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Siswa::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'kelas_id' => ['nullable', 'integer', 'exists:t_kelas,id'],
            'tingkat' => ['nullable', 'string', 'max:20', 'exists:t_kelas,tingkat'],
            'jurusan' => ['nullable', 'string', 'max:255', 'exists:t_kelas,jurusan'],
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tingkat.exists' => 'Tingkat yang dipilih tidak ditemukan.',
            'jurusan.exists' => 'Jurusan yang dipilih tidak ditemukan.',
            'status.in' => 'Status siswa tidak valid.',
        ];
    }
}
