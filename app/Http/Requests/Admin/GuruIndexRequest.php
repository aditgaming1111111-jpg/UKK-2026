<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Guru::class) ?? false;
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
            'status' => ['nullable', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $search = trim((string) $this->input('q', ''));

        $this->merge(['q' => $search !== '' ? $search : null]);
    }

    public function messages(): array
    {
        return [
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'status.in' => 'Status Guru tidak valid.',
        ];
    }
}
