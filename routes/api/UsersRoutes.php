<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;

Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('list', [UsersController::class, 'list']);
    Route::patch('subscriptions/{id}/deactivate', [UsersController::class, 'deactivateSubscription']);
});

