<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    use ApiResponseTrait;

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(RegisterRequest $request)
    {
        $this->userRepository->register($request->only('name', 'email', 'password'));
        return $this->returnSuccessMessage('User registered successfully', Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);

        if (! $user || !Hash::check($request->password, $user->password)) {
            return $this->returnValidationMsg('The provided credentials are incorrect.', Response::HTTP_UNAUTHORIZED);
        }

        return $this->createToken($user);
    }

    public function createToken($user): JsonResponse
    {
        $data = $this->userRepository->createUserToken($user);
        return $this->returnData('data', $data, $this->getSuccessMessage());
    }
}
