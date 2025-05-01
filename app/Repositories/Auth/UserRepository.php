<?php

namespace App\Repositories\Auth;

use App\Models\User;

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
}