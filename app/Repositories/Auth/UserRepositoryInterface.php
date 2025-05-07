<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\UserProfile;

interface UserRepositoryInterface{

    public function findByEmail(string $email);

    public function createUser(array $userData):User;

    public function findById(string $id);

    public function getUserProfile(string $id);
}