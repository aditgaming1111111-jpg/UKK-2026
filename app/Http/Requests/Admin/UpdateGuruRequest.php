<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('guru')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_kepegawaian' => ['required', 'string', Rule::in(Guru::STATUS_KEPEGAWAIAN)],
            'nip' => ['required_if:status_kepegawaian,PNS', 'nullable', 'string', 'digits:18', Rule::unique('t_guru', 'nip')->ignore($this->route('guru'))],
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('t_guru', 'email')->ignore($this->route('guru')),
                Rule::unique('t_users', 'email')->ignore($this->route('guru')->user_id),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $nip = trim((string) $this->input('nip', ''));

        $this->merge([
            'nip' => $nip === '' ? null : $nip,
            'nama' => trim((string) $this->input('nama', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
        ]);
    }

    public function messages(): array
    {
        return [
            'status_kepegawaian.required' => 'Status kepegawaian wajib dipilih.',
            'status_kepegawaian.in' => 'Status kepegawaian harus PNS, PPPK, atau Non-PNS.',
            'nip.required_if' => 'NIP wajib diisi untuk Guru berstatus PNS.',
            'nip.digits' => 'NIP harus tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah digunakan Guru lain.',
            'nama.required' => 'Nama Guru wajib diisi.',
            'nama.max' => 'Nama Guru maksimal 100 karakter.',
            'email.required' => 'Email Guru wajib diisi.',
            'email.email' => 'Email Guru tidak valid.',
            'email.unique' => 'Email sudah digunakan pada data Guru atau akun pengguna lain.',
        ];
    }
}
