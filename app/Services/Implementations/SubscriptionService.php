<?php


namespace App\Services\Implementations;

use App\Models\Subscription;
use App\Services\Interfaces\SubscriptionInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;


class SubscriptionService implements SubscriptionInterface
{
    public function createSubscription(array $data): Subscription
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data['created_by'] = $user->id;
            $data['updated_by'] = $user->id;
            $data['deleted_by'] = $user->id;
            $subscription = Subscription::create($data);
            DB::commit();
            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSubscriptionById(string $id): Subscription
    {
        return Subscription::findOrFail($id);
    }

    public function updateSubscription(string $id, array $data): Subscription
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data['updated_by'] = $user->id;
            $subscription = Subscription::findOrFail($id);
            $subscription->update($data);
            DB::commit();
            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteSubscription(string $id): void
    {
        Subscription::findOrFail($id)->delete();
    }

    public function getAllSubscriptions(): Collection
    {
        return Subscription::active()->get();
    }
}

