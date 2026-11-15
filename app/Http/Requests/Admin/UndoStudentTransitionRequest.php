<?php

namespace App\Http\Requests\Admin;

use App\Models\TahunAjaran;
use Illuminate\Foundation\Http\FormRequest;

class UndoStudentTransitionRequest extends FormRequest
{
    /**
     * Memeriksa kewenangan Admin untuk membatalkan proses pada tahun ajaran tersebut.
     */
    public function authorize(): bool
    {
        $academicYear = $this->route('tahunAjaran');

        return $academicYear instanceof TahunAjaran
            && ($this->user()?->can('update', $academicYear) ?? false);
    }

    /**
     * Tidak ada masukan tambahan untuk pembatalan selain identitas batch pada route.
     */
    public function rules(): array
    {
        return [];
    }
}
