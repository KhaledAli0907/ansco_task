<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\Interfaces\AuthInterface;
use DB;
use Hash;
use Laravel\Sanctum\Sanctum;


class AuthService implements AuthInterface
{
    public function register(array $data): User
    {
        DB::beginTransaction();
        try {
            $user = User::create($data);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            throw new \Exception('User not found');
        }
        if (!Hash::check($data['password'], $user->password)) {
            throw new \Exception('Invalid password');
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(string $token): void
    {
        request()->user()->currentAccessToken()->delete();
    }

    public function refreshToken(string $token): string
    {
        request()->user()->currentAccessToken()->delete();
        $token = request()->user()->createToken('auth_token')->plainTextToken;
        return $token;
    }
}
