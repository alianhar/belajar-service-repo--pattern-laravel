<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthServiceInterface $authService){
        $this->authService = $authService;
    }

    public function login(LoginRequest $request){
        return $this->authService->login($request);
    }

    public function register(RegisterRequest $registerRequest){
        return $this->authService->register($registerRequest);
    }
}
