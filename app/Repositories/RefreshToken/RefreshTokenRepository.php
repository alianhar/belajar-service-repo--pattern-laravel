<?php

namespace App\Repositories\RefreshToken;

use App\Helpers\ResponseHelper;
use App\Models\RefreshToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface{

    public function createToken(User $user, int $expirationDays = 30){
        try{
            $token = Str::random(64);

            $expiresAt = Carbon::now()->addDays($expirationDays);

            $this->revokeAllUserTokens($user);

            RefreshToken::create([
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => $expiresAt,
                'revoked' =>false,
            ]);

            return $token;
        }
        catch(Exception $e){
            return ResponseHelper::error($e->getMessage(),[],500);
        }
    }

    public function findValidToken(string $token){

        return RefreshToken::where('token',$token)
                                ->where('revoked',false)
                                ->where('expires_at','>',Carbon::now())
                                ->first();
    }

    public function revokeToken(string $token){

        $token = RefreshToken::where('token',$token)
                                ->where('revoked',false)
                                ->first();

        if($token){
            $token->update([
                'revoked' => true
            ]);

            return true;
        }

        return false;
    }

    public function revokeAllUserTokens(User $user){

        $user->refreshTokens()->where('revoked',false)
                                ->update([
                                    'revoked' => true
                                ]);
    }

    public function purgeExpiredTokens(){
        return RefreshToken::where('expires_at','<',Carbon::now())->delete();
    }

}