<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

interface AuthServiceInterface{

    public function login(LoginRequest $loginRequest);

    public function register(RegisterRequest $registerRequest);
}