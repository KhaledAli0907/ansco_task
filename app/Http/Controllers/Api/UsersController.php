<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeactivateSubscriptionRequest;
use App\Services\Interfaces\UserInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    use ResponseTrait;
    public function __construct(protected UserInterface $userService)
    {
    }

    public function list(): JsonResponse
    {
        try {
            // Check if user is admin
            if (!auth()->user()->isAdmin()) {
                return $this->error403('You are not authorized to access this resource');
            }

            $users = $this->userService->getAllUsers();
            return $this->success200($users);
        } catch (\Exception $e) {
            return $this->error500($e->getMessage());
        }
    }

    public function deactivateSubscription(int $id): JsonResponse
    {
        try {
            // Check if user is admin
            if (!auth()->user()->isAdmin()) {
                return $this->error403('You are not authorized to deactivate subscriptions');
            }

            $userSubscription = $this->userService->deactivateSubscription($id);

            return $this->success200($userSubscription, 'Subscription deactivated successfully');
        } catch (\Exception $e) {
            return $this->error500($e->getMessage(), 'Failed to deactivate subscription');
        }
    }
}
