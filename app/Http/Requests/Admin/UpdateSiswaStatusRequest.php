<?php

namespace App\Http\Requests\Admin;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $student = $this->route('siswa');

        return $student instanceof Siswa && ($this->user()?->can('changeStatus', $student) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['status_siswa' => ['required', 'string', Rule::in(['aktif', 'nonaktif'])]];
    }

    public function messages(): array
    {
        return [
            'status_siswa.required' => 'Status siswa wajib dipilih.',
            'status_siswa.in' => 'Status siswa harus aktif atau nonaktif.',
        ];
    }
}
