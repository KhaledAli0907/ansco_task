<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Collection;

class UserService implements UserInterface
{
    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        $user->refresh();
        return $user;
    }

    public function deleteUser(int $id): void
    {
        User::findOrFail($id)->delete();
    }

    public function getAllUsers(): Collection
    {
        return User::with('userSubscriptions')->users()->get();
    }

    public function deactivateSubscription(int $subscriptionId): UserSubscription
    {
        $userSubscription = UserSubscription::findOrFail($subscriptionId);

        if ($userSubscription->status === 'cancelled') {
            throw new \Exception('Subscription is already cancelled');
        }

        if ($userSubscription->status === 'expired') {
            throw new \Exception('Subscription is already expired');
        }

        $userSubscription->update([
            'status' => 'cancelled',
        ]);

        return $userSubscription->fresh();
    }
}
