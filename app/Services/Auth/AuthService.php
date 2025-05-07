<?php

namespace App\Services\Auth;

use App\Helpers\CustomException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Auth\UserRepositoryInterface;
use App\Services\RefreshToken\RefreshTokenServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface
{
    protected $userRepo;

    protected $jwtService;

    public function __construct(UserRepositoryInterface $userRepo,RefreshTokenServiceInterface $jwtService)
    {
        $this->userRepo = $userRepo;
        $this->jwtService = $jwtService;
    }

    public function login(LoginRequest $loginRequest)
    {
        try {
            $validated = $loginRequest->validated();
            $email = $validated['email'];
            $password = $validated['password'];

            if (empty($email) || empty($password)) {
                throw new CustomException("Email dan password wajib diisi", 422);
            }

            $user = $this->userRepo->findByEmail($email);

            if (!$user || !Hash::check($password, $user->password)) {
                throw new CustomException("Email atau password salah", 401);
            }

            $accessToken = $this->jwtService->createAccessToken($user);

            $refreshToken = $this->jwtService->createRefreshToken($user);

            if($accessToken && $refreshToken){
                $cookie = $this->jwtService->setCookieRefreshToken($refreshToken);

                return ResponseHelper::success("Login sukses", [
                    // 'user' => $user,
                    'accessToken' => $accessToken
                ], 200)->withCookie($cookie);
            }

            throw new CustomException("Login gagal, terjadi kesalahan",500);

        } catch (CustomException $e) {
            return ResponseHelper::error($e->getMessage(), [], $e->getStatusCode());
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return ResponseHelper::error("Terjadi kesalahan sistem", [], 500);
        }
    }

    public function register(RegisterRequest $registerRequest){
        try{
            $validatedUser = $registerRequest->validated();

            $validatedUser['role_id'] = $validatedUser['role_id'] ?? 2;

            if(!$validatedUser){
                throw new CustomException("Registrasi Gagal",400);
            }

            $user = $this->userRepo->createUser($validatedUser);

            // dd($user);
            return ResponseHelper::success("registrasi berhasil, Silakan Login",[
                'user' => $user
            ],200);
        }
        catch(CustomException $e){
            // Log::error("error custom", [
            //     'message' => $e->getMessage()
            // ]);
            return ResponseHelper::error($e->getMessage(),[],$e->getStatusCode());
            // return dd($e->getMessage());
        }
        catch(Exception $e){
            // return dd($e->getMessage());
            return ResponseHelper::error("terjadi kesalahan sistem",[],500);
        }
    }

    public function profile(){
        try{
            $user = Auth::user();

            if(!$user){
                throw new CustomException("user tidak ditemukan atau belum login",401);
            }

            $userData = $this->userRepo->getUserProfile($user);

            return ResponseHelper::success("berhasil mengambil data user",[
                'user' => $userData
            ],200);

        }
        catch(CustomException $e){
            return ResponseHelper::error($e->getMessage(),[],$e->getStatusCode());
        }
        catch(Exception $e){
            return ResponseHelper::error("terjadi kesalahan pada sistem",[],500);
        }
    }
}
