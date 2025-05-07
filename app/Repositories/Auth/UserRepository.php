<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Helpers\CustomException;
use Exception;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface{

    public function findByEmail(string $email){
        return User::where('email',$email)->first();
    }

    public function createUser(array $data): User{
        return User::create([
            'email'=> $data['email'],
            'role_id' => $data['role_id'] ?? 2,
            'password'=> bcrypt($data['password']),
        ]);
    }

    public function findById(string $id){
        return User::with('role:id,name')->where('id',$id)->first();
    }

    public function getUserProfile(string $id){
        try {
            $user = User::select('id', 'email', 'name')
                ->with([
                    'profile' => function($query) {
                        $query->select('id', 'user_id', 'phone', 'name', 'address', 'date_of_birth');
                    },
                ])
                ->where('id', $id)
                ->first();

            if (!$user) {
                throw new CustomException("User tidak ditemukan", 404);
            }

            if (!$user->profile) {
                throw new CustomException("Profile user belum dibuat", 404);
            }

            return $user;
        } catch (CustomException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            throw new CustomException("Terjadi kesalahan saat mengambil data profile", 500);
        }
    }
}