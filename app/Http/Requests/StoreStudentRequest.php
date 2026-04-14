<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'nisn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('students', 'nisn')->whereNotNull('nisn')
            ],
            'nis' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('students', 'nis')->whereNotNull('nis')
            ],
            'grade_level' => 'required|in:X,XI,XII',
            'class_name' => 'required|string|max:50',
            'major' => 'nullable|string|max:50',
            'g7_kaih_class_id' => 'nullable|exists:g7_kaih_classes,id', // TAMBAHAN
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'nis.unique' => 'NIS sudah digunakan.',
            'grade_level.required' => 'Tingkat kelas wajib diisi.',
            'grade_level.in' => 'Tingkat kelas harus X, XI, atau XII.',
            'class_name.required' => 'Nama kelas wajib diisi.',
            'g7_kaih_class_id.exists' => 'Kelas yang dipilih tidak valid.', // TAMBAHAN
            'gender.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
    }
}