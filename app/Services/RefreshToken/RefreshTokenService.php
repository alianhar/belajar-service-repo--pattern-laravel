<?php

namespace App\Services\RefreshToken;

use App\Helpers\CustomException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\RefreshToken\RefreshTokenRequest;
use App\Models\RefreshToken;
use App\Models\User;
use App\Repositories\RefreshToken\RefreshTokenRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenService implements RefreshTokenServiceInterface{

    protected $refreshTokenRepo;

    public function __construct(RefreshTokenRepositoryInterface $refreshTokenRepo){
        $this->refreshTokenRepo = $refreshTokenRepo;
    }

    public function createAccessToken(User $user){
        try{
            $accessToken = JWTAuth::fromUser($user);

            if(!$accessToken){
                throw new CustomException("gagal membuat access token",500);
            }

            return $accessToken;
        }
        catch(CustomException $e){
            throw $e;
        }
        catch(Exception $e){
            throw new CustomException($e->getMessage(),500);
        }

    }

    public function createRefreshToken(User $user){
        try{
            $refreshTokenUser = $this->refreshTokenRepo->createToken($user);

            if(!$refreshTokenUser){
                throw new CustomException("tidak dapat membuat refresh token user",500);
            }

            return $refreshTokenUser;
        }
        catch(CustomException $e){
            throw $e;
        }
        catch(Exception $e){
            throw new CustomException($e->getMessage(), 500);
        }
    }

    public function setCookieRefreshToken(string $token){
        try{
            $refreshTokenCookie = cookie("refresh_token", $token,60 * 24 * 30, null, null, true, true, false, 'none');

            if(!$refreshTokenCookie){
                throw new CustomException("tidak dapat menyimpan cookie",500);
            }

            return $refreshTokenCookie;
        }
        catch(CustomException $e){
            throw $e;
        }
        catch(Exception $e){
            throw new CustomException($e->getMessage(), 500);
        }
    }

    public function refreshAccessToken(Request $refreshTokenRequest){

        try{
            $getAuthRefreshToken = $refreshTokenRequest->cookie('refresh_token');

            if(!$getAuthRefreshToken){
                throw new CustomException("gagal mengambil refresh token dari cookie",500);
            }

            $existedUserToken = $this->refreshTokenRepo->findValidToken($getAuthRefreshToken);

            if(!$existedUserToken){
                throw new CustomException("gagal menemukan token user ",500);
            }

            $user = $existedUserToken->user;

            if(!$user){
                throw new CustomException("user tidak ditemukan",500);
            }

            $validatedUserAndToken =  $this->createAccessToken($user);

            return ResponseHelper::success("berhasil membuat refresh Token",[
                'accessToken' => $validatedUserAndToken,
            ],200);
        }
        catch(CustomException $e){
            return ResponseHelper::error($e->getMessage(),[],$e->getStatusCode());
        }
        catch(Exception $e){
            return ResponseHelper::error($e->getMessage(),[],500);
        }
    }

    public function revokeRefreshToken(Request $request){
        try{
            $refreshToken = $request->cookie("refresh_token");

            if(!$refreshToken){
                throw new CustomException("tidak dapat mencabut refresh token",500);
            }

            return $this->refreshTokenRepo->revokeToken($refreshToken);
        }
        catch(CustomException $e){
            return ResponseHelper::error($e->getMessage(),[],$e->getStatusCode());
        }
        catch(Exception $e){
            return ResponseHelper::error($e->getMessage(),[],500);
        }
    }
}
