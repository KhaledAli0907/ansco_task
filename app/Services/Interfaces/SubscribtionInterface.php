<?php


namespace App\Services\Interfaces;

use App\Models\Subscribtion;
use Illuminate\Database\Eloquent\Collection;

interface SubscribtionInterface
{
    public function createSubscribtion(array $data): Subscribtion;
    public function getSubscribtionById(string $id): Subscribtion;
    public function updateSubscribtion(string $id, array $data): Subscribtion;
    public function deleteSubscribtion(string $id): void;
    public function getAllSubscribtions(): Collection;
}
