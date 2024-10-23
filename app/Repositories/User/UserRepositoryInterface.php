<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function register(array $data);
    public function findByEmail(string $email);
    public function createUserToken(User $user);
}
