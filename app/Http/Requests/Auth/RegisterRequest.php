<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // User account fields
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:60',
                'confirmed'
            ],
            // 'role_id' => [
            //     'sometimes',
            //     'exists:roles,id'
            // ],

            // Profile fields
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'max:20'
            ],
            'date_of_birth' => [
                'required',
                'date'
            ],
            'address' => [
                'nullable',
                'string'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // User account messages
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',

            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 60 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',


            // Profile messages
            'name.required' => 'nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',

            'phone.required' => 'nomor handphone harus diisi',
            'phone.string' => 'Nomor telepon harus berupa teks',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',

            'date_of_birth.required' => 'tanggal lahir harus diisi',
            'date_of_birth.date' => 'Format tanggal lahir tidak valid',

            'address.string' => 'Alamat harus berupa teks',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422));
    }
}