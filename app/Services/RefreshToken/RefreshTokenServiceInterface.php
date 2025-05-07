<?php

namespace App\Services\RefreshToken;

use App\Http\Requests\RefreshToken\RefreshTokenRequest;
use App\Models\User;
use Illuminate\Http\Request;

interface RefreshTokenServiceInterface{

    public function createAccessToken(User $user);

    public function createRefreshToken(User $user);

    public function setCookieRefreshToken(string $token);

    public function refreshAccessToken(Request $request);

    public function revokeRefreshToken(Request $request);
}