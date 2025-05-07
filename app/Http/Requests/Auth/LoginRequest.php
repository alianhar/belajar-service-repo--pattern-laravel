<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest{

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'email' => [
                'required',
                'email',
                'string'
            ],
            'password'=> [
                'required',
                'string',
            ]
        ];
    }

    public function messages(){
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.string' => 'Email harus berupa teks',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa teks'
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