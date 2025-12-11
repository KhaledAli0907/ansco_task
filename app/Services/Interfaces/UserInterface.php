<?php

namespace App\Services\Interfaces;

use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Collection;

interface UserInterface
{
    public function getUserById(int $id): User;
    public function updateUser(int $id, array $data): User;
    public function deleteUser(int $id): void;
    public function getAllUsers(): Collection;
    public function deactivateSubscription(int $subscriptionId): UserSubscription;
}
