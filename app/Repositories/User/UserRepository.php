<?php

namespace App\Repositories\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function createUserToken(User $user): array
    {
        $tokenName = hash('sha256', Str::random(40));
        $token =  $user->createToken($tokenName)->plainTextToken;
        return $data = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

    }
}
