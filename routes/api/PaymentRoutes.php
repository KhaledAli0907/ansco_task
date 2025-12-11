<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

Route::prefix('payment')->group(function () {
    Route::post('pay', [PaymentController::class, 'send_payment'])->middleware(['auth:sanctum', 'throttle:60,1']);
    Route::match(['GET', 'POST'], 'callback', [PaymentController::class, 'callBack']);
});
