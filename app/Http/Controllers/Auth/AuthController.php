<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServiceInterface;
use App\Services\RefreshToken\RefreshTokenServiceInterface;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    protected $jwtService;

    public function __construct(AuthServiceInterface $authService,RefreshTokenServiceInterface $jwtService){
        $this->authService = $authService;
        $this->jwtService = $jwtService;
    }

    public function login(LoginRequest $request){
        return $this->authService->login($request);
    }

    public function register(RegisterRequest $registerRequest){
        return $this->authService->register($registerRequest);
    }

    public function profile(){
        return $this->authService->profile();
    }

    public function refresh(Request $request){
        return $this->jwtService->refreshAccessToken($request);
    }

    public function logout(Request $request){
        return $this->authService->logout($request);
    }
}
