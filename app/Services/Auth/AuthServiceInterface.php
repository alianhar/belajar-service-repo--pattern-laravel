<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;

interface AuthServiceInterface{

    public function login(LoginRequest $loginRequest);

    public function register(RegisterRequest $registerRequest);

    public function profile();

    public function logout(Request $request);
}