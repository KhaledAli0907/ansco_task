<?php


namespace App\Services\Implementations;

use App\Models\Subscribtion;
use App\Services\Interfaces\SubscribtionInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;


class SubscribtionService implements SubscribtionInterface
{
    public function createSubscribtion(array $data): Subscribtion
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data['created_by'] = $user->id;
            $data['updated_by'] = $user->id;
            $data['deleted_by'] = $user->id;
            $subscribtion = Subscribtion::create($data);
            DB::commit();
            return $subscribtion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSubscribtionById(string $id): Subscribtion
    {
        return Subscribtion::findOrFail($id);
    }

    public function updateSubscribtion(string $id, array $data): Subscribtion
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data['updated_by'] = $user->id;
            $subscribtion = Subscribtion::findOrFail($id);
            $subscribtion->update($data);
            DB::commit();
            return $subscribtion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteSubscribtion(string $id): void
    {
        Subscribtion::findOrFail($id)->delete();
    }

    public function getAllSubscribtions(): Collection
    {
        return Subscribtion::active()->get();
    }
}
