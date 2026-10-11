<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CancelPelanggaranSiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('cancel', $this->route('pelanggaranSiswa')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['alasan' => ['required', 'string', 'min:3', 'max:1000']];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['alasan' => $this->filled('alasan') ? trim((string) $this->input('alasan')) : null]);
    }

    public function messages(): array
    {
        return [
            'alasan.required' => 'Alasan pembatalan wajib diisi.',
            'alasan.min' => 'Alasan pembatalan minimal 3 karakter.',
            'alasan.max' => 'Alasan pembatalan maksimal 1.000 karakter.',
        ];
    }
}
