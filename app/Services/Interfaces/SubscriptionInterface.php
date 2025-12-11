<?php


namespace App\Services\Interfaces;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionInterface
{
    public function createSubscription(array $data): Subscription;
    public function getSubscriptionById(string $id): Subscription;
    public function updateSubscription(string $id, array $data): Subscription;
    public function deleteSubscription(string $id): void;
    public function getAllSubscriptions(): Collection;
}

