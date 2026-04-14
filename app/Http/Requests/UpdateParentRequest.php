<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false,
        ]);
    }

    public function rules(): array
    {
        $userId = $this->route('parent')->user_id;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone_number' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'student_id' => 'required|exists:students,id',
            'family_relationship' => 'required|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah digunakan.',
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'family_relationship.required' => 'Hubungan keluarga wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
    }
}