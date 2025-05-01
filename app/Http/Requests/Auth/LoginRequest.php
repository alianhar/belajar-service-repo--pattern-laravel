<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Rule;

class LoginRequest extends FormRequest{

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            // 'email' => [
            //     'required',
            //     'email',
            //     'exists:users,email'
            // ],
            // 'password'=> [
            //     'required',
            //     'string',
            //     // 'min:8'
            // ]
        ];
    }

    // public function messages(){
    //     return [
    //         'email.required' => 'email harus diisi',
    //         'email.exists' => 'email tidak ditemukan',
    //         'email.email' => 'email tidak valid',
    //         'password.required' => 'password harus diisi',
    //         'password.string' => 'password harus berupa karakter'
    //     ];
    // }
}