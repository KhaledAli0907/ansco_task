<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Services\Interfaces\SubscriptionInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ResponseTrait;
    public function __construct(protected SubscriptionInterface $subscriptionService)
    {
    }

    public function list(): JsonResponse
    {
        try {
            $subscriptions = $this->subscriptionService->getAllSubscriptions();
            return $this->success200($subscriptions);
        } catch (\Exception $e) {
            return $this->error500($e->getMessage());
        }
    }

    public function detail(string $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionService->getSubscriptionById($id);
            return $this->success200($subscription);
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
            $subscription = $this->subscriptionService->createSubscription($request->all());
            return $this->success201($subscription, 'Subscription created successfully');
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
            $subscription = $this->subscriptionService->updateSubscription($id, $request->all());
            return $this->success200($subscription, 'Subscription updated successfully');
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
            $this->subscriptionService->deleteSubscription($id);
            return $this->success200('Subscription deleted successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to delete subscription');
        }
    }

}
