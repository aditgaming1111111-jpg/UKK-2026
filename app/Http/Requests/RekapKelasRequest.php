<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RekapKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'guru'
            && $this->user()->guru()->where('status_aktif', true)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['nullable', 'integer', Rule::exists('t_tahun_ajaran', 'id')],
            'kelas_id' => ['nullable', 'integer', Rule::exists('t_kelas', 'id')],
            'q' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', Rule::when($this->filled('tanggal_mulai'), ['after_or_equal:tanggal_mulai'])],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak ditemukan.',
            'kelas_id.exists' => 'Kelas tidak ditemukan.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tanggal_selesai.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
        ];
    }
}
