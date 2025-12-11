<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface AuthInterface
{
    public function register(array $data): User;
    public function login(array $data): array;
    public function logout(string $token): void;
    public function refreshToken(string $token): string;

    // TODO: V2.0.0
    // public function forgotPassword(string $email): void;
    // public function resetPassword(string $token, string $password): void;

}
