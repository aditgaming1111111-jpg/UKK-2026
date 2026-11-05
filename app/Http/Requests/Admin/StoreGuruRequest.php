<?php

namespace App\Http\Requests\Admin;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Guru::class) ?? false;
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
            'nip' => ['required_if:status_kepegawaian,PNS', 'nullable', 'string', 'digits:18', 'unique:t_guru,nip'],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:t_guru,email', Rule::unique('t_users', 'email')],
            'password_login' => ['required', 'string', 'min:8', 'confirmed'],
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
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama Guru wajib diisi.',
            'nama.max' => 'Nama Guru maksimal 100 karakter.',
            'email.required' => 'Email Guru wajib diisi.',
            'email.email' => 'Email Guru tidak valid.',
            'email.unique' => 'Email sudah digunakan pada data Guru atau akun pengguna.',
            'password_login.required' => 'Kata sandi wajib diisi.',
            'password_login.min' => 'Kata sandi minimal 8 karakter.',
            'password_login.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
