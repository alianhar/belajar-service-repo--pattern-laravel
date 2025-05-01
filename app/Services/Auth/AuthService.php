<?php

namespace App\Services\Auth;

use App\Helpers\CustomException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Auth\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function login(LoginRequest $loginRequest)
    {
        try {

            $email = $loginRequest->input('email');
            $password = $loginRequest->input('password');

            if (empty($email) || empty($password)) {
                throw new CustomException("Email dan password wajib diisi", 422);
            }

            $user = $this->userRepo->findByEmail($email);

            if (!$user || !Hash::check($password, $user->password)) {
                throw new CustomException("Email atau password salah", 401);
            }

            $token = $user->createToken("access_token")->plainTextToken;

            return ResponseHelper::success("Login sukses", [
                'user' => $user,
                'access_token' => $token
            ], 200);
        } catch (CustomException $e) {
            return ResponseHelper::error($e->getMessage(), [], $e->getStatusCode());
        } catch (Exception $e) {
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
            // return ResponseHelper::error($e->getMessage(),[],$e->getStatusCode());
            return dd($e->getMessage());
        }
        catch(Exception $e){
            return dd($e->getMessage());
            // return ResponseHelper::error("terjadi kesalahan sistem",[],500);
        }
    }
}
