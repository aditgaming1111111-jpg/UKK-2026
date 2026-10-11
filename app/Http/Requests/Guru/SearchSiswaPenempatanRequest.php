<?php

namespace App\Http\Requests\Guru;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;

class SearchSiswaPenempatanRequest extends FormRequest
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
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'tanggal_pelanggaran' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.required' => 'Masukkan NISN atau nama siswa.',
            'q.min' => 'Masukkan minimal 2 karakter untuk mencari siswa.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tanggal_pelanggaran.required' => 'Tanggal kejadian wajib dipilih.',
            'tanggal_pelanggaran.date' => 'Tanggal kejadian tidak valid.',
            'tanggal_pelanggaran.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
        ];
    }
}
