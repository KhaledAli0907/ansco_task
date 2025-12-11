<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriptionController;


Route::prefix('subscription')->middleware('auth:sanctum')->group(function () {
    Route::get('list', [SubscriptionController::class, 'list']);
    Route::get('detail/{id}', [SubscriptionController::class, 'detail']);
    Route::post('create', [SubscriptionController::class, 'create']);
    Route::put('update/{id}', [SubscriptionController::class, 'update']);
    Route::delete('delete/{id}', [SubscriptionController::class, 'delete']);
});
