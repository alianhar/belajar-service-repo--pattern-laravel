<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface UserRepositoryInterface{

    public function findByEmail(string $email);

    public function createUser(array $user) : User;

    public function findById(string $id);

    public function getUserProfile(string $id);
}