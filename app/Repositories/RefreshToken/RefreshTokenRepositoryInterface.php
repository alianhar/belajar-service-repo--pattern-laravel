<?php

namespace App\Repositories\RefreshToken;

use App\Models\User;

interface RefreshTokenRepositoryInterface{

    public function createToken(User $user, int $expirationDays = 30);

    public function findValidToken(string $token);

    public function revokeToken(string $token);

    public function revokeAllUserTokens(User $user);

    public function purgeExpiredTokens();
}