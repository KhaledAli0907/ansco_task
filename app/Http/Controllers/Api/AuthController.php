<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Interfaces\AuthInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseTrait;
    public function __construct(protected AuthInterface $authService)
    {
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());

            return $this->success201($user, 'User registered successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to register user');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->validated());

            return $this->success200(
                ['user' => $data['user'], 'token' => $data['token']],
                'User logged in successfully'
            );
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to login user');
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->bearerToken());
            return $this->success200('User logged out successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to logout user');
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $token = $this->authService->refreshToken($request->bearerToken());
            return $this->success200(['token' => $token], 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to refresh token');
        }
    }
}
