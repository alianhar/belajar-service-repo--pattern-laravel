<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Helpers\CustomException;
use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface{

    public function findByEmail(string $email){
        return User::where('email',$email)->first();
    }

    public function createUser(array $userData): User {
        try{
            DB::beginTransaction();

            $authData = User::create([
                'role_id' => $userData['role_id'] ?? 2,
                'email' => $userData['email'],
                'password' => bcrypt($userData['password'])
            ]);

            if(!$authData){
                DB::rollBack();
                throw new CustomException("gagal registrasi user",500);
            }

            $userProfile = UserProfile::create([
                'user_id' => $authData->id,
                'name' => $userData['name'],
                'address' => $userData['address'] ?? null,
                'date_of_birth' => $userData['date_of_birth'],
                'phone' => $userData['phone']
            ]);

            if(!$userProfile){
                DB::rollBack();
                throw new CustomException("gagal membuat data user ", 500);
            }

            DB::commit();

            return $authData;
        }
        catch(CustomException $e){
            DB::rollBack();
            throw $e;
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error('User creation error: ' . $e->getMessage());
            throw new CustomException("Terjadi kesalahan saat membuat user", 500);
        }
    }

    public function findById(string $id){
        return User::with('role:id,name')->where('id',$id)->first();
    }

    public function getUserProfile(string $id){
        try {
            $user = User::select('id', 'email', 'role_id')
                            ->with([
                                'profile' => function($query) {
                                    $query->select('id', 'user_id', 'phone', 'name', 'address', 'date_of_birth');
                                },
                                'role:id,name',
                            ])
                            ->where('id', $id)
                            ->first();

            if (!$user) {
                Log::error("User not found with ID: " . $id);
                throw new CustomException("User tidak ditemukan", 404);
            }

            if (!$user->profile) {
                Log::error("Profile not found for user ID: " . $id);
                throw new CustomException("Profile user tidak ditemukan", 404);
            }

            return $user;
        } catch (CustomException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Profile error: ' . $e->getMessage() . ' for user ID: ' . $id);
            throw new CustomException("Terjadi kesalahan saat mengambil data profile", 500);
        }
    }
}