<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher')->id;
        $userId = $this->route('teacher')->user_id;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone_number' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:50',
            'nip' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('teachers', 'nip')->ignore($teacherId),
            ],
            'nik' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('teachers', 'nik')->ignore($teacherId),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah digunakan.',
            'nip.unique' => 'NIP sudah digunakan.',
            'nik.unique' => 'NIK sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
    }
}