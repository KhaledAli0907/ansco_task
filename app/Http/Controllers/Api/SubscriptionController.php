<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Services\Interfaces\SubscribtionInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    public function __construct(protected SubscribtionInterface $subscribtionService)
    {
    }

    public function list(): JsonResponse
    {
        try {
            $subscribtions = $this->subscribtionService->getAllSubscribtions();
            return $this->success200($subscribtions);
        } catch (\Exception $e) {
            return $this->error500($e->getMessage());
        }
    }

    public function detail(string $id): JsonResponse
    {
        try {
            $subscribtion = $this->subscribtionService->getSubscribtionById($id);
            return $this->success200($subscribtion);
        } catch (\Exception $e) {
            return $this->error500($e->getMessage());
        }
    }

    public function create(CreateSubscriptionRequest $request): JsonResponse
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return $this->error403('You are not authorized to create a subscription');
            }
            $subscribtion = $this->subscribtionService->createSubscribtion($request->all());
            return $this->success201($subscribtion, 'Subscription created successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to create subscription');
        }
    }

    public function update(UpdateSubscriptionRequest $request, $id): JsonResponse
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return $this->error403('You are not authorized to update a subscription');
            }
            $subscribtion = $this->subscribtionService->updateSubscribtion($id, $request->all());
            return $this->success200($subscribtion, 'Subscription updated successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to update subscription');
        }
    }

    public function delete(string $id): JsonResponse
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return $this->error403('You are not authorized to delete a subscription');
            }
            $this->subscribtionService->deleteSubscribtion($id);
            return $this->success200('Subscription deleted successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to delete subscription');
        }
    }
    
}
